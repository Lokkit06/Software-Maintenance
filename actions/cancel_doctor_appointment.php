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

$stmt = $con->prepare("UPDATE appointmenttb SET doctorStatus = '0' WHERE ID = ? AND doctor = ?");
if (!$stmt) {
    respond_and_exit('Failed to prepare update');
}

$stmt->bind_param('is', $id, $doctor);
$ok = $stmt->execute();
$stmt->close();

if ($ok && $con->affected_rows > 0) {
    respond_and_exit('Appointment cancelled');
}

respond_and_exit('No matching appointment');

