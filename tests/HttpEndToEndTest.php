<?php
declare(strict_types=1);

require_once __DIR__ . '/BaseWebTestCase.php';
require_once __DIR__ . '/TestDataFactory.php';

use GuzzleHttp\Cookie\CookieJar;

/**
 * End-to-end HTTP tests rewritten to be TDD-friendly:
 * - Each test builds its own fixtures (no reliance on pre-existing rows).
 * - Cleanup happens after every test to keep DB state isolated.
 * - Tests are narrower and deterministic (fixed times/IDs).
 * @method CookieJar loginPatient(array $patient)
 * @method CookieJar loginDoctor(array $doctor)
 * @method CookieJar loginAdmin(array $admin)
 */
final class HttpEndToEndTest extends BaseWebTestCase
{
    /** @var array<string,mixed> */
    private $patient;
    /** @var array<string,mixed> */
    private $doctor;
    /** @var array<string,mixed> */
    private $admin;

    /** @var list<int> */
    private $createdPatients = [];
    /** @var list<string> */
    private $createdDoctors = [];
    /** @var list<string> */
    private $createdAdmins = [];
    /** @var list<int> */
    private $createdAppointments = [];
    /** @var list<int> */
    private $createdPrescriptions = [];

    protected function setUp(): void
    {
        parent::setUp();
        $this->createdPatients = [];
        $this->createdDoctors = [];
        $this->createdAdmins = [];
        $this->createdAppointments = [];
        $this->createdPrescriptions = [];

        $this->patient = TestDataFactory::createPatient(self::$db);
        $this->doctor = TestDataFactory::createDoctor(self::$db);
        $this->admin = TestDataFactory::createAdmin(self::$db);

        $this->createdPatients[] = $this->patient['pid'];
        $this->createdDoctors[] = $this->doctor['username'];
        $this->createdAdmins[] = $this->admin['username'];
    }

    protected function tearDown(): void
    {
        foreach (array_unique($this->createdPrescriptions) as $id) {
            TestDataFactory::deletePrescription(self::$db, (int) $id);
        }
        foreach (array_unique($this->createdAppointments) as $id) {
            TestDataFactory::deleteAppointment(self::$db, (int) $id);
        }
        foreach (array_unique($this->createdDoctors) as $username) {
            TestDataFactory::deleteDoctor(self::$db, $username);
        }
        foreach (array_unique($this->createdPatients) as $pid) {
            TestDataFactory::deletePatient(self::$db, (int) $pid);
        }
        foreach (array_unique($this->createdAdmins) as $username) {
            TestDataFactory::deleteAdmin(self::$db, $username);
        }
        parent::tearDown();
    }

    // Login/public smoke coverage moved to HttpSmokeTest and PatientAuthTest.

    public function testPatientBookingCreatesAppointment(): void
    {
        $jar = $this->loginPatient($this->patient);

        $doctor  = $this->doctor['username'];
        $docFees = $this->doctor['docFees'];
        $appdate = date('Y-m-d', strtotime('+2 days'));
        $apptime = '10:00:00';

        $response = $this->post('actions/book_appointment.php', [
            'form_params' => [
                'doctor'     => $doctor,
                'docFees'    => $docFees,
                'appdate'    => $appdate,
                'apptime'    => $apptime,
                'app-submit' => 'Book',
            ],
            'cookies' => $jar,
            'allow_redirects' => false,
        ]);

        $body = (string) $response->getBody();
        $this->assertSame(200, $response->getStatusCode(), 'Booking should return 200 with alert/redirect');
        $this->assertStringContainsString('successfully booked', $body, 'Expected booking success message');

        $id = $this->latestAppointmentId($this->patient['pid'], $doctor, $appdate, $apptime);
        $this->assertNotNull($id, 'Appointment row should exist after booking');
        $this->createdAppointments[] = (int) $id;
    }

    public function testPrescriptionListShowsEntriesForPatientWithPrescriptions(): void
    {
        $patientWithRx = TestDataFactory::createPatient(self::$db);
        $this->createdPatients[] = $patientWithRx['pid'];

        $prescriptionId = random_int(200000, 900000);
        $appdate = date('Y-m-d', strtotime('+4 days'));
        $apptime = '15:00:00';
        TestDataFactory::createPrescription(self::$db, [
            'doctor'       => $this->doctor['username'],
            'pid'          => $patientWithRx['pid'],
            'ID'           => $prescriptionId,
            'fname'        => $patientWithRx['fname'],
            'lname'        => $patientWithRx['lname'],
            'appdate'      => $appdate,
            'apptime'      => $apptime,
            'disease'      => 'Migraine',
            'allergy'      => 'None',
            'prescription' => 'Test prescription entry',
        ]);
        $this->createdPrescriptions[] = $prescriptionId;

        $jar = $this->loginPatient($patientWithRx);

        $response = $this->get('views/patient/dashboard.php', $status, $jar);
        $this->assertSame(200, $status, 'Patient dashboard should load');

        $this->assertStringContainsString($this->doctor['username'], $response, 'Doctor name should appear in prescription list');
        $this->assertStringContainsString((string) $prescriptionId, $response, 'Appointment ID should appear in prescription list');
        $this->assertStringContainsString('Migraine', $response, 'Disease should appear in prescription list');
    }

