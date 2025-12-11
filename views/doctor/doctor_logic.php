<?php
include('../../actions/doctor_login.php');
require_once '../../config/db_connect.php';

// Guard against missing session to avoid undefined index notices
$doctor = isset($_SESSION['dname']) ? $_SESSION['dname'] : null;

if (!$doctor) {
  header('Location: ../public/index.php');
  exit();
}
?>