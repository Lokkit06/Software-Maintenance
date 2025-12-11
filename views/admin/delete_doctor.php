<div class="tab-pane fade" id="list-settings1" role="tabpanel" aria-labelledby="list-settings1-list">
  <form class="form-group" method="post" action="../../actions/delete_doctor.php">
    <div class="row">
    
      <div class="col-md-4"><label>Email ID:</label></div>
      <div class="col-md-8"><input type="email"  class="form-control" name="demail" required></div><br><br>
      
    </div>
    <input type="submit" name="docsub1" value="Delete Doctor" class="btn btn-primary" onclick="confirm('do you really want to delete?')">
  </form>
</div>