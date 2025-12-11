<div class="tab-pane fade" id="list-mes" role="tabpanel" aria-labelledby="list-mes-list">
  <div class="col-md-8">
    <form class="form-group" action="message_search.php" method="post">
      <div class="row">
      <div class="col-md-10"><input type="text" name="mes_contact" placeholder="Enter Contact" class = "form-control"></div>
      <div class="col-md-2"><input type="submit" name="mes_search_submit" class="btn btn-primary" value="Search"></div></div>
    </form>
  </div>
    
  <table class="table table-hover">
    <thead>
      <tr>
        <th scope="col">User Name</th>
        <th scope="col">Email</th>
        <th scope="col">Contact</th>
        <th scope="col">Message</th>
      </tr>
    </thead>
    <tbody>
      <?php 
        global $con;
        $query = "select * from contact;";
        $result = mysqli_query($con,$query);
        while ($row = mysqli_fetch_array($result)){
      ?>
          <tr>
            <td><?php echo $row['name'];?></td>
            <td><?php echo $row['email'];?></td>
            <td><?php echo $row['contact'];?></td>
            <td><?php echo $row['message'];?></td>
          </tr>
        <?php } ?>
    </tbody>
  </table>
  <br>
</div>