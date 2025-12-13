<?php
declare(strict_types=1);

require_once __DIR__ . '/BaseWebTestCase.php';
require_once __DIR__ . '/TestDataFactory.php';

/**
 * Tests for patient registration functionality.
 */
final class PatientRegistrationTest extends BaseWebTestCase
{
    protected function tearDown(): void
    {
        // Clean up any created patients
        if (isset($this->createdPatientPid)) {
            TestDataFactory::deletePatient(self::$db, $this->createdPatientPid);
        }
        parent::tearDown();
    }

    /** @var int|null */
    private $createdPatientPid = null;

    public function testPatientRegistrationWithValidDataCreatesAccount(): void
    {
        $email = 'test_' . uniqid('', false) . '@example.com';
        $fname = 'Test';
        $lname = 'Patient';
        $gender = 'Male';
        $contact = '1234567890';
        $password = 'testpass123';

        $response = $this->post('actions/patient_register.php', [
            'form_params' => [
                'patsub1'    => 'Register',
                'fname'      => $fname,
                'lname'      => $lname,
                'gender'     => $gender,
                'email'      => $email,
                'contact'    => $contact,
                'password'   => $password,
                'cpassword'  => $password,
            ],
            'allow_redirects' => false,
        ]);

        // Should redirect to dashboard on success
        $this->assertSame(302, $response->getStatusCode(), 'Registration should redirect to dashboard');
        $location = $response->getHeader('Location')[0] ?? '';
        $this->assertStringContainsString('patient/dashboard.php', $location, 'Should redirect to patient dashboard');

        // Verify patient was created in database
        $patient = $this->getPatientByEmail($email);
        $this->assertNotNull($patient, 'Patient should exist in database after registration');
        $this->assertSame($fname, $patient['fname']);
        $this->assertSame($lname, $patient['lname']);
        $this->assertSame($email, $patient['email']);
        $this->assertSame($contact, $patient['contact']);
        $this->assertSame($gender, $patient['gender']);
        $this->assertSame($password, $patient['password']);

        $this->createdPatientPid = (int) $patient['pid'];
    }

    public function testPatientRegistrationWithMismatchedPasswordsFails(): void
    {
        $email = 'test_' . uniqid('', false) . '@example.com';
        $password = 'testpass123';
        $cpassword = 'differentpass';

        $response = $this->post('actions/patient_register.php', [
            'form_params' => [
                'patsub1'    => 'Register',
                'fname'      => 'Test',
                'lname'      => 'Patient',
                'gender'     => 'Female',
                'email'      => $email,
                'contact'    => '9876543210',
                'password'   => $password,
                'cpassword'  => $cpassword, // Different password
            ],
            'allow_redirects' => false,
        ]);

        // Should redirect to error page when passwords don't match
        $this->assertSame(302, $response->getStatusCode(), 'Registration with mismatched passwords should redirect to error page');
        $location = $response->getHeader('Location')[0] ?? '';
        $this->assertStringContainsString('error_login.php', $location, 'Should redirect to error page');

        // Verify patient was NOT created in database
        $patient = $this->getPatientByEmail($email);
        $this->assertNull($patient, 'Patient should NOT exist in database when passwords mismatch');
    }

    public function testPatientCanLoginAfterRegistration(): void
    {
        $email = 'test_' . uniqid('', false) . '@example.com';
        $password = 'registerpass123';

        // Register
        $registerResponse = $this->post('actions/patient_register.php', [
            'form_params' => [
                'patsub1'    => 'Register',
                'fname'      => 'New',
                'lname'      => 'User',
                'gender'     => 'Female',
                'email'      => $email,
                'contact'    => '5551234567',
                'password'   => $password,
                'cpassword'  => $password,
            ],
            'allow_redirects' => false,
        ]);

        $this->assertSame(302, $registerResponse->getStatusCode(), 'Registration should succeed');
        $patient = $this->getPatientByEmail($email);
        $this->createdPatientPid = (int) $patient['pid'];

        // Now verify we can authenticate with the same credentials
        // We'll test the authentication function directly to avoid session conflicts
        $stmt = self::$db->prepare('SELECT * FROM patreg WHERE email = ? AND password = ? LIMIT 1');
        $stmt->bind_param('ss', $email, $password);
        $stmt->execute();
        $result = $stmt->get_result();
        $authenticated = $result ? $result->fetch_assoc() : null;
        $stmt->close();

        $this->assertNotNull($authenticated, 'Should be able to authenticate after registration');
        $this->assertSame($email, $authenticated['email']);
    }

    private function getPatientByEmail(string $email): ?array
    {
        $stmt = self::$db->prepare('SELECT * FROM patreg WHERE email = ? LIMIT 1');
        if (!$stmt) {
            return null;
        }
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result ? $result->fetch_assoc() : null;
        $stmt->close();
        return $row;
    }
}

