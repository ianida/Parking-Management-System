<?php
session_start();
include('include/dbcon.php');

if (!isset($_SESSION['id'])) {
    header("Location: ../loginform.php");
    exit();
}

$userId = $_SESSION['id'];
$space_id = intval($_GET['space_id'] ?? 0);
if ($space_id <= 0) {
    die("Invalid space.");
}

$amount = 500;  // Rs. 500 dummy amount
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Dummy Payment</title>
</head>
<body>
<h2>Dummy Payment Page</h2>
<p>Amount to pay: Rs. <?= $amount ?></p>

<form method="post" action="dummy_payment_process.php">
    <input type="hidden" name="space_id" value="<?= $space_id ?>">
    <input type="hidden" name="amount" value="<?= $amount ?>">
    <button type="submit">Pay Now (Dummy)</button>
</form>
</body>
</html>
