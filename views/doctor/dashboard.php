<?php
include('doctor_logic.php');
include('header.php');
?>

<body style="padding-top:50px;">
  <div class="container-fluid" style="margin-top:50px;">
    <h3 style = "margin-left: 40%; padding-bottom: 20px;font-family:'IBM Plex Sans', sans-serif;"> Welcome <?php echo $doctor ?>  </h3>
    <div class="row">
      
      <?php include('sidebar.php'); ?>

      <div class="col-md-8" style="margin-top: 3%;">
        <div class="tab-content" id="nav-tabContent" style="width: 950px;">
          
          <?php include('dashboard_home.php'); ?>
          <?php include('doctor_appointment.php'); ?>
          <?php include('prescription_history.php'); ?>
                    
          <div class="tab-pane fade" id="list-messages" role="tabpanel" aria-labelledby="list-messages-list">...</div>
    
          
          <div class="tab-pane fade" id="list-attend" role="tabpanel" aria-labelledby="list-attend-list">...</div>
          
        </div>
      </div>
    </div>
  </div>

<?php include('footer.php'); ?>