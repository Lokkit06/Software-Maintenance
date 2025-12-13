<?php
declare(strict_types=1);

require_once __DIR__ . '/BaseWebTestCase.php';
require_once __DIR__ . '/TestDataFactory.php';

use GuzzleHttp\Cookie\CookieJar;

/**
 * Minimal HTTP smoke tests: public pages and a basic patient login redirect.
 */
final class HttpSmokeTest extends BaseWebTestCase
{
    /** @var array<string,mixed> */
    private $patient;

    protected function setUp(): void
    {
        parent::setUp();
        $this->patient = TestDataFactory::createPatient(self::$db);
    }

    protected function tearDown(): void
    {
        TestDataFactory::deletePatient(self::$db, (int) $this->patient['pid']);
        parent::tearDown();
    }

    public function publicPageProvider(): array
    {
        return [
            ['views/public/index.php', 'GLOBAL HOSPITALS'],
            ['views/public/services.php', 'Make an appoinment'],
            ['views/public/contact.php', 'Drop Us a Message'],
            ['views/public/login.php', 'Patient Login'],
        ];
    }

    /**
     * @dataProvider publicPageProvider
     */
    public function testPublicPagesReturn200AndContainText(string $path, string $expected): void
    {
        $body = $this->get($path, $status);
        $this->assertSame(200, $status, "Unexpected status {$status} for {$path}");
        $this->assertStringContainsString($expected, $body, "Missing expected text on {$path}");
    }

    public function testPatientLoginRedirectsOnSuccess(): void
    {
        $jar = $this->loginPatient($this->patient);
        $this->assertNotEmpty($jar->toArray(), 'Expected session cookie to be set');
    }
}


