<?php
/**
 * Patient books an appointment (single responsibility action).
 */
session_start();
require_once __DIR__ . '/../config/db_connect.php';

function respond_and_exit(string $message, string $redirect): void {
    echo "<script>alert('$message'); window.location.href = '$redirect';</script>";
    exit;
}

if (!isset($_SESSION['pid'])) {
    respond_and_exit('Not authorized', '../views/public/login.php');
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['app-submit'])) {
    respond_and_exit('Invalid request', '../views/patient/dashboard.php');
}

$pid     = $_SESSION['pid'];
$fname   = $_SESSION['fname'] ?? '';
$lname   = $_SESSION['lname'] ?? '';
$gender  = $_SESSION['gender'] ?? '';
$email   = $_SESSION['email'] ?? '';
$contact = $_SESSION['contact'] ?? '';

$doctor   = $_POST['doctor'] ?? '';
$docFees  = $_POST['docFees'] ?? '';
$appdate  = $_POST['appdate'] ?? '';
$apptime  = $_POST['apptime'] ?? '';

date_default_timezone_set('Asia/Kolkata');
$cur_date = date('Y-m-d');
$cur_time = date('H:i:s');
$appdate_ts = strtotime($appdate);
$apptime_ts = strtotime($apptime);

// Validate future date/time
if (!$appdate_ts || !$apptime_ts || date('Y-m-d', $appdate_ts) < $cur_date ||
    (date('Y-m-d', $appdate_ts) === $cur_date && date('H:i:s', $apptime_ts) <= $cur_time)) {
    respond_and_exit('Select a time or date in the future!', '../views/patient/dashboard.php#list-home');
}

// Check doctor availability
try {
    $check = $con->prepare('SELECT 1 FROM appointmenttb WHERE doctor = ? AND appdate = ? AND apptime = ? LIMIT 1');
    if (!$check) {
        throw new Exception('Failed to prepare availability check');
    }
    $check->bind_param('sss', $doctor, $appdate, $apptime);
    $check->execute();
    $checkResult = $check->get_result();
    $busy = $checkResult && $checkResult->num_rows > 0;
    $check->close();

    if ($busy) {
        respond_and_exit('Doctor not available at this time/date. Please choose a different slot.', '../views/patient/dashboard.php#list-home');
    }

    // Insert appointment
    $stmt = $con->prepare('INSERT INTO appointmenttb (pid,fname,lname,gender,email,contact,doctor,docFees,appdate,apptime,userStatus,doctorStatus)
                           VALUES (?,?,?,?,?,?,?,?,?,?,1,1)');
    if (!$stmt) {
        throw new Exception('Failed to prepare booking');
    }
    $stmt->bind_param(
        'isssssssss',
        $pid,
        $fname,
        $lname,
        $gender,
        $email,
        $contact,
        $doctor,
        $docFees,
        $appdate,
        $apptime
    );
    $ok = $stmt->execute();
    $stmt->close();

    if ($ok) {
        respond_and_exit('Your appointment successfully booked', '../views/patient/dashboard.php#list-home');
    }

    respond_and_exit('Unable to process your request. Please try again!', '../views/patient/dashboard.php#list-home');
} catch (Throwable $e) {
    error_log('book_appointment failed: ' . $e->getMessage());
    respond_and_exit('An unexpected error occurred. Please try again.', '../views/patient/dashboard.php#list-home');
}

?>