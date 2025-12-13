<?php
// 1. Include Logic
include('admin_func.php');

// 2. Include Header (HTML Head + Nav)
include('header.php');
?>

<body style="padding-top:50px;">
  <div class="container-fluid" style="margin-top:50px;">
    <h3 style = "margin-left: 40%; padding-bottom: 20px;font-family: 'IBM Plex Sans', sans-serif;"> WELCOME RECEPTIONIST </h3>
    <div class="row">
      
      <?php include('sidebar.php'); ?>

      <div class="col-md-8" style="margin-top: 3%;">
        <div class="tab-content" id="nav-tabContent" style="width: 950px;">

          <?php include('dashboard_home.php'); ?>
          <?php include('doctor_list.php'); ?>
          <?php include('patient_list.php'); ?>
          <?php include('appointment_list.php'); ?>
          <?php include('prescription_list.php'); ?>
          <?php include('add_doctor.php'); ?>
          <?php include('delete_doctor.php'); ?>
          
          <div class="tab-pane fade" id="list-attend" role="tabpanel" aria-labelledby="list-attend-list">...</div>

        </div>
      </div>
    </div>
  </div>

<?php include('footer.php'); ?>