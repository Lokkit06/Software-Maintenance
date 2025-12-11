<?php
/**
 * Dedicated action for creating doctors (SOLID: single responsibility).
 */
require_once __DIR__ . '/../config/db_connect.php';

function respond_and_exit(string $message, string $redirect): void {
    echo "<script>alert('$message'); window.location.href = '$redirect';</script>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['docsub'])) {
    $name       = $_POST['doctor']    ?? '';
    $spec       = $_POST['special']   ?? '';
    $email      = $_POST['demail']    ?? '';
    $password   = $_POST['dpassword'] ?? '';
    $cpassword  = $_POST['cdpassword'] ?? '';
    $fees       = $_POST['docFees']   ?? '';

    if ($password !== $cpassword) {
        respond_and_exit('Passwords do not match', '../views/admin/dashboard.php#list-settings');
    }

    $hash = password_hash($password, PASSWORD_BCRYPT);

    $stmt = $con->prepare('INSERT INTO doctb (username, spec, email, password, docFees) VALUES (?, ?, ?, ?, ?)');
    if (!$stmt) {
        respond_and_exit('Failed to prepare statement', '../views/admin/dashboard.php#list-settings');
    }
    $stmt->bind_param('sssss', $name, $spec, $email, $hash, $fees);
    $ok = $stmt->execute();
    $stmt->close();

    if ($ok) {
        respond_and_exit('Doctor added successfully', '../views/admin/dashboard.php#list-settings');
    }

    respond_and_exit('Failed to add doctor', '../views/admin/dashboard.php#list-settings');
}

respond_and_exit('Invalid request', '../views/admin/dashboard.php');

