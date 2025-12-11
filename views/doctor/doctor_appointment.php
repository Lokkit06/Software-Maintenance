<?php
require_once __DIR__ . '/../../config/db_connect.php';
require_once __DIR__ . '/../../includes/doctor_appointments.php';
$dname = $_SESSION['dname'] ?? '';
$doctorAppointments = fetch_doctor_appointments($con, $dname);
?>

<div class="tab-pane fade" id="list-app" role="tabpanel" aria-labelledby="list-home-list">
  <table class="table table-hover">
    <thead>
      <tr>
        <th scope="col">Patient ID</th>
        <th scope="col">Appointment ID</th>
        <th scope="col">First Name</th>
        <th scope="col">Last Name</th>
        <th scope="col">Gender</th>
        <th scope="col">Email</th>
        <th scope="col">Contact</th>
        <th scope="col">Appointment Date</th>
        <th scope="col">Appointment Time</th>
        <th scope="col">Current Status</th>
        <th scope="col">Action</th>
        <th scope="col">Prescribe</th>

      </tr>
    </thead>
    <tbody>
      <?php foreach ($doctorAppointments as $row): ?>
          <tr>
          <td><?php echo $row['pid']; ?></td>
            <td><?php echo $row['ID']; ?></td>
            <td><?php echo $row['fname']; ?></td>
            <td><?php echo $row['lname']; ?></td>
            <td><?php echo $row['gender']; ?></td>
            <td><?php echo $row['email']; ?></td>
            <td><?php echo $row['contact']; ?></td>
            <td><?php echo $row['appdate']; ?></td>
            <td><?php echo $row['apptime']; ?></td>
            <td><?php echo format_doctor_app_status($row); ?></td>

          <td>
            <?php if(($row['userStatus']==1) && ($row['doctorStatus']==1))  
            { ?>

              
              <a href="../../actions/cancel_doctor_appointment.php?ID=<?php echo $row['ID']?>"
                  onClick="return confirm('Are you sure you want to cancel this appointment ?')"
                  title="Cancel Appointment" tooltip-placement="top" tooltip="Remove"><button class="btn btn-danger">Cancel</button></a>
              <?php } else {

                    echo "Cancelled";
                    } ?>
            
            </td>

            <td>

            <?php if(($row['userStatus']==1) && ($row['doctorStatus']==1))  
            { ?>

            <a href="prescribe.php?pid=<?php echo $row['pid']?>&ID=<?php echo $row['ID']?>&fname=<?php echo $row['fname']?>&lname=<?php echo $row['lname']?>&appdate=<?php echo $row['appdate']?>&apptime=<?php echo $row['apptime']?>"
            tooltip-placement="top" tooltip="Remove" title="prescribe">
            <button class="btn btn-success">Prescibe</button></a>
            <?php } else {

                echo "-";
                } ?>
            
            </td>


          </tr></a>
        <?php endforeach; ?>
    </tbody>
  </table>
  <br>
</div>