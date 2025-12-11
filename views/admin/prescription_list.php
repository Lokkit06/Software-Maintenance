<?php
require_once __DIR__ . '/../../config/db_connect.php';
require_once __DIR__ . '/../../includes/prescriptions.php';

$prescriptions = fetch_all_prescriptions($con);
?>

<div class="tab-pane fade" id="list-pres" role="tabpanel" aria-labelledby="list-pres-list">
  <div class="col-md-8">
    <table class="table table-hover">
      <thead>
        <tr>
        <th scope="col">Doctor</th>
          <th scope="col">Patient ID</th>
          <th scope="col">Appointment ID</th>
          <th scope="col">First Name</th>
          <th scope="col">Last Name</th>
          <th scope="col">Appointment Date</th>
          <th scope="col">Appointment Time</th>
          <th scope="col">Disease</th>
          <th scope="col">Allergy</th>
          <th scope="col">Prescription</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($prescriptions as $row): ?>
            <tr>
              <td><?php echo $row['doctor']; ?></td>
              <td><?php echo $row['pid']; ?></td>
              <td><?php echo $row['ID']; ?></td>
              <td><?php echo $row['fname']; ?></td>
              <td><?php echo $row['lname']; ?></td>
              <td><?php echo $row['appdate']; ?></td>
              <td><?php echo $row['apptime']; ?></td>
              <td><?php echo $row['disease']; ?></td>
              <td><?php echo $row['allergy']; ?></td>
              <td><?php echo $row['prescription']; ?></td>
            </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <br>
  </div>
</div>