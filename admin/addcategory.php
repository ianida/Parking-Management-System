<?php include('include/header.php'); ?>
<?php
if(isset($_POST['submit']))
{
    $catname = $_POST['catename'];

    $query = mysqli_query($conn, "INSERT INTO tblcategory (VehicleCat) VALUES ('$catname')");
    if ($query) {
        echo "<div class='alert alert-success text-center' id='success-msg' role='alert' style='margin-top:20px;'>
                Vehicle Category Entry Detail has been added
              </div>";
    } else {
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

<script>
    setTimeout(function() {
        var msg = document.getElementById('success-msg');
        if(msg) {
            msg.style.display = 'none';
            //window.location.href = 'manage-category.php';
        }
    }, 1500); // 1.5 seconds
</script>

<?php include('include/footer.php'); ?>
