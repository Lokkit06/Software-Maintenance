<!DOCTYPE html>
<?php
require_once '../../config/db_connect.php';
?>
<html>
<head>
  <title>Patient Search Results</title>
  <link rel="shortcut icon" type="image/x-icon" href="../../assets/images/images/favicon.png" />
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" integrity="sha384-/Y6pD6FV/Vv2HJnA6t+vslU6fwYXjCFtcEpHbNJ0lyAFsXTsjBbfaDjzALeQsN6M" crossorigin="anonymous">
</head>
<body>
<?php
if(isset($_POST['patient_search_submit']))
{
  try {
    $contact = $_POST['patient_contact'];
    $query = "select * from patreg where contact= '$contact'";
    $result = mysqli_query($con, $query);
    if(!$result){
      throw new Exception('Patient search query failed');
    }
    
    // Logic to handle no results found
    if(mysqli_num_rows($result) == 0){
      echo "<script> 
        alert('No entries found! Please enter valid details'); 
        window.location.href = 'dashboard.php#list-pat';
      </script>";
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
              <th scope='col'>Password</th>
            </tr>
          </thead>
          <tbody>";

      while ($row = mysqli_fetch_array($result)){
        $fname = $row['fname'];
        $lname = $row['lname'];
        $email = $row['email'];
        $contact = $row['contact'];
        $password = $row['password'];
        echo "<tr>
          <td>$fname</td>
          <td>$lname</td>
          <td>$email</td>
          <td>$contact</td>
          <td>$password</td>
        </tr>";
      }
      
      echo "</tbody></table><center><a href='dashboard.php#list-pat' class='btn btn-light'>Back to Dashboard</a></div></center></div></div></div>";
    }
  } catch (Throwable $e) {
    error_log('patient_search failed: ' . $e->getMessage());
    echo "<script>alert('An unexpected error occurred. Please try again.');
          window.location.href = 'dashboard.php#list-pat';</script>";
  }
}
?>
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js" integrity="sha384-h0AbiXch4ZDo7tp9hKZ4TsHbi047NrKGLO3SEJAg45jXxnGIfYzk4Si90RDIqNm1" crossorigin="anonymous"></script> 
</body>
</html>