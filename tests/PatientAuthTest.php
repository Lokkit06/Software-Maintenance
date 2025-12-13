<?php
declare(strict_types=1);

require_once __DIR__ . '/TestDataFactory.php';
require_once __DIR__ . '/../actions/patient_login.php';

use PHPUnit\Framework\TestCase;

/**
 * Unit/integration tests for patient authentication logic (TDD-friendly).
 * Calls the pure function authenticate_patient() directly, no HTTP layer.
 */
final class PatientAuthTest extends TestCase
{
    /** @var mysqli */
    private static $db;

    /** @var array<string,mixed> */
    private $patient;

    public static function setUpBeforeClass(): void
    {
        self::$db = mysqli_connect('localhost', 'root', '', 'myhmsdb');
        if (!self::$db) {
            self::markTestSkipped('Cannot connect to database myhmsdb: ' . mysqli_connect_error());
        }
    }

    protected function setUp(): void
    {
        $this->patient = TestDataFactory::createPatient(self::$db);
    }

    protected function tearDown(): void
    {
        TestDataFactory::deletePatient(self::$db, (int) $this->patient['pid']);
    }

    public function testAuthenticatePatientReturnsRowOnValidCredentials(): void
    {
        $row = authenticate_patient(self::$db, $this->patient['email'], $this->patient['password']);
        $this->assertNotNull($row);
        $this->assertSame($this->patient['email'], $row['email']);
    }

    public function testAuthenticatePatientReturnsNullOnWrongPassword(): void
    {
        $row = authenticate_patient(self::$db, $this->patient['email'], 'wrong-pass');
        $this->assertNull($row);
    }
}


