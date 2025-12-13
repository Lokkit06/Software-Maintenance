<!DOCTYPE html>
<?php
require_once '../../config/db_connect.php';
require_once '../../includes/appointments.php';
?>
<html>
<head>
	<title>Patient Details</title>
  <link rel="shortcut icon" type="image/x-icon" href="../../assets/images/images/favicon.png" />
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" integrity="sha384-/Y6pD6FV/Vv2HJnA6t+vslU6fwYXjCFtcEpHbNJ0lyAFsXTsjBbfaDjzALeQsN6M" crossorigin="anonymous">

</head>
<body>
<?php
if(isset($_POST['app_search_submit']))
{
    try {
    	$contact=$_POST['app_contact'] ?? '';
    	$rows = fetch_appointments_by_contact($con, $contact);

      if(empty($rows)){
        echo "<script> alert('No entries found! Please enter valid details'); 
              window.location.href = '../admin/dashboard.php#list-doc';</script>";
      }
      else {
        echo "<div class='container-fluid' style='margin-top:50px;'>
        <div class='card'>
        <div class='card-body' style='background-color:#342ac1;color:#ffffff;'>
      <table class='table table-hover'>
        <thead>
          <tr>
            <th scope='col'>First Name</th>
            <th scope='col'>Last Name</th>
            <th scope='col'>Email</th>
            <th scope='col'>Contact</th>
            <th scope='col'>Doctor Name</th>
            <th scope='col'>Consultancy Fees</th>
            <th scope='col'>Appointment Date</th>
            <th scope='col'>Appointment Time</th>
            <th scope='col'>Appointment Status</th>
          </tr>
        </thead>
        <tbody>";
      
        foreach ($rows as $row) {
              $fname = $row['fname'];
              $lname = $row['lname'];
              $email = $row['email'];
              $contact = $row['contact'];
              $doctor = $row['doctor'];
              $docFees= $row['docFees'];
              $appdate= $row['appdate'];
              $apptime = $row['apptime'];
              if(($row['userStatus']==1) && ($row['doctorStatus']==1))  
                        {
                          $appstatus = "Active";
                        }
                        if(($row['userStatus']==0) && ($row['doctorStatus']==1))  
                        {
                          $appstatus = "Cancelled by You";
                        }

                        if(($row['userStatus']==1) && ($row['doctorStatus']==0))  
                        {
                          $appstatus = "Cancelled by Doctor";
                        }
              echo "<tr>
                <td>$fname</td>
                <td>$lname</td>
                <td>$email</td>
                <td>$contact</td>
                <td>$doctor</td>
                <td>$docFees</td>
                <td>$appdate</td>
                <td>$apptime</td>
                <td>$appstatus</td>
              </tr>";
        }
        echo "</tbody></table><center><a href='../admin/dashboard.php' class='btn btn-light'>Back to your Dashboard</a></div></center></div></div></div>";
      }
      }
      catch (Throwable $e) {
        error_log('patient appointment_search failed: ' . $e->getMessage());
        echo "<script>alert('An unexpected error occurred. Please try again.');
              window.location.href = '../admin/dashboard.php#list-doc';</script>";
      }
      }
    	
?>
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js" integrity="sha384-h0AbiXch4ZDo7tp9hKZ4TsHbi047NrKGLO3SEJAg45jXxnGIfYzk4Si90RDIqNm1" crossorigin="anonymous"></script> 
</body>
</html>