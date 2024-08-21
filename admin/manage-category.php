<?php include('include/header.php'); ?>
<?php
$catid = isset($_GET['del']) ? $_GET['del'] : 0;


if (!empty($catid)) {
    $catid = mysqli_real_escape_string($conn, $catid);

    $query = "DELETE FROM tblcategory WHERE ID = '$catid'";
    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Data Deleted');</script>";
        echo "<script>window.location.href='manage-category.php'</script>";
    } else {
        echo "Error deleting data: " . mysqli_error($conn);
    }
}
?>

<div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Manage Category</h4>
                        <a href="addcategory.php" class="btn btn-primary float-end">+ Add Category</a>
                    </div>
                    <div class="card-body">
                        <!-- Category Table -->
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>S.NO</th>
                                    <th>Category</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <?php
                            // Assuming you've established the database connection ($conn)
                            $ret = mysqli_query($conn, "SELECT * FROM tblcategory");
                            $cnt = 1;
                            while ($row = mysqli_fetch_array($ret)) {
                                ?>
                                <tr>
                                    <td><?php echo $cnt; ?></td>
                                    <td><?php echo $row['VehicleCat']; ?></td>
                                    <td>
                                        <a href="edit-category.php?editid=<?php echo $row['ID']; ?>" class="btn btn-primary">Edit</a>
                                        <a href="manage-category.php?del=<?php echo $row['ID']; ?>" class="btn btn-danger" onClick="return confirm('Are you sure you want to delete?')">Delete</a>
                                    </td>
                                </tr>
                                <?php
                                $cnt++;
                            }
                            ?>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>




<?php include('include/footer.php'); ?>