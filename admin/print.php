<?php
require '../config/function.php';

$cid = isset($_GET['vid']) ? intval($_GET['vid']) : 0;

$query = "
    SELECT 
        tblvehicle.*,
        users.name AS OwnerName,
        users.phone AS OwnerContactNumber
    FROM tblvehicle
    LEFT JOIN users ON tblvehicle.UserId = users.id
    WHERE tblvehicle.ID = $cid
";

$ret = mysqli_query($conn, $query);

while ($row = mysqli_fetch_array($ret)) {
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
                if ($row['Status'] == "") {
                    echo "Incoming Vehicle";
                } elseif ($row['Status'] == "Out") {
                    echo "Outgoing Vehicle";
                } else {
                    echo htmlspecialchars($row['Status']);
                }
                ?>
            </td>
        </tr>

        <?php if (!empty($row['Remark'])) { ?>
        <tr>
            <th>Out Time</th>
            <td><?= htmlspecialchars($row['OutTime']); ?></td>
            <th>Parking Charge</th>
            <td><?= htmlspecialchars($row['ParkingCharge']); ?></td>
        </tr>
        <tr>
            <th>Remark</th>
            <td colspan="3"><?= htmlspecialchars($row['Remark']); ?></td>
        </tr>
        <?php } ?>

        <tr>
            <td colspan="4" style="text-align:center; cursor:pointer">
                <i class="fa fa-print fa-2x" aria-hidden="true" onclick="CallPrint()"></i>
            </td>
        </tr>
    </table>

</div>

<script>
function CallPrint() {
    var prtContent = document.getElementById("exampl").cloneNode(true);

    // Remove the print icon row from cloned content
    var printIconRow = prtContent.querySelector('tr:last-child');
    if(printIconRow) {
        printIconRow.remove();
    }

    var WinPrint = window.open('', '', 'left=0,top=0,width=800,height=900,toolbar=0,scrollbars=0,status=0');

    WinPrint.document.write(`
        <html>
        <head>
            <title>Print Vehicle Receipt</title>
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css">
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css">
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
        </html>
    `);

    WinPrint.document.close();
    WinPrint.focus();

    setTimeout(() => {
        WinPrint.print();
        WinPrint.close();
    }, 500);
}

</script>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/normalize.css@8.0.0/normalize.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/lykmapipo/themify-icons@0.1.2/css/themify-icons.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pixeden-stroke-7-icon@1.2.3/pe-icon-7-stroke/dist/pe-icon-7-stroke.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.2.0/css/flag-icon.min.css">
<link rel="stylesheet" href="assets/css/cs-skin-elastic.css">
<link rel="stylesheet" href="assets/css/style.css">

<?php } // end while loop ?>
