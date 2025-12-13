<?php
/**
 * Patient cancels an appointment (sets userStatus = 0).
 */
session_start();
require_once __DIR__ . '/../config/db_connect.php';

function respond_and_exit(string $message): void {
    echo "<script>alert('$message'); window.location.href = '../views/patient/dashboard.php#app-hist';</script>";
    exit;
}

if (!isset($_SESSION['pid'])) {
    respond_and_exit('Not authorized');
}

$pid = $_SESSION['pid'];
$id = $_GET['ID'] ?? null;

if (!$id) {
    respond_and_exit('Missing appointment ID');
}

try {
    $stmt = $con->prepare("UPDATE appointmenttb SET userStatus = '0' WHERE ID = ? AND pid = ?");
    if (!$stmt) {
        throw new Exception('Failed to prepare update');
    }

    $stmt->bind_param('ii', $id, $pid);
    $ok = $stmt->execute();
    $stmt->close();

    if ($ok && $con->affected_rows > 0) {
        respond_and_exit('Your appointment successfully cancelled');
    }

    // If the row exists but was already cancelled, treat as success for idempotency
    $check = $con->prepare("SELECT 1 FROM appointmenttb WHERE ID = ? AND pid = ? LIMIT 1");
    if ($check) {
        $check->bind_param('ii', $id, $pid);
        $check->execute();
        $check->store_result();
        $found = $check->num_rows > 0;
        $check->close();
        if ($found) {
            respond_and_exit('Your appointment successfully cancelled');
        }
    }

    respond_and_exit('No matching appointment');
} catch (Throwable $e) {
    error_log('cancel_patient_appointment failed: ' . $e->getMessage());
    respond_and_exit('An unexpected error occurred');
}

?>