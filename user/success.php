
<?php
session_start();
require '../config/function.php';

if (!isset($_GET['pidx'])) {
    die("Payment ID missing from Khalti callback.");
}

$payment_id = (int)$_GET['pidx'];

//Get payment info
// $stmt = $conn->prepare("SELECT amount, user_id, owner_id, commission FROM tblpayment WHERE payment_id=? AND paid_status='pending'");
// $stmt->bind_param("i", $payment_id);
// $stmt->execute();
// $result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Payment already processed or not found.");
}

$payment = $result->fetch_assoc();
$stmt->close();

// 1. update user balance 
$stmt = $conn->prepare("UPDATE users SET balance = 0 WHERE id=?");
$stmt->bind_param("i", $_SESSION['id']);
$stmt->execute();
$stmt->close();

// 1. Mark payment as paid
$stmt = $conn->prepare("UPDATE tblpayment SET paid_status='paid', payment_method='khalti', paid_time=NOW() WHERE space_user_id=?");
$stmt->bind_param("i", $_SESSION['id']);
$stmt->execute();
$stmt->close();



echo "<h1>Payment Successful!</h1>";
// echo "<p>Amount Paid: Rs. " . number_format($payment['amount'], 2) . "</p>";
// echo "<p>Commission: Rs. " . number_format($payment['commission'], 2) . "</p>";
// echo "<p>Owner Earned: Rs. " . number_format($owner_earnings, 2) . "</p>";
echo "<p><a href='userbalance.php'>Back to Balance</a></p>";



?>