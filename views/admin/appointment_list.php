<?php
require_once __DIR__ . '/../../config/db_connect.php';
require_once __DIR__ . '/../../includes/appointments.php';

// Fetch once so the view only renders.
$appointments = fetch_all_appointments($con);
?>

<div class="tab-pane fade" id="list-app" role="tabpanel" aria-labelledby="list-pat-list">
  <div class="col-md-8">
    <form class="form-group" action="../patient/appointment_search.php" method="post">
      <div class="row">
      <div class="col-md-10"><input type="text" name="app_contact" placeholder="Enter Contact" class = "form-control"></div>
      <div class="col-md-2"><input type="submit" name="app_search_submit" class="btn btn-primary" value="Search"></div></div>
    </form>
  </div>
    
  <table class="table table-hover">
    <thead>
      <tr>
      <th scope="col">Appointment ID</th>
      <th scope="col">Patient ID</th>
        <th scope="col">First Name</th>
        <th scope="col">Last Name</th>
        <th scope="col">Gender</th>
        <th scope="col">Email</th>
        <th scope="col">Contact</th>
        <th scope="col">Doctor Name</th>
        <th scope="col">Consultancy Fees</th>
        <th scope="col">Appointment Date</th>
        <th scope="col">Appointment Time</th>
        <th scope="col">Appointment Status</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($appointments as $row): ?>
          <tr>
            <td><?php echo $row['ID']; ?></td>
            <td><?php echo $row['pid']; ?></td>
            <td><?php echo $row['fname']; ?></td>
            <td><?php echo $row['lname']; ?></td>
            <td><?php echo $row['gender']; ?></td>
            <td><?php echo $row['email']; ?></td>
            <td><?php echo $row['contact']; ?></td>
            <td><?php echo $row['doctor']; ?></td>
            <td><?php echo $row['docFees']; ?></td>
            <td><?php echo $row['appdate']; ?></td>
            <td><?php echo $row['apptime']; ?></td>
            <td><?php echo format_app_status($row); ?></td>
          </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <br>
</div>