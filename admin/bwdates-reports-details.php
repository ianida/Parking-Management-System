<?php include('include/header.php'); ?>

<div class="content">
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Between Date Reports</h4>
                    </div>
                    <div class="card-body">
                        <?php
                        $fdate = $_POST['fromdate'];
                        $tdate = $_POST['todate'];
                        ?>
                        <h5 align="center" style="color:blue">Report from <?= htmlspecialchars($fdate) ?> to <?= htmlspecialchars($tdate) ?></h5>
                        <table class="table">
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
                                    WHERE DATE(tblvehicle.InTime) BETWEEN '$fdate' AND '$tdate'
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
                                            <a href="view-incomingvehicle-detail.php?viewid=<?= urlencode($row['ID']); ?>" class="btn btn-primary">View</a>
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
</div>

<?php include('include/footer.php'); ?>
