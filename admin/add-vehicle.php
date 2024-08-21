<?php include('include/header.php'); ?>
<?php
if(isset($_POST['submit']))
  {
    $parkingnumber=mt_rand(100000000, 999999999);
    $catename=$_POST['catename'];
    $vehcomp=$_POST['vehcomp'];
    $vehreno=$_POST['vehreno'];
    $ownername=$_POST['ownername'];
    $ownercontno=$_POST['ownercontno'];
    $enteringtime=$_POST['enteringtime'];
    
     
    $query=mysqli_query($conn, "insert into  tblvehicle(ParkingNumber,VehicleCategory,VehicleCompanyname,RegistrationNumber,OwnerName,OwnerContactNumber) value('$parkingnumber','$catename','$vehcomp','$vehreno','$ownername','$ownercontno')");
    if ($query) {
        echo "<script>alert('Vehicle Entry Detail has been added');</script>";
        echo "<script>window.location.href ='manage-incomingvehicle.php'</script>";
    }
    else
    {
        echo "<script>alert('Something Went Wrong. Please try again.');</script>";       
    }

  
}
  ?>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>Add Vehicle</h4>
                </div>
                <div class="card-body">
                    <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">Select Category</label>
                            <div class="col-lg-10">
                                <select name="catename" id="catename" class="form-control">
                                    <option value="0">Select Category</option>
                                    <?php
                                    $query = mysqli_query($conn, "SELECT * FROM tblcategory");
                                    while ($row = mysqli_fetch_array($query)) {
                                        echo '<option value="' . $row['VehicleCat'] . '">' . $row['VehicleCat'] . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">Vehicle Company</label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control" id="vehcomp" name="vehcomp" placeholder="Vehicle Company" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">Registration Number</label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control" id="vehreno" name="vehreno" placeholder="Registration Number" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">Owner Name</label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control" id="ownername" name="ownername" placeholder="Owner Name" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">Owner Contact Number</label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control" id="ownercontno" name="ownercontno" placeholder="Owner Contact Number" required maxlength="10" pattern="[0-9]+">
                            </div>
                        </div>
                        
                        <div class="text-center">
                            <button type="submit" name="submit" class="btn btn-primary">Add</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>





<?php include('include/footer.php'); ?>