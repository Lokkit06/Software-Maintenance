<?php
require_once __DIR__ . '/../../includes/appointments.php';
$pid = $_SESSION['pid'] ?? 0;
$appointments = $pid ? fetch_appointments_by_pid($con, (int)$pid) : [];
?>

<div class="tab-pane fade" id="app-hist" role="tabpanel" aria-labelledby="list-pat-list">
  
        <table class="table table-hover">
          <thead>
            <tr>
              
              <th scope="col">Doctor Name</th>
              <th scope="col">Consultancy Fees</th>
              <th scope="col">Appointment Date</th>
              <th scope="col">Appointment Time</th>
              <th scope="col">Current Status</th>
              <th scope="col">Action</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($appointments as $row): ?>
                <tr>
                  <td><?php echo $row['doctor']; ?></td>
                  <td><?php echo $row['docFees']; ?></td>
                  <td><?php echo $row['appdate']; ?></td>
                  <td><?php echo $row['apptime']; ?></td>
                  
                    <td>
              <?php if(($row['userStatus']==1) && ($row['doctorStatus']==1))  
              {
                echo "Active";
              }
              if(($row['userStatus']==0) && ($row['doctorStatus']==1))  
              {
                echo "Cancelled by You";
              }

              if(($row['userStatus']==1) && ($row['doctorStatus']==0))  
              {
                echo "Cancelled by Doctor";
              }
                  ?></td>

                  <td>
                  <?php if(($row['userStatus']==1) && ($row['doctorStatus']==1))  
                  { ?>

                    
                    <a href="../../actions/cancel_patient_appointment.php?ID=<?php echo $row['ID']?>"
                        onClick="return confirm('Are you sure you want to cancel this appointment ?')"
                        title="Cancel Appointment" tooltip-placement="top" tooltip="Remove"><button class="btn btn-danger">Cancel</button></a>
                    <?php } else {

                          echo "Cancelled";
                          } ?>
                  
                  </td>
                </tr>
              <?php endforeach; ?>
          </tbody>
        </table>
  <br>
</div>