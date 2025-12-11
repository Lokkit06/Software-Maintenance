<div class="tab-pane fade" id="list-home" role="tabpanel" aria-labelledby="list-home-list">
  <div class="container-fluid">
    <div class="card">
      <div class="card-body">
        <center><h4>Create an appointment</h4></center><br>
        <?php
          require_once __DIR__ . '/../../includes/doctors.php';
          $specs   = fetch_doctor_specs($con);
          $doctors = fetch_doctors_with_fees($con);
        ?>
        <form class="form-group" method="post" action="../../actions/book_appointment.php">
          <div class="row">
            
  

              <div class="col-md-4">
                    <label for="spec">Specialization:</label>
                  </div>
                  <div class="col-md-8">
                    <select name="spec" class="form-control" id="spec" required>
                        <option value="" disabled selected>Select Specialization</option>
                        <?php foreach ($specs as $spec): ?>
                          <option value="<?php echo htmlspecialchars($spec); ?>">
                            <?php echo htmlspecialchars($spec); ?>
                          </option>
                        <?php endforeach; ?>
                    </select>
                  </div>

                  <br><br>

                  <script>
                document.getElementById('spec').onchange = function foo() {
                  let spec = this.value;   
                  console.log(spec)
                  let docs = [...document.getElementById('doctor').options];
                  
                  docs.forEach((el, ind, arr)=>{
                    arr[ind].setAttribute("style","");
                    if (el.getAttribute("data-spec") != spec ) {
                      arr[ind].setAttribute("style","display: none");
                    }
                  });
                };

            </script>

        <div class="col-md-4"><label for="doctor">Doctors:</label></div>
          <div class="col-md-8">
              <select name="doctor" class="form-control" id="doctor" required="required">
                <option value="" disabled selected>Select Doctor</option>
                <?php foreach ($doctors as $doc): ?>
                  <option value="<?php echo htmlspecialchars($doc['username']); ?>"
                          data-value="<?php echo htmlspecialchars($doc['docFees']); ?>"
                          data-spec="<?php echo htmlspecialchars($doc['spec']); ?>">
                    <?php echo htmlspecialchars($doc['username']); ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div><br/><br/> 


                  <script>
        document.getElementById('doctor').onchange = function updateFees(e) {
          const selected = this.selectedOptions[0];
          const fee = selected ? selected.getAttribute('data-value') : '';
          document.getElementById('docFees').value = fee;
        };
      </script>

            
            <div class="col-md-4"><label for="consultancyfees">
                          Consultancy Fees
                        </label></div>
                        <div class="col-md-8">
                        <input class="form-control" type="text" name="docFees" id="docFees" readonly="readonly"/>
            </div><br><br>

            <div class="col-md-4"><label>Appointment Date</label></div>
            <div class="col-md-8"><input type="date" class="form-control datepicker" name="appdate"></div><br><br>

            <div class="col-md-4"><label>Appointment Time</label></div>
            <div class="col-md-8">
              <select name="apptime" class="form-control" id="apptime" required="required">
                <option value="" disabled selected>Select Time</option>
                <option value="08:00:00">8:00 AM</option>
                <option value="10:00:00">10:00 AM</option>
                <option value="12:00:00">12:00 PM</option>
                <option value="14:00:00">2:00 PM</option>
                <option value="16:00:00">4:00 PM</option>
              </select>

            </div><br><br>

            <div class="col-md-4">
              <input type="submit" name="app-submit" value="Create new entry" class="btn btn-primary" id="inputbtn">
            </div>
            <div class="col-md-8"></div>                  
          </div>
        </form>
      </div>
    </div>
  </div><br>
</div>