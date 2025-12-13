<?php
/**
 * Cancel an appointment by doctor (sets doctorStatus = 0).
 */
session_start();
require_once __DIR__ . '/../config/db_connect.php';

function respond_and_exit(string $message): void {
    echo "<script>alert('$message'); window.location.href = '../views/doctor/dashboard.php#list-app';</script>";
    exit;
}

if (!isset($_SESSION['dname'])) {
    respond_and_exit('Not authorized');
}

$doctor = $_SESSION['dname'];
$id = $_GET['ID'] ?? null;

if (!$id) {
    respond_and_exit('Missing appointment ID');
}

try {
    $stmt = $con->prepare("UPDATE appointmenttb SET doctorStatus = '0' WHERE ID = ? AND doctor = ?");
    if (!$stmt) {
        throw new Exception('Failed to prepare update');
    }

    $stmt->bind_param('is', $id, $doctor);
    $ok = $stmt->execute();
    $stmt->close();

    if ($ok && $con->affected_rows > 0) {
        respond_and_exit('Appointment cancelled');
    }

    // If the row exists but was already cancelled, treat as success for idempotency
    $check = $con->prepare("SELECT 1 FROM appointmenttb WHERE ID = ? AND doctor = ? LIMIT 1");
    if ($check) {
        $check->bind_param('is', $id, $doctor);
        $check->execute();
        $check->store_result();
        $found = $check->num_rows > 0;
        $check->close();
        if ($found) {
            respond_and_exit('Appointment cancelled');
        }
    }

    respond_and_exit('No matching appointment');
} catch (Throwable $e) {
    error_log('cancel_doctor_appointment failed: ' . $e->getMessage());
    respond_and_exit('An unexpected error occurred');
}

?>