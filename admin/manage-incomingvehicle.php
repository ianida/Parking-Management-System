<?php include('include/header.php'); ?>

<?php
$catid = isset($_GET['del']) ? $_GET['del'] : 0;
if (!empty($catid)) {
    $catid = mysqli_real_escape_string($conn, $catid);
    $query = "DELETE FROM tblvehicle WHERE ID = '$catid'";
    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Data Deleted');</script>";
        echo "<script>window.location.href='manage-incomingvehicle.php'</script>";
    } else {
        echo "Error: 'del' key is not set." . mysqli_error($conn);
    }
}
?>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>Manage Incoming Vehicle</h4>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>S.NO</th>
                                <th>Parking Number</th>
                                <th>Owner Name</th>
                                <th>Vehicle Reg Number</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $ret = mysqli_query($conn, "
                                SELECT tblvehicle.*, users.name AS OwnerName
                                FROM tblvehicle
                                LEFT JOIN users ON tblvehicle.UserId = users.id
                                WHERE tblvehicle.Status = 'IN'
                            ");
                            $cnt = 1;
                            while ($row = mysqli_fetch_array($ret)) {
                            ?>
                                <tr>
                                    <td><?= $cnt; ?></td>
                                    <td><?= htmlspecialchars($row['ParkingNumber']); ?></td>
                                    <td><?= htmlspecialchars($row['OwnerName']); ?></td>
                                    <td><?= htmlspecialchars($row['RegistrationNumber']); ?></td>
                                    <td>
                                        <a href="view-incomingvehicle-detail.php?viewid=<?= $row['ID']; ?>" class="btn btn-primary">View</a>
                                        <a href="print.php?vid=<?= $row['ID']; ?>" style="cursor:pointer" target="_blank" class="btn btn-warning">Print</a>
                                        <a href="manage-incomingvehicle.php?del=<?= $row['ID']; ?>" class="btn btn-danger" onClick="return confirm('Are you sure you want to delete?')">Delete</a>
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

<?php include('include/footer.php'); ?>