    public function testPatientCancelsAppointment(): void
    {
        $jar = $this->loginPatient($this->patient);

        $doctor  = $this->doctor['username'];
        $docFees = $this->doctor['docFees'];
        $appdate = date('Y-m-d', strtotime('+3 days'));
        $apptime = '09:30:00';

        $book = $this->post('actions/book_appointment.php', [
            'form_params' => [
                'doctor'     => $doctor,
                'docFees'    => $docFees,
                'appdate'    => $appdate,
                'apptime'    => $apptime,
                'app-submit' => 'Book',
            ],
            'cookies' => $jar,
            'allow_redirects' => false,
        ]);
        $this->assertSame(200, $book->getStatusCode(), 'Booking should succeed before cancel');
        $this->assertStringContainsString('successfully booked', (string) $book->getBody(), 'Booking should return success message');

        $id = $this->latestAppointmentId($this->patient['pid'], $doctor, $appdate, $apptime);
        $this->assertNotNull($id, 'Booked appointment should exist');
        $this->createdAppointments[] = (int) $id;

        $this->setAppointmentActive((int) $id);

        $response = $this->get('actions/cancel_patient_appointment.php?ID=' . $id, $status, $jar);
        $this->assertSame(200, $status, 'Cancel action should respond with alert/redirect');
        $this->assertStringContainsString('successfully cancelled', $response, 'Cancel should report success');
        $this->assertTrue($this->appointmentIsCancelled((int) $id), 'Appointment should be marked cancelled');
    }

    public function testDoctorCreatesPrescription(): void
    {
        $jar = $this->loginDoctor($this->doctor);

        $ID = random_int(500000, 900000);
        $appdate = date('Y-m-d', strtotime('+3 days'));
        $apptime = '11:00:00';

        $response = $this->post('views/doctor/prescribe.php', [
            'form_params' => [
                'prescribe'    => 'Prescribe',
                'pid'          => $this->patient['pid'],
                'ID'           => $ID,
                'fname'        => $this->patient['fname'],
                'lname'        => $this->patient['lname'],
                'appdate'      => $appdate,
                'apptime'      => $apptime,
                'disease'      => 'Test Disease',
                'allergy'      => 'None',
                'prescription' => 'Test Rx entry',
            ],
            'cookies' => $jar,
            'allow_redirects' => false,
        ]);

        $body = (string) $response->getBody();
        $this->assertSame(200, $response->getStatusCode(), 'Prescribe action should return 200 with alert');
        $this->assertStringContainsString('Prescribed successfully', $body, 'Expected success message after prescribing');

        $this->assertTrue($this->prescriptionExists($ID), 'Prescription row should exist after prescribing');
        $this->createdPrescriptions[] = $ID;
    }

    public function testAdminAddsDoctor(): void
    {
        $jar = $this->loginAdmin($this->admin);

        $username = 'autodoc_' . uniqid();
        $password = 'pass123';
        $email    = $username . '@example.com';
        $spec     = 'General';
        $fees     = 123;

        $response = $this->post('views/admin/admin_func.php', [
            'form_params' => [
                'docsub'    => 'Add',
                'doctor'    => $username,
                'dpassword' => $password,
                'demail'    => $email,
                'special'   => $spec,
                'docFees'   => $fees,
            ],
            'cookies' => $jar,
            'allow_redirects' => false,
        ]);

        $body = (string) $response->getBody();
        $this->assertSame(200, $response->getStatusCode(), 'Add doctor should return 200 with alert');
        $this->assertStringContainsString('Doctor added successfully', $body, 'Expected success message for add doctor');
        $this->assertTrue($this->doctorExists($username), 'Doctor row should exist after adding');
        $this->createdDoctors[] = $username;
    }

    private function doctorExists(string $username): bool
    {
        $stmt = self::$db->prepare('SELECT 1 FROM doctb WHERE username = ? LIMIT 1');
        if (!$stmt) {
            return false;
        }
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $exists = $result && $result->num_rows > 0;
        $stmt->close();
        return $exists;
    }

    private function prescriptionExists(int $ID): bool
    {
        $stmt = self::$db->prepare('SELECT 1 FROM prestb WHERE ID = ? LIMIT 1');
        if (!$stmt) {
            return false;
        }
        $stmt->bind_param('i', $ID);
        $stmt->execute();
        $result = $stmt->get_result();
        $exists = $result && $result->num_rows > 0;
        $stmt->close();
        return $exists;
    }

    private function appointmentIsCancelled(int $id): bool
    {
        $stmt = self::$db->prepare('SELECT userStatus FROM appointmenttb WHERE ID = ? LIMIT 1');
        if (!$stmt) {
            return false;
        }
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result ? $result->fetch_assoc() : null;
        $stmt->close();
        return $row && (int) $row['userStatus'] === 0;
    }

    private function setAppointmentActive(int $id): void
    {
        $stmt = self::$db->prepare('UPDATE appointmenttb SET userStatus = 1, doctorStatus = 1 WHERE ID = ?');
        if (!$stmt) {
            return;
        }
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $stmt->close();
    }

    private function latestAppointmentId(int $pid, string $doctor, string $appdate, string $apptime): ?int
    {
        $stmt = self::$db->prepare('SELECT ID FROM appointmenttb WHERE pid = ? AND doctor = ? AND appdate = ? AND apptime = ? ORDER BY ID DESC LIMIT 1');
        if (!$stmt) {
            return null;
        }
        $stmt->bind_param('isss', $pid, $doctor, $appdate, $apptime);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result ? $result->fetch_assoc() : null;
        $stmt->close();
        return $row ? (int) $row['ID'] : null;
    }
}
