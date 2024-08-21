<?php include('include/header.php'); ?>

<div class="content">
            <div class="animated fadeIn">
                <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Between Date Reports</h4>
                        </div>
                        <div class="card-body">
                            <?php
                            $fdate=$_POST['fromdate'];
                            $tdate=$_POST['todate'];
                            ?>
                            <h5 align="center" style="color:blue">Report from <?php echo $fdate?> to <?php echo $tdate?></h5>
                             <table class="table">
                <thead>
                                        <tr>
                                            <tr>
                  <th>S.NO</th>
                    <th>Parking Number</th>
                    <th>Owner Name</th>
                    <th>Vehicle Reg Number</th>
                          <th>Action</th>
                </tr>
                                        </tr>
                                        </thead>
               <?php
$ret=mysqli_query($conn,"select *from   tblvehicle where date(InTime) between '$fdate' and '$tdate'");
$cnt=1;
while ($row=mysqli_fetch_array($ret)) {
?>      
                <tr>
                  <td><?php echo $cnt;?></td>
                  <td><?php  echo $row['ParkingNumber'];?></td>
                  <td><?php  echo $row['OwnerName'];?></td>
                  <td><?php  echo $row['RegistrationNumber'];?></td>
                  <td><a href="view-incomingvehicle-detail.php?viewid=<?php echo $row['ID'];?>"class="btn btn-primary">View</a></td>
                </tr>
                <?php 
$cnt=$cnt+1;
}?>
              </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>






<?php include('include/footer.php'); ?>