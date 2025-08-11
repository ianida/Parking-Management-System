<?php include('include/header.php'); ?>

<?php
if (isset($_POST['submit'])) {
    $cid = $_GET['viewid'];
    $remark = $_POST['remark'];
    $status = $_POST['status'];
    $parkingcharge = $_POST['parkingcharge'];

    $query = mysqli_query($conn, "UPDATE tblvehicle SET Remark='$remark', Status='$status', ParkingCharge='$parkingcharge' WHERE ID='$cid'");
    if ($query) {
        echo "<script>alert('All remarks has been updated');</script>";
    } else {
        echo "<script>alert('Something Went Wrong. Please try again');</script>";
    }
}
?>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>View Incoming Vehicle</h4>
                </div>
                <div class="card-body">

                    <?php
                    $cid = $_GET['viewid'];
                    $ret = mysqli_query($conn, "
                        SELECT tblvehicle.*, users.name AS OwnerName, users.phone AS OwnerContactNumber
                        FROM tblvehicle
                        LEFT JOIN users ON tblvehicle.UserId = users.id
                        WHERE tblvehicle.ID = '$cid'
                    ");
                    while ($row = mysqli_fetch_array($ret)) {
                    ?>
                        <table border="1" class="table table-bordered mg-b-0">
                            <tr>
                                <th>Parking Number</th>
                                <td><?= htmlspecialchars($row['ParkingNumber']); ?></td>
                            </tr>
                            <tr>
                                <th>Vehicle Category</th>
                                <td><?= htmlspecialchars($row['VehicleCategory']); ?></td>
                            </tr>
                            <tr>
                                <th>Vehicle Company Name</th>
                                <td><?= htmlspecialchars($row['VehicleCompanyname']); ?></td>
                            </tr>
                            <tr>
                                <th>Registration Number</th>
                                <td><?= htmlspecialchars($row['RegistrationNumber']); ?></td>
                            </tr>
                            <tr>
                                <th>Owner Name</th>
                                <td><?= htmlspecialchars($row['OwnerName']); ?></td>
                            </tr>
                            <tr>
                                <th>Owner Contact Number</th>
                                <td><?= htmlspecialchars($row['OwnerContactNumber']); ?></td>
                            </tr>
                            <tr>
                                <th>In Time</th>
                                <td><?= htmlspecialchars($row['InTime']); ?></td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td>
                                    <?php
                                    if ($row['Status'] == "") {
                                        echo "Vehicle In";
                                    } elseif ($row['Status'] == "Out") {
                                        echo "Vehicle Out";
                                    }
                                    ?>
                                </td>
                            </tr>
                        </table>
                </div>
            </div>

            <table class="table mb-0">
                <?php if ($row['Status'] == "") { ?>
                    <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                        <tr>
                            <th>Remark :</th>
                            <td>
                                <textarea name="remark" rows="12" cols="14" class="form-control" required></textarea>
                            </td>
                        </tr>
                        <tr>
                            <th>Parking Charge: </th>
                            <td>
                                <input type="text" name="parkingcharge" id="parkingcharge" class="form-control">
                            </td>
                        </tr>
                        <tr>
                            <th>Status :</th>
                            <td>
                                <select name="status" class="form-control" required>
                                    <option value="Out">Outgoing Vehicle</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" style="text-align: center;">
                                <button type="submit" class="btn btn-primary btn-sm" name="submit">Update</button>
                            </td>
                        </tr>
                    </form>
                <?php } else { ?>
                    <table border="1" class="table table-bordered mg-b-0">
                        <tr>
                            <th>Remark</th>
                            <td><?= htmlspecialchars($row['Remark']); ?></td>
                        </tr>
                        <tr>
                            <th>Parking Fee</th>
                            <td><?= htmlspecialchars($row['ParkingCharge']); ?></td>
                        </tr>
                    </table>
                <?php } ?>
            </table>
        <?php } ?>
        </div>
    </div>
</div>

<?php include('include/footer.php'); ?>
