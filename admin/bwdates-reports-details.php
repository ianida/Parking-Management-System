<?php include('include/header.php'); ?>

<div class="content">
  <div class="animated fadeIn">
    <div class="row">
      <div class="col-lg-12">
        <div class="card mb-4">
          <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="card-title mb-0">Between Date Reports</h4>
            <button onclick="printReport()" class="btn btn-success btn-sm">Print Report</button>
          </div>
          <div class="card-body">
            <?php
            if (!isset($_POST['fromdate']) || !isset($_POST['todate']) || empty($_POST['fromdate']) || empty($_POST['todate'])) {
              echo '<p style="color:red; text-align:center;">Please select both From Date and To Date.</p>';
              echo '<p style="text-align:center;"><a href="bwdates-report-ds.php" class="btn btn-secondary">Back to Search</a></p>';
            } else {
              $fdate = $_POST['fromdate'];
              $tdate = $_POST['todate'];
            ?>

            <div id="printable-area">
              <h5 align="center" style="color:blue;">
                <?php echo "Report from " . htmlspecialchars($fdate) . " to " . htmlspecialchars($tdate); ?>
              </h5>

              <?php
              // Query 1 - User & Parking Space Details with Space Owner Name
              $query1 = "SELECT 
                  us.userSpaceId,
                  u.name AS user_name,
                  s.location,
                  s.vehicletype,
                  us.StartTime,
                  us.EndTime,
                  su.name AS space_owner_name
                FROM userspace us
                LEFT JOIN users u ON us.userid = u.id
                LEFT JOIN space s ON us.spaceid = s.space_id
                LEFT JOIN users su ON s.user_id = su.id
                WHERE DATE(us.StartTime) BETWEEN ? AND ?
                ORDER BY us.StartTime DESC
              ";

              $stmt1 = $conn->prepare($query1);
              $stmt1->bind_param("ss", $fdate, $tdate);
              $stmt1->execute();
              $result1 = $stmt1->get_result();
              ?>

              <h5>User & Parking Space Details</h5>
              <table class="table table-bordered" style="margin-bottom: 40px;">
                <thead>
                  <tr>
                    <th>S.NO</th>
                    <th>User Name</th>
                    <th>Space Owner</th>
                    <th>Location</th>
                    <th>Vehicle Type</th>
                    <th>Start Time</th>
                    <th>End Time</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $cnt = 1;
                  while ($row = $result1->fetch_assoc()) {
                  ?>
                    <tr>
                      <td><?= $cnt ?></td>
                      <td><?= htmlspecialchars($row['user_name']) ?></td>
                      <td><?= htmlspecialchars($row['space_owner_name'] ?? 'N/A') ?></td>
                      <td><?= htmlspecialchars($row['location']) ?></td>
                      <td><?= htmlspecialchars($row['vehicletype']) ?></td>
                      <td><?= htmlspecialchars($row['StartTime']) ?></td>
                      <td><?= htmlspecialchars($row['EndTime'] ?: 'N/A') ?></td>
                    </tr>
                  <?php
                    $cnt++;
                  }
                  ?>
                </tbody>
              </table>

              <?php
              // Query 2 - Vehicle & Booking Details with Vehicle Owner Name
              $query2 = "SELECT 
                  us.userSpaceId,
                  us.ParkingNumber,
                  v.VehicleModel,
                  v.VehicleCategory,
                  v.VehicleCompanyname,
                  v.RegistrationNumber,
                  us.Fare AS ParkingFees,
                  vu.name AS vehicle_owner_name
                FROM userspace us
                LEFT JOIN tblvehicle v ON us.vehicle_id = v.ID
                LEFT JOIN users vu ON v.UserId = vu.id
                WHERE DATE(us.StartTime) BETWEEN ? AND ?
                ORDER BY us.StartTime DESC
              ";


              $stmt2 = $conn->prepare($query2);
              $stmt2->bind_param("ss", $fdate, $tdate);
              $stmt2->execute();
              $result2 = $stmt2->get_result();
              ?>

              <h5>Vehicle & Booking Details</h5>
              <div style="overflow-x:auto;">
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th>S.NO</th>
                      <th>Parking<br>Number</th>
                      <th>Vehicle Model</th>
                      <th>Category</th>
                      <th>Company Name</th>
                      <th>Registration<br>Number</th>
                      <th>Vehicle Owner</th>
                      <th>Fare</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $cnt = 1;
                    while ($row = $result2->fetch_assoc()) {
                    ?>
                      <tr>
                        <td><?= $cnt ?></td>
                        <td><?= htmlspecialchars($row['ParkingNumber'] ?: 'N/A') ?></td>
                        <td><?= htmlspecialchars($row['VehicleModel']) ?></td>
                        <td><?= htmlspecialchars($row['VehicleCategory']) ?></td>
                        <td><?= htmlspecialchars($row['VehicleCompanyname']) ?></td>
                        <td><?= htmlspecialchars($row['RegistrationNumber']) ?></td>
                        <td><?= htmlspecialchars($row['vehicle_owner_name'] ?? 'N/A') ?></td>
                        <td><?= htmlspecialchars(number_format($row['ParkingFees'], 2)) ?></td>
                      </tr>
                    <?php
                      $cnt++;
                    }
                    ?>
                  </tbody>
                </table>
              </div>
            </div>

            <?php
              $stmt1->close();
              $stmt2->close();
            }
            ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
function printReport() {
  var printContents = document.getElementById('printable-area').innerHTML;

  var printWindow = window.open('', '', 'width=900,height=700');
  printWindow.document.write(`
    <html>
    <head>
      <title>Parking Report</title>
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css">
      <style>
        body { margin: 20px; font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 8px; }
        th { background-color: #f0f0f0; }
      </style>
    </head>
    <body>
      ${printContents}
    </body>
    </html>
  `);
  printWindow.document.close();
  printWindow.focus();

  setTimeout(function() {
    printWindow.print();
    printWindow.close();
  }, 500);
}
</script>

<?php include('include/footer.php'); ?>
