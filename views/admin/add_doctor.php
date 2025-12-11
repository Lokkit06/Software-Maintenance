<div class="tab-pane fade" id="list-settings" role="tabpanel" aria-labelledby="list-settings-list">
  <form class="form-group" method="post" action="../../actions/add_doctor.php">
    <div class="row">
      <div class="col-md-4"><label>Doctor Name:</label></div>
      <div class="col-md-8"><input type="text" class="form-control" name="doctor" onkeydown="return alphaOnly(event);" required></div><br><br>
      <div class="col-md-4"><label>Specialization:</label></div>
      <div class="col-md-8">
      <select name="special" class="form-control" id="special" required="required">
        <option value="head" name="spec" disabled selected>Select Specialization</option>
        <option value="General" name="spec">General</option>
        <option value="Cardiologist" name="spec">Cardiologist</option>
        <option value="Neurologist" name="spec">Neurologist</option>
        <option value="Pediatrician" name="spec">Pediatrician</option>
      </select>
      </div><br><br>
      <div class="col-md-4"><label>Email ID:</label></div>
      <div class="col-md-8"><input type="email"  class="form-control" name="demail" required></div><br><br>
      <div class="col-md-4"><label>Password:</label></div>
      <div class="col-md-8"><input type="password" class="form-control"  onkeyup='check();' name="dpassword" id="dpassword"  required></div><br><br>
      <div class="col-md-4"><label>Confirm Password:</label></div>
      <div class="col-md-8"  id='cpass'><input type="password" class="form-control" onkeyup='check();' name="cdpassword" id="cdpassword" required>&nbsp &nbsp<span id='message'></span> </div><br><br>
        
      <div class="col-md-4"><label>Consultancy Fees:</label></div>
      <div class="col-md-8"><input type="text" class="form-control"  name="docFees" required></div><br><br>
    </div>
    <input type="submit" name="docsub" value="Add Doctor" class="btn btn-primary">
  </form>
</div>