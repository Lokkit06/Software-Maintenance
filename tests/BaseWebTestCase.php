<?php
declare(strict_types=1);

use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use PHPUnit\Framework\TestCase;

/**
 * Base utilities shared by HTTP-facing tests.
 *
 * - Manages a shared Guzzle client pointed at the local app.
 * - Manages a shared mysqli connection for fixtures/cleanup.
 */
abstract class BaseWebTestCase extends TestCase
{
    /** @var Client */
    protected static $client;

    /** @var mysqli */
    protected static $db;

    /** @var string */
    protected static $baseUrl = 'http://localhost/Software-Maintenance/';

    public static function setUpBeforeClass(): void
    {
        self::$client = new Client([
            'base_uri' => self::$baseUrl,
            'http_errors' => false,
            'timeout' => 5,
        ]);

        self::$db = mysqli_connect('localhost', 'root', '', 'myhmsdb');
        if (!self::$db) {
            self::markTestSkipped('Cannot connect to database myhmsdb: ' . mysqli_connect_error());
        }
    }

    protected function get(string $path, ?int &$status = null, ?CookieJar $jar = null): string
    {
        try {
            $options = [];
            if ($jar) {
                $options['cookies'] = $jar;
            }
            $response = self::$client->get($path, $options);
        } catch (ConnectException $e) {
            $this->markTestSkipped('Server not reachable at ' . self::$baseUrl . ': ' . $e->getMessage());
        } catch (RequestException $e) {
            $this->fail('HTTP request failed: ' . $e->getMessage());
        }
        $status = $response->getStatusCode();
        return (string) $response->getBody();
    }

    protected function post(string $path, array $options)
    {
        try {
            return self::$client->post($path, $options);
        } catch (ConnectException $e) {
            $this->markTestSkipped('Server not reachable at ' . self::$baseUrl . ': ' . $e->getMessage());
        } catch (RequestException $e) {
            $this->fail('HTTP request failed: ' . $e->getMessage());
        }
    }

    /**
     * Reusable login helpers for HTTP tests.
     */
    protected function loginPatient(array $patient): CookieJar
    {
        $jar = new CookieJar();
        $login = $this->post('actions/patient_login.php', [
            'form_params' => [
                'email'     => $patient['email'],
                'password2' => $patient['password'],
                'patsub'    => 'Login',
            ],
            'cookies' => $jar,
            'allow_redirects' => false,
        ]);
        $this->assertSame(302, $login->getStatusCode(), 'Patient login should redirect');
        return $jar;
    }

    protected function loginDoctor(array $doctor): CookieJar
    {
        $jar = new CookieJar();
        $login = $this->post('actions/doctor_login.php', [
            'form_params' => [
                'username3' => $doctor['username'],
                'password3' => $doctor['password'],
                'docsub1'   => 'Login',
            ],
            'cookies' => $jar,
            'allow_redirects' => false,
        ]);
        $this->assertSame(302, $login->getStatusCode(), 'Doctor login should redirect');
        return $jar;
    }

    protected function loginAdmin(array $admin): CookieJar
    {
        $jar = new CookieJar();
        $login = $this->post('actions/admin_login.php', [
            'form_params' => [
                'username1' => $admin['username'],
                'password2' => $admin['password'],
                'adsub'     => 'Login',
            ],
            'cookies' => $jar,
            'allow_redirects' => false,
        ]);
        $this->assertSame(302, $login->getStatusCode(), 'Admin login should redirect');
        return $jar;
    }
}


