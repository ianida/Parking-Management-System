<?php include('include/header.php'); ?>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4> View Outgoing Vehicle </h4>
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
                                <th>Out Time</th>
                                <td><?= htmlspecialchars($row['OutTime']); ?></td>
                            </tr>
                            <tr>
                                <th>Remark</th>
                                <td><?= htmlspecialchars($row['Remark']); ?></td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td><?= htmlspecialchars($row['Status']); ?></td>
                            </tr>
                            <tr>
                                <th>Parking Fee</th>
                                <td><?= htmlspecialchars($row['ParkingCharge']); ?></td>
                            </tr>
                        </table>
                    <?php } ?>

                </div>
            </div>
        </div>
    </div>
</div><!-- .animated -->

<?php include('include/footer.php'); ?>
