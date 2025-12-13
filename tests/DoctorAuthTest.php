<?php
declare(strict_types=1);

require_once __DIR__ . '/TestDataFactory.php';
require_once __DIR__ . '/../actions/doctor_login.php';

use PHPUnit\Framework\TestCase;

/**
 * Unit/integration tests for doctor authentication logic (TDD-friendly).
 * Calls the pure function authenticate_doctor() directly, no HTTP layer.
 */
final class DoctorAuthTest extends TestCase
{
    /** @var mysqli */
    private static $db;

    /** @var array<string,mixed> */
    private $doctor;

    public static function setUpBeforeClass(): void
    {
        self::$db = mysqli_connect('localhost', 'root', '', 'myhmsdb');
        if (!self::$db) {
            self::markTestSkipped('Cannot connect to database myhmsdb: ' . mysqli_connect_error());
        }
    }

    protected function setUp(): void
    {
        $this->doctor = TestDataFactory::createDoctor(self::$db);
    }

    protected function tearDown(): void
    {
        TestDataFactory::deleteDoctor(self::$db, $this->doctor['username']);
    }

    public function testAuthenticateDoctorReturnsRowOnValidCredentials(): void
    {
        $row = authenticate_doctor(self::$db, $this->doctor['username'], $this->doctor['password']);
        $this->assertNotNull($row);
        $this->assertSame($this->doctor['username'], $row['username']);
    }

    public function testAuthenticateDoctorReturnsNullOnWrongPassword(): void
    {
        $row = authenticate_doctor(self::$db, $this->doctor['username'], 'wrong-pass');
        $this->assertNull($row);
    }

    public function testAuthenticateDoctorReturnsNullOnWrongUsername(): void
    {
        $row = authenticate_doctor(self::$db, 'nonexistent_doctor', $this->doctor['password']);
        $this->assertNull($row);
    }
}

