<?php
declare(strict_types=1);

require_once __DIR__ . '/TestDataFactory.php';
require_once __DIR__ . '/../actions/admin_login.php';

use PHPUnit\Framework\TestCase;

/**
 * Unit/integration tests for admin authentication logic (TDD-friendly).
 * Calls the pure function authenticate_admin() directly, no HTTP layer.
 */
final class AdminAuthTest extends TestCase
{
    /** @var mysqli */
    private static $db;

    /** @var array<string,mixed> */
    private $admin;

    public static function setUpBeforeClass(): void
    {
        self::$db = mysqli_connect('localhost', 'root', '', 'myhmsdb');
        if (!self::$db) {
            self::markTestSkipped('Cannot connect to database myhmsdb: ' . mysqli_connect_error());
        }
    }

    protected function setUp(): void
    {
        $this->admin = TestDataFactory::createAdmin(self::$db);
    }

    protected function tearDown(): void
    {
        TestDataFactory::deleteAdmin(self::$db, $this->admin['username']);
    }

    public function testAuthenticateAdminReturnsRowOnValidCredentials(): void
    {
        $row = authenticate_admin(self::$db, $this->admin['username'], $this->admin['password']);
        $this->assertNotNull($row);
        $this->assertSame($this->admin['username'], $row['username']);
    }

    public function testAuthenticateAdminReturnsNullOnWrongPassword(): void
    {
        $row = authenticate_admin(self::$db, $this->admin['username'], 'wrong-pass');
        $this->assertNull($row);
    }

    public function testAuthenticateAdminReturnsNullOnWrongUsername(): void
    {
        $row = authenticate_admin(self::$db, 'nonexistent_admin', $this->admin['password']);
        $this->assertNull($row);
    }
}

