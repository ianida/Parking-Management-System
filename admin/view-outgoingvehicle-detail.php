<?php include('include/header.php'); ?>

<div class="container">
                <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4> View Outcoming Vehicle </h4>
                        </div>
                        <div class="card-body">
                  
              <?php
              $cid=$_GET['viewid'];
              $ret=mysqli_query($conn,"select * from tblvehicle where ID='$cid'");
              $cnt=1;
while ($row=mysqli_fetch_array($ret)) {

?>                       <table border="1" class="table table-bordered mg-b-0">
   
   <tr>
                                <th>Parking Number</th>
                                   <td><?php  echo $row['ParkingNumber'];?></td>
                                   </tr>                             
<tr>
                                <th>Vehicle Category</th>
                                   <td><?php  echo $row['VehicleCategory'];?></td>
                                   </tr>
                                   <tr>
                                <th>Vehicle Company Name</th>
                                   <td><?php  echo $packprice= $row['VehicleCompanyname'];?></td>
                                   </tr>
                                <tr>
                                <th>Registration Number</th>
                                   <td><?php  echo $row['RegistrationNumber'];?></td>
                                   </tr>
                                   <tr>
                                    <th>Owner Name</th>
                                      <td><?php  echo $row['OwnerName'];?></td>
                                  </tr>
                                      <tr>  
                                       <th>Owner Contact Number</th>
                                        <td><?php  echo $row['OwnerContactNumber'];?></td>
                                    </tr>
                                    <tr>
                               <th>In Time</th>
                                <td><?php  echo $row['InTime'];?></td>
                            </tr>
                            <tr>
                               <th>Out Time</th>
                                <td><?php  echo $row['OutTime'];?></td>
                            </tr>
                            <tr>
                            <th>Remark</th>
    <td><?php echo $row['Remark']; ?></td>
  </tr>
   <tr>
    <th>Status</th>
    <td><?php echo $row['Status']; ?></td>
  </tr>
<tr>
   <tr>
    <th>Parking Fee</th>
   <td><?php echo $row['ParkingCharge']; ?></td>
  </tr>

</table>

                    </div>
                </div>
<?php } ?>
            </div>



        </div>
    </div><!-- .animated -->



<?php include('include/footer.php'); ?>