<div class="tab-pane fade show active" id="list-dash" role="tabpanel" aria-labelledby="list-dash-list">
  <div class="container-fluid container-fullw bg-white" >
  <div class="row">

   <div class="col-sm-4" style="left: 10%">
      <div class="panel panel-white no-radius text-center">
        <div class="panel-body">
          <span class="fa-stack fa-2x"> <i class="fa fa-square fa-stack-2x text-primary"></i> <i class="fa fa-list fa-stack-1x fa-inverse"></i> </span>
          <h4 class="StepTitle" style="margin-top: 5%;"> View Appointments</h4>
          <script>
            function clickDiv(id) {
              document.querySelector(id).click();
            }
          </script>                      
          <p class="links cl-effect-1">
            <a href="#list-app" onclick="clickDiv('#list-app-list')">
              Appointment List
            </a>
          </p>
        </div>
      </div>
    </div>

    <div class="col-sm-4" style="left: 15%">
      <div class="panel panel-white no-radius text-center">
        <div class="panel-body">
          <span class="fa-stack fa-2x"> <i class="fa fa-square fa-stack-2x text-primary"></i> <i class="fa fa-list-ul fa-stack-1x fa-inverse"></i> </span>
          <h4 class="StepTitle" style="margin-top: 5%;"> Prescriptions</h4>
            
          <p class="links cl-effect-1">
            <a href="#list-pres" onclick="clickDiv('#list-pres-list')">
              Prescription List
            </a>
          </p>
        </div>
      </div>
    </div>    

  </div>
</div>
</div>