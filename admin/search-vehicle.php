<!-- <?php //include('include/header.php'); ?>
<div class="content">
            <div class="animated fadeIn">
                <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Search Vehicle</h4>
                        </div>
                        <div class="card-body">
<form action="" method="post" enctype="multipart/form-data" class="form-horizontal" name="search">
                                    <div class="row form-group">
                                    <div class="col col-md-3">
                                        <label for="text-input" class="col-form-label">Search By Parking Number</label>
                                    </div>
                                        <div class="col-12 col-md-9"><input type="text" id="searchdata" name="searchdata" class="form-control"  required="required" autofocus="autofocus" ></div>
                                    </div>
                                   <p style="text-align: center;"> <button type="submit" class="btn btn-primary btn-sm" name="search" >Search</button></p>
                                </form>
 <?php
/*if(isset($_POST['search']))
{ 
$sdata=$_POST['searchdata'];
  ?>
  <h4 align="center">Result against "<?php echo $sdata;?>" keyword </h4> 
                             <table class="table">
                <thead>
                                        <tr>
                                            <tr>
                  <th>S.NO</th>
                    <th>Parking Number</th>
                    <th>Owner Name</th>
                    <th>Vehicle Reg. Number</th>  
                          <th>Action</th>
                </tr>
                                        </tr>
                                        </thead>
               <?php
$ret=mysqli_query($conn,"select *from   tblvehicle where ParkingNumber like '$sdata%'");
$num=mysqli_num_rows($ret);
if($num>0){
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
} } else { ?>
     <tr>
    <td colspan="8"> No record found against this search</td>

  </tr>
<?php } }?>
              </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php //include('include/footer.php'); ?> -->
*/