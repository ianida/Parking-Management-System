<?php 
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once '../config/dbcon.php';  // require_once is used to avoid multiple includes
include('include/header.php');

$successMessage = '';

// Handles delete request and set message (do NOT redirect immediately)
if (isset($_GET['del'])) {
    $catid = intval($_GET['del']);
    if ($catid > 0) {
        $catid = mysqli_real_escape_string($conn, $catid);
        $query = "DELETE FROM tblcategory WHERE ID = '$catid'";
        if (mysqli_query($conn, $query)) {
            $successMessage = "Category deleted successfully.";
        } else {
            $successMessage = "Error deleting data: " . mysqli_error($conn);
        }
    }
}
?>

<div class="container">
    <div class="row">
        <div class="col-lg-12">

            <?php if ($successMessage): ?>
                <div class="alert alert-success text-center" id="success-msg" role="alert" style="margin-top:20px;">
                    <?php echo htmlspecialchars($successMessage); ?>
                </div>
            <?php endif; ?>

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
                        <tbody>
                            <?php
                            $ret = mysqli_query($conn, "SELECT * FROM tblcategory");
                            $cnt = 1;
                            while ($row = mysqli_fetch_array($ret)) {
                                ?>
                                <tr>
                                    <td><?php echo $cnt; ?></td>
                                    <td><?php echo htmlspecialchars($row['VehicleCat']); ?></td>
                                    <td>
                                        <a href="edit-category.php?editid=<?php echo $row['ID']; ?>" class="btn btn-primary">Edit</a>
                                        <a href="manage-category.php?del=<?php echo $row['ID']; ?>" class="btn btn-danger">Delete</a>
                                    </td>
                                </tr>
                                <?php
                                $cnt++;
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    table.table thead th {
  text-align: center;
}
  
  table.table tbody td {
    text-align: center;
    padding-left: 100px;  
    padding-right: 100px; 
  }

  
  table.table th, table.table td {
    padding-left: 25px;
    padding-right: 25px;
  }
</style>

<script>
    // Hide the success message after 2 seconds
    setTimeout(function() {
        var msg = document.getElementById('success-msg');
        if(msg) {
            msg.style.display = 'none';
        }
    }, 2000); // 2 seconds
</script>

<?php include('include/footer.php'); ?>
