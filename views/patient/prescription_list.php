<?php
require_once __DIR__ . '/../../config/db_connect.php';
require_once __DIR__ . '/../../includes/prescriptions.php';
$pid = $_SESSION['pid'] ?? 0;
$prescriptions = $pid ? fetch_prescriptions_by_patient($con, (int)$pid) : [];
?>

<div class="tab-pane fade" id="list-pres" role="tabpanel" aria-labelledby="list-pres-list">
  
        <table class="table table-hover">
          <thead>
            <tr>
              
              <th scope="col">Doctor Name</th>
              <th scope="col">Appointment ID</th>
              <th scope="col">Appointment Date</th>
              <th scope="col">Appointment Time</th>
              <th scope="col">Diseases</th>
              <th scope="col">Allergies</th>
              <th scope="col">Prescriptions</th>
              <th scope="col">Bill Payment</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($prescriptions as $row): ?>
                <tr>
                  <td><?php echo $row['doctor']; ?></td>
                  <td><?php echo $row['ID']; ?></td>
                  <td><?php echo $row['appdate']; ?></td>
                  <td><?php echo $row['apptime']; ?></td>
                  <td><?php echo $row['disease']; ?></td>
                  <td><?php echo $row['allergy']; ?></td>
                  <td><?php echo $row['prescription']; ?></td>
                  <td>
                        <a href="dashboard.php?ID=<?php echo $row['ID']?>"
                           onclick="alert('Bill Paid Successfully');"
                           class="btn btn-success">Pay Bill</a>
                  </td>
                </tr>
              <?php endforeach; ?>
          </tbody>
        </table>
  <br>
</div>