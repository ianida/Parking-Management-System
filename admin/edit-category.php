<?php include('include/header.php'); ?>
<?php
if(isset($_POST['submit']))
{
   $catname=$_POST['catename'];
$eid=$_GET['editid'];
 
  $query=mysqli_query($conn, "update tblcategory set VehicleCat='$catname' where ID='$eid'");
  if ($query) {
  
  echo "<script>alert('Category Details updated');</script>";
}
else
  {
  
    echo "<script>alert('Something Went Wrong. Please try again');</script>";
  }

}
?>

<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <strong>Update Category</strong>
                </div>
                <div class="card-body">
                    <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                        <?php
                        $cid = $_GET['editid'];
                        $ret = mysqli_query($conn, "select * from tblcategory where ID='$cid'");
                        while ($row = mysqli_fetch_array($ret)) {
                            ?>
                            <div class="form-group row">
                                <label for="catename" class="col-md-3 col-form-label">Category Name</label>
                                <div class="col-md-9">
                                    <input type="text" id="catename" name="catename" class="form-control"
                                           placeholder="Vehicle Category" required value="<?php echo $row['VehicleCat']; ?>">
                                </div>
                            </div>
                        <?php } ?>
                        <div class="form-group row">
                            <div class="col-md-12 text-center">
                                <button type="submit" class="btn btn-primary" name="submit">Update</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>



<?php include('include/footer.php'); ?>