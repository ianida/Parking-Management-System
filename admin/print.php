<?php
require '../config/function.php';

$cid = isset($_GET['vid']) ? intval($_GET['vid']) : 0;

// Query to get vehicle info with user info and latest booking info (from userspace)
$query = "
    SELECT 
        v.ParkingNumber,
        v.VehicleCategory,
        v.VehicleCompanyname,
        v.RegistrationNumber,
        u.name AS OwnerName,
        u.phone AS OwnerContactNumber,
        us.StartTime AS InTime,
        us.EndTime AS OutTime,
        us.status AS BookingStatus,
        us.ParkingFees
    FROM tblvehicle v
    LEFT JOIN users u ON v.UserId = u.id
    LEFT JOIN userspace us ON us.vehicle_id = v.ID
    WHERE v.ID = ?
    ORDER BY us.StartTime DESC
    LIMIT 1
";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $cid);
$stmt->execute();
$ret = $stmt->get_result();

if ($row = $ret->fetch_assoc()) {
?>

<div id="exampl">

    <table border="1" class="table table-bordered mg-b-0">
        <tr>
            <th colspan="4" style="text-align: center; font-size:22px;">Vehicle Parking Receipt</th>
        </tr>

        <tr>
            <th>Parking Number</th>
            <td><?= htmlspecialchars($row['ParkingNumber']); ?></td>
            <th>Vehicle Category</th>
            <td><?= htmlspecialchars($row['VehicleCategory']); ?></td>
        </tr>

        <tr>
            <th>Vehicle Company Name</th>
            <td><?= htmlspecialchars($row['VehicleCompanyname']); ?></td>
            <th>Registration Number</th>
            <td><?= htmlspecialchars($row['RegistrationNumber']); ?></td>
        </tr>

        <tr>
            <th>Owner Name</th>
            <td><?= htmlspecialchars($row['OwnerName']); ?></td>
            <th>Owner Contact Number</th>
            <td><?= htmlspecialchars($row['OwnerContactNumber']); ?></td>
        </tr>

        <tr>
            <th>In Time</th>
            <td><?= htmlspecialchars($row['InTime']); ?></td>
            <th>Status</th>
            <td>
                <?php
                if ($row['BookingStatus'] === '1') {
                    echo "Incoming Vehicle";
                } elseif ($row['BookingStatus'] === '0') {
                    echo "Outgoing Vehicle";
                } else {
                    echo "Unknown";
                }
                ?>
            </td>
        </tr>

        <tr>
            <th>Out Time</th>
            <td><?= htmlspecialchars($row['OutTime'] ?: 'N/A'); ?></td>
            <th>Parking Charge</th>
            <td><?= htmlspecialchars(number_format($row['ParkingFees'], 2)); ?></td>
        </tr>

        <tr>
            <td colspan="4" style="text-align:center; cursor:pointer;">
                <i class="fa fa-print fa-2x" aria-hidden="true" onclick="CallPrint()" style="cursor:pointer;"></i>
            </td>
        </tr>
    </table>

</div>

<script>
function CallPrint() {
    var prtContent = document.getElementById("exampl");
    if (!prtContent) {
        alert("Print content not found!");
        return;
    }

    var WinPrint = window.open('', '', 'width=900,height=650');

    var html = `
    <html>
    <head>
        <title>Print Vehicle Receipt</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" />
        <style>
            body { margin: 20px; }
            table { width: 100%; border-collapse: collapse; }
            th, td { border: 1px solid #000; padding: 8px; }
            th { background-color: #f0f0f0; }
        </style>
    </head>
    <body>
        ${prtContent.innerHTML}
    </body>
    </html>`;

    WinPrint.document.write(html);
    WinPrint.document.close();
    WinPrint.focus();

    WinPrint.onload = function() {
        WinPrint.print();
        WinPrint.close();
    };
}
</script>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/normalize.css@8.0.0/normalize.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css">

<?php
} else {
    echo "No vehicle found with the given ID.";
}
$stmt->close();
?>
