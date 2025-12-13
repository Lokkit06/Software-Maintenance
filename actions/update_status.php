<?php
// session_start();
require_once __DIR__ . '/../config/db_connect.php';
require_once __DIR__ . '/../config/logger.php';

// if(isset($_POST['submit'])){
//  $username=$_POST['username'];
//  $password=$_POST['password'];
//  $query="select * from logintb where username='$username' and password='$password';";
//  $result=mysqli_query($con,$query);
//  if(mysqli_num_rows($result)==1)
//  {
//   $_SESSION['username']=$username;
//   $_SESSION['pid']=
//   header("Location:admin-panel.php");
//  }
//  else
//   header("Location:error.php");
// }
if(isset($_POST['update_data']))
{
 $contact=$_POST['contact'];
 $status=$_POST['status'];
  try {
    $query="update appointmenttb set payment='$status' where contact='$contact';";
    $result=mysqli_query($con,$query);
    if($result) {
      app_log('payment_status_updated', ['contact' => $contact, 'status' => $status]);
      header("Location:updated.php");
      exit;
    }
    throw new Exception('Failed to update payment status');
  } catch (Throwable $e) {
    app_log('update_status_failed', ['error' => $e->getMessage(), 'contact' => $contact ?? null]);
    echo "<script>alert('An unexpected error occurred. Please try again.');
          window.location.href = '../views/public/error_login.php';</script>";
    exit;
  }
}

// function display_docs()
// {
//  global $con;
//  $query="select * from doctb";
//  $result=mysqli_query($con,$query);
//  while($row=mysqli_fetch_array($result))
//  {
//   $username=$row['username'];
//   $price=$row['docFees'];
//   echo '<option value="' .$username. '" data-value="'.$price.'">'.$username.'</option>';
//  }
// }


function display_specs() {
  global $con;
  $query="select distinct(spec) from doctb";
  $result=mysqli_query($con,$query);
  while($row=mysqli_fetch_array($result))
  {
    $spec=$row['spec'];
    echo '<option data-value="'.$spec.'">'.$spec.'</option>';
  }
}

function display_docs()
{
 global $con;
 $query = "select * from doctb";
 $result = mysqli_query($con,$query);
 while( $row = mysqli_fetch_array($result) )
 {
  $username = $row['username'];
  $price = $row['docFees'];
  $spec = $row['spec'];
  echo '<option value="' .$username. '" data-value="'.$price.'" data-spec="'.$spec.'">'.$username.'</option>';
 }
}

// function display_specs() {
//   global $con;
//   $query = "select distinct(spec) from doctb";
//   $result = mysqli_query($con,$query);
//   while($row = mysqli_fetch_array($result))
//   {
//     $spec = $row['spec'];
//     $username = $row['username'];
//     echo '<option value = "' .$spec. '">'.$spec.'</option>';
//   }
// }


if(isset($_POST['doc_sub']))
{
 $username=$_POST['username'];
  try {
    $query="insert into doctb(username)values('$username')";
    $result=mysqli_query($con,$query);
    if($result) {
      app_log('doctor_added_from_update_status', ['username' => $username]);
      header("Location:adddoc.php");
      exit;
    }
    throw new Exception('Failed to add doctor');
  } catch (Throwable $e) {
    app_log('update_status_doc_add_failed', ['error' => $e->getMessage(), 'username' => $username ?? null]);
    echo "<script>alert('An unexpected error occurred. Please try again.');
          window.location.href = '../views/public/error_login.php';</script>";
    exit;
  }
}

?>