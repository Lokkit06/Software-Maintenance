<?php
/**
 * Dedicated action for deleting a doctor (SOLID: single responsibility).
 */
require_once __DIR__ . '/../config/db_connect.php';
require_once __DIR__ . '/../config/logger.php';

app_log_request('delete_doctor_hit');

function respond_and_exit(string $message, string $redirect): void {
    echo "<script>alert('$message'); window.location.href = '$redirect';</script>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['docsub1'])) {
    $email = $_POST['demail'] ?? '';
    if ($email === '') {
        app_log('delete_doctor_missing_email', []);
        respond_and_exit('Email is required', '../views/admin/dashboard.php#list-settings1');
    }

    try {
        $stmt = $con->prepare('DELETE FROM doctb WHERE email = ?');
        if (!$stmt) {
            throw new Exception('Failed to prepare delete');
        }

        $stmt->bind_param('s', $email);
        $ok = $stmt->execute();
        $stmt->close();

        if ($ok && $con->affected_rows > 0) {
            app_log('doctor_deleted', ['email' => $email]);
            respond_and_exit('Doctor deleted', '../views/admin/dashboard.php#list-settings1');
        }

        app_log('delete_doctor_not_found', ['email' => $email]);
        respond_and_exit('No doctor found for that email', '../views/admin/dashboard.php#list-settings1');
    } catch (Throwable $e) {
        app_log('delete_doctor_failed', ['error' => $e->getMessage(), 'email' => $email]);
        respond_and_exit('An unexpected error occurred', '../views/admin/dashboard.php#list-settings1');
    }
}

app_log('invalid_delete_doctor_request', []);
respond_and_exit('Invalid request', '../views/admin/dashboard.php');

?>