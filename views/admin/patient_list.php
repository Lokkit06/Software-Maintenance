<?php
require_once __DIR__ . '/../../config/db_connect.php';
require_once __DIR__ . '/../../includes/patients.php';
$patients = fetch_all_patients($con);
?>

<!-- Changed ID to 'list-pat' so it's unique -->
<div class="tab-pane fade" id="list-pat" role="tabpanel" aria-labelledby="list-pat-list">
  <div class="col-md-8">
    <form class="form-group" action="patient_search.php" method="post">
      <div class="row">
      <div class="col-md-10"><input type="text" name="patient_contact" placeholder="Enter Contact" class = "form-control"></div>
      <div class="col-md-2"><input type="submit" name="patient_search_submit" class="btn btn-primary" value="Search"></div></div>
    </form>
  </div>
  <table class="table table-hover">
    <thead>
      <tr>
        <th scope="col">First Name</th>
        <th scope="col">Last Name</th>
        <th scope="col">Email</th>
        <th scope="col">Contact</th>
        <th scope="col">Gender</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($patients as $row): ?>
          <tr>
            <td><?php echo $row['fname']; ?></td>
            <td><?php echo $row['lname']; ?></td>
            <td><?php echo $row['email']; ?></td>
            <td><?php echo $row['contact']; ?></td>
            <td><?php echo $row['gender']; ?></td>
          </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <br>
</div>