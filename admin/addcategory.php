<?php include('include/header.php'); ?>
<?php
if(isset($_POST['submit']))
    {
         $catname=$_POST['catename'];
       
        $query=mysqli_query($conn,"insert into tblcategory (VehicleCat) value('$catname')");
        if ($query) {
echo "<script>alert('Vehicle Category Entry Detail has been added');</script>";
echo "<script>window.location.href ='manage-category.php'</script>";
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
                    <h4>Add Categories</h4>
                </div>
                <div class="card-body">
                    <form role="form" method="post">
                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">Category</label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control" name="catename" placeholder="Vehicle Category" required>
                            </div>
                        </div>
                        <div class="text-center">
                            <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>



<?php include('include/footer.php'); ?>