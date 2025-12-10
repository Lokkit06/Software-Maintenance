<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$db   = 'myhmsdb';

$con = mysqli_connect($host, $user, $pass, $db);

if (!$con) {
    die('Database connection failed: ' . mysqli_connect_error());
}

