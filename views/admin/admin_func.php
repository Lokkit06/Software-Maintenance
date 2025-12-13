<?php
session_start();
require_once '../../config/db_connect.php';
include('../../actions/update_status.php');

// Handle Add Doctor
if(isset($_POST['docsub']))
{
  $doctor=$_POST['doctor'];
  $dpassword=$_POST['dpassword'];
  $demail=$_POST['demail'];
  $spec=$_POST['special'];
  $docFees=$_POST['docFees'];
  try {
    $query="insert into doctb(username,password,email,spec,docFees)values('$doctor','$dpassword','$demail','$spec','$docFees')";
    $result=mysqli_query($con,$query);
    if($result)
      {
        echo "<script>alert('Doctor added successfully!');</script>";
    } else {
        throw new Exception('Failed to add doctor');
    }
  } catch (Throwable $e) {
    error_log('admin_func add doctor failed: ' . $e->getMessage());
    echo "<script>alert('An unexpected error occurred while adding doctor.');</script>";
  }
}

// Handle Delete Doctor
if(isset($_POST['docsub1']))
{
  $demail=$_POST['demail'];
  try {
    $query="delete from doctb where email='$demail';";
    $result=mysqli_query($con,$query);
    if($result)
      {
        echo "<script>alert('Doctor removed successfully!');</script>";
    }
    else{
      echo "<script>alert('Unable to delete!');</script>";
    }
  } catch (Throwable $e) {
    error_log('admin_func delete doctor failed: ' . $e->getMessage());
    echo "<script>alert('An unexpected error occurred while deleting doctor.');</script>";
  }
}
?>