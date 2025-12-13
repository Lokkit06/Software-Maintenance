<?php
/**
 * Cancel an appointment by doctor (sets doctorStatus = 0).
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../config/db_connect.php';
require_once __DIR__ . '/../config/logger.php';

app_log_request('cancel_doctor_hit');
function respond_and_exit(string $message): void {
    echo "<script>alert('$message'); window.location.href = '../views/doctor/dashboard.php#list-app';</script>";
    exit;
}

if (!isset($_SESSION['dname'])) {
    app_log('cancel_doctor_unauthorized', []);
    respond_and_exit('Not authorized');
}

$doctor = $_SESSION['dname'];
$id = $_GET['ID'] ?? null;

if (!$id) {
    app_log('cancel_doctor_missing_id', ['doctor' => $doctor]);
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
        app_log('appointment_cancelled_by_doctor', ['doctor' => $doctor, 'appointment_id' => $id]);
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
            app_log('appointment_already_cancelled_by_doctor', ['doctor' => $doctor, 'appointment_id' => $id]);
            respond_and_exit('Appointment cancelled');
        }
    }

    app_log('cancel_doctor_no_match', ['doctor' => $doctor, 'appointment_id' => $id]);
    respond_and_exit('No matching appointment');
} catch (Throwable $e) {
    app_log('cancel_doctor_appointment_failed', ['error' => $e->getMessage(), 'doctor' => $doctor, 'appointment_id' => $id]);
    respond_and_exit('An unexpected error occurred');
}

?>