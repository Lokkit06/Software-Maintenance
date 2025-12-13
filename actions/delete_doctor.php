<?php
/**
 * Dedicated action for deleting a doctor (SOLID: single responsibility).
 */
require_once __DIR__ . '/../config/db_connect.php';

function respond_and_exit(string $message, string $redirect): void {
    echo "<script>alert('$message'); window.location.href = '$redirect';</script>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['docsub1'])) {
    $email = $_POST['demail'] ?? '';
    if ($email === '') {
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
            respond_and_exit('Doctor deleted', '../views/admin/dashboard.php#list-settings1');
        }

        respond_and_exit('No doctor found for that email', '../views/admin/dashboard.php#list-settings1');
    } catch (Throwable $e) {
        error_log('delete_doctor failed: ' . $e->getMessage());
        respond_and_exit('An unexpected error occurred', '../views/admin/dashboard.php#list-settings1');
    }
}

respond_and_exit('Invalid request', '../views/admin/dashboard.php');

?>