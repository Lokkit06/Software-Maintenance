<?php
require_once __DIR__ . '/../../config/db_connect.php';
require_once __DIR__ . '/../../includes/doctors.php';
$doctors = fetch_all_doctors($con);
?>

<div class="tab-pane fade" id="list-doc" role="tabpanel" aria-labelledby="list-home-list">
  <div class="col-md-8">
    <form class="form-group" action="doctor_search.php" method="post">
      <div class="row">
      <div class="col-md-10"><input type="text" name="doctor_contact" placeholder="Enter Email" class = "form-control"></div>
      <div class="col-md-2"><input type="submit" name="doctor_search_submit" class="btn btn-primary" value="Search"></div></div>
    </form>
  </div>
  <table class="table table-hover">
    <thead>
      <tr>
        <th scope="col">Doctor Name</th>
        <th scope="col">Specialization</th>
        <th scope="col">Email</th>
        <th scope="col">Password</th>
        <th scope="col">Fees</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($doctors as $row): ?>
          <tr>
            <td><?php echo $row['username']; ?></td>
            <td><?php echo $row['spec']; ?></td>
            <td><?php echo $row['email']; ?></td>
            <td><?php echo $row['password']; ?></td>
            <td><?php echo $row['docFees']; ?></td>
          </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <br>
</div>