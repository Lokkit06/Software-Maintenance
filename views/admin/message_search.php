<!DOCTYPE html>
<?php
require_once '../../config/db_connect.php';
?>
<html>
<head>
	<title>User Messages</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" integrity="sha384-/Y6pD6FV/Vv2HJnA6t+vslU6fwYXjCFtcEpHbNJ0lyAFsXTsjBbfaDjzALeQsN6M" crossorigin="anonymous">
</head>
<body>
<?php
if(isset($_POST['mes_search_submit']))
{
    try {
    	$contact=$_POST['mes_contact'];
    	$query = "select * from contact where contact= '$contact'";
      $result = mysqli_query($con,$query);
      if(!$result){
        throw new Exception('Message search query failed');
      }
      $row=mysqli_fetch_array($result);
      if(!$row || ($row['name']=="" && $row['email']=="" && $row['contact']=="" && $row['message']=="")){
        echo "<script> alert('No entries found! Please enter valid details'); 
              window.location.href = 'dashboard.php#list-doc';</script>";
      } 
      else {
        echo "<div class='container-fluid' style='margin-top:50px;'>
        <div class='card'>
        <div class='card-body' style='background-color:#342ac1;color:#ffffff;'>
      <table class='table table-hover'>
        <thead>
          <tr>
            <th scope='col'>User Name</th>
            <th scope='col'>Email</th>
            <th scope='col'>Contact</th>
            <th scope='col'>Message</th>
          </tr>
        </thead>
        <tbody>";
      
        
              $name = $row['name'];
              $email = $row['email'];
              $contact = $row['contact'];
              $message = $row['message'];
              echo "<tr>
                <td>$name</td>
                <td>$email</td>
                <td>$contact</td>
                <td>$message</td>
              </tr>";
        
        echo "</tbody></table><center><a href='dashboard.php' class='btn btn-light'>Back to your Dashboard</a></div></center></div></div></div>";
      }
      }
      catch (Throwable $e) {
        error_log('message_search failed: ' . $e->getMessage());
        echo "<script>alert('An unexpected error occurred. Please try again.');
              window.location.href = 'dashboard.php#list-doc';</script>";
      }
      }
    	
?>
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js" integrity="sha384-h0AbiXch4ZDo7tp9hKZ4TsHbi047NrKGLO3SEJAg45jXxnGIfYzk4Si90RDIqNm1" crossorigin="anonymous"></script> 
</body>
</html>