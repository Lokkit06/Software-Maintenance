<div class="tab-pane fade show active" id="list-dash" role="tabpanel" aria-labelledby="list-dash-list">
  <div class="container-fluid container-fullw bg-white" >
        <div class="row">
         <div class="col-sm-4">
            <div class="panel panel-white no-radius text-center">
              <div class="panel-body">
                <span class="fa-stack fa-2x"> <i class="fa fa-square fa-stack-2x text-primary"></i> <i class="fa fa-users fa-stack-1x fa-inverse"></i> </span>
                <h4 class="StepTitle" style="margin-top: 5%;">Doctor List</h4>
                <script>
                  function clickDiv(id) {
                    document.querySelector(id).click();
                  }
                </script> 
                <p class="links cl-effect-1">
                  <a href="#list-doc" onclick="clickDiv('#list-doc-list')">
                    View Doctors
                  </a>
                </p>
              </div>
            </div>
          </div>

          <div class="col-sm-4" style="left: -3%">
            <div class="panel panel-white no-radius text-center">
              <div class="panel-body" >
                <span class="fa-stack fa-2x"> <i class="fa fa-square fa-stack-2x text-primary"></i> <i class="fa fa-users fa-stack-1x fa-inverse"></i> </span>
                <h4 class="StepTitle" style="margin-top: 5%;">Patient List</h4>
                
                <p class="cl-effect-1">
                  <a href="#app-hist" onclick="clickDiv('#list-pat-list')">
                    View Patients
                  </a>
                </p>
              </div>
            </div>
          </div>
        

          <div class="col-sm-4">
            <div class="panel panel-white no-radius text-center">
              <div class="panel-body" >
                <span class="fa-stack fa-2x"> <i class="fa fa-square fa-stack-2x text-primary"></i> <i class="fa fa-paperclip fa-stack-1x fa-inverse"></i> </span>
                <h4 class="StepTitle" style="margin-top: 5%;">Appointment Details</h4>
              
                <p class="cl-effect-1">
                  <a href="#app-hist" onclick="clickDiv('#list-app-list')">
                    View Appointments
                  </a>
                </p>
              </div>
            </div>
          </div>
          </div>

          <div class="row">
          <div class="col-sm-4" style="left: 13%;margin-top: 5%;">
            <div class="panel panel-white no-radius text-center">
              <div class="panel-body" >
                <span class="fa-stack fa-2x"> <i class="fa fa-square fa-stack-2x text-primary"></i> <i class="fa fa-list-ul fa-stack-1x fa-inverse"></i> </span>
                <h4 class="StepTitle" style="margin-top: 5%;">Prescription List</h4>
              
                <p class="cl-effect-1">
                  <a href="#list-pres" onclick="clickDiv('#list-pres-list')">
                    View Prescriptions
                  </a>
                </p>
              </div>
            </div>
          </div>


          <div class="col-sm-4" style="left: 18%;margin-top: 5%">
            <div class="panel panel-white no-radius text-center">
              <div class="panel-body" >
                <span class="fa-stack fa-2x"> <i class="fa fa-square fa-stack-2x text-primary"></i> <i class="fa fa-plus fa-stack-1x fa-inverse"></i> </span>
                <h4 class="StepTitle" style="margin-top: 5%;">Manage Doctors</h4>
              
                <p class="cl-effect-1">
                  <a href="#app-hist" onclick="clickDiv('#list-adoc-list')">Add Doctors</a>
                  &nbsp|
                  <a href="#app-hist" onclick="clickDiv('#list-ddoc-list')">
                    Delete Doctors
                  </a>
                </p>
              </div>
            </div>
          </div>
          </div>
        </div>
      </div>