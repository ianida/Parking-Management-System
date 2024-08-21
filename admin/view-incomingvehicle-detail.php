<?php include('include/header.php'); ?>
<?php

if(isset($_POST['submit']))
  {
    
    $cid=$_GET['viewid'];
      $remark=$_POST['remark'];
      $status=$_POST['status'];
      $parkingcharge=$_POST['parkingcharge'];
     
 
    
     
   $query=mysqli_query($conn, "update  tblvehicle set Remark='$remark',Status='$status',ParkingCharge='$parkingcharge' where ID='$cid'");
    if ($query) {
    
     echo "<script>alert('All remarks has been updated');</script>";
  }
  else
    {
    echo "<script>alert('Something Went Wrong. Please try again');</script>";
    }

  
} 

?>

<div class="container">
                <div class="row">
                   
         

                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
<h4> View Incoming Vehicle </h4>
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
    <th>Status</th>
    <td> <?php  
if($row['Status']=="")
{
  echo "Vehicle In";
}
if($row['Status']=="Out")
{
  echo "Vehicle out";
}

     ;?></td>
  </tr>

</table>

                    </div>
                </div>
                <table class="table mb-0">

<?php if($row['Status']==""){ ?>
        <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                                    

  <tr>
    <th>Remark :</th>
    <td>
    <textarea name="remark" placeholder="" rows="12" cols="14" class="form-control" required="true"></textarea></td>
  </tr>
 <tr>
<th>Parking Charge: </th>
<td>
  <input type="text" name="parkingcharge" id="parkingcharge" class="form-control" >
</td></tr>
<tr>
    <th>Status :</th>
    <td>
   <select name="status" class="form-control" required="true" >
     <option value="Out">Outgoing Vehicle</option>
   </select></td>
  </tr>
                                 
                                    
                                    
                                 <tr>  <p style="text-align: center;"><td> <button type="submit" class="btn btn-primary btn-sm" name="submit" >Update</button></p></td></tr>
                                </form>
                            </table>
<?php } else { ?>
    <table border="1" class="table table-bordered mg-b-0">
  <tr>
    <th>Remark</th>
    <td><?php echo $row['Remark']; ?></td>
  </tr>
<tr>
   <tr>
    <th>Parking Fee</th>
   <td><?php echo $row['ParkingCharge']; ?></td>
  </tr>

  

<?php } ?>
</table>


  

  

<?php } ?>
            </div>



        </div>
    </div>
</div>



<?php include('include/footer.php'); ?>