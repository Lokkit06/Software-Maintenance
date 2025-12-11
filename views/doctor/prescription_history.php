<div class="tab-pane fade" id="list-pres" role="tabpanel" aria-labelledby="list-pres-list">
  <table class="table table-hover">
    <thead>
      <tr>
        <th scope="col">Patient ID</th>
        <th scope="col">First Name</th>
        <th scope="col">Last Name</th>
        <th scope="col">Appointment ID</th>
        <th scope="col">Appointment Date</th>
        <th scope="col">Appointment Time</th>
        <th scope="col">Disease</th>
        <th scope="col">Allergy</th>
        <th scope="col">Prescribe</th>
      </tr>
    </thead>
    <tbody>
      <?php 
        global $con;
        $doctor = $_SESSION['dname'];
        $query = "select pid,fname,lname,ID,appdate,apptime,disease,allergy,prescription from prestb where doctor='$doctor';";
        
        $result = mysqli_query($con,$query);
        if(!$result){
          echo mysqli_error($con);
        }
        
        while ($row = mysqli_fetch_array($result)){
      ?>
          <tr>
            <td><?php echo $row['pid'];?></td>
            <td><?php echo $row['fname'];?></td>
            <td><?php echo $row['lname'];?></td>
            <td><?php echo $row['ID'];?></td>
            
            <td><?php echo $row['appdate'];?></td>
            <td><?php echo $row['apptime'];?></td>
            <td><?php echo $row['disease'];?></td>
            <td><?php echo $row['allergy'];?></td>
            <td><?php echo $row['prescription'];?></td>
        
          </tr>
        <?php }
        ?>
    </tbody>
  </table>
</div>