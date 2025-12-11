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

$stmt = $con->prepare("UPDATE appointmenttb SET userStatus = '0' WHERE ID = ? AND pid = ?");
if (!$stmt) {
    respond_and_exit('Failed to prepare update');
}

$stmt->bind_param('ii', $id, $pid);
$ok = $stmt->execute();
$stmt->close();

if ($ok && $con->affected_rows > 0) {
    respond_and_exit('Your appointment successfully cancelled');
}

respond_and_exit('No matching appointment');

