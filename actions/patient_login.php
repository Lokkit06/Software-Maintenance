<?php
/**
 * Step 1 toward SOLID for patient login:
 * - Single Responsibility: this file only authenticates patients.
 * - Dependency Inversion/Encapsulation: prepared statements instead of raw SQL interpolation.
 * - Clear session handling isolated in a helper.
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../config/db_connect.php';
require_once __DIR__ . '/../config/logger.php';

/**
 * Authenticate a patient by email/password.
 * Returns the patient row (assoc array) or null on failure.
 */
function authenticate_patient(mysqli $con, string $email, string $password): ?array
{
    try {
        $stmt = $con->prepare('SELECT * FROM patreg WHERE email = ? AND password = ? LIMIT 1');
        if (!$stmt) {
            throw new Exception('Failed to prepare patient lookup statement');
        }

        $stmt->bind_param('ss', $email, $password);
        $stmt->execute();
        $result  = $stmt->get_result();
        $patient = $result ? $result->fetch_assoc() : null;
        $stmt->close();

        return $patient ?: null;
    } catch (Throwable $e) {
        app_log('Patient authentication error', [
            'action' => 'authenticate_error',
            'user_type' => 'patient',
            'email' => $email,
            'error' => $e->getMessage()
        ]);
        return null;
    }
}

/**
 * Persist authenticated patient data to session.
 */
function start_patient_session(array $patient): void
{
    $_SESSION['pid']      = $patient['pid'];
    $_SESSION['username'] = $patient['fname'] . ' ' . $patient['lname'];
    $_SESSION['fname']    = $patient['fname'];
    $_SESSION['lname']    = $patient['lname'];
    $_SESSION['gender']   = $patient['gender'];
    $_SESSION['contact']  = $patient['contact'];
    $_SESSION['email']    = $patient['email'];
}

if (isset($_POST['patsub'])) {
    $email    = $_POST['email'] ?? '';
    $password = $_POST['password2'] ?? '';

    try {
        $patient = authenticate_patient($con, $email, $password);

        if ($patient) {
            start_patient_session($patient);
            $patientId = $patient['pid'] ?? null;
            $patientName = ($patient['fname'] ?? '') . ' ' . ($patient['lname'] ?? '');
            app_log("Patient logged in successfully (ID: {$patientId}, Email: {$email})", [
                'action' => 'login_success',
                'user_type' => 'patient',
                'email' => $email,
                'patient_id' => $patientId,
                'name' => $patientName
            ]);
            header('Location: ../views/patient/dashboard.php');
            exit;
        }

        echo "<script>alert('Invalid Username or Password. Try Again!');
              window.location.href = '../views/public/login.php';</script>";
        app_log('Patient login failed - wrong password', [
            'action' => 'login_failed',
            'user_type' => 'patient',
            'email' => $email,
            'reason' => 'invalid_credentials'
        ]);
        exit;
    } catch (Throwable $e) {
        app_log('Patient login error - system failure', [
            'action' => 'login_error',
            'user_type' => 'patient',
            'email' => $email,
            'error' => $e->getMessage()
        ]);
        echo "<script>alert('An unexpected error occurred. Please try again.');
              window.location.href = '../views/public/login.php';</script>";
        exit;
    }
}

// Legacy, unrelated behaviors (payment updates, doctor creation, HTML rendering)
// were removed from this action to respect Single Responsibility. If still needed,
// move them into dedicated actions/services.
 
?>