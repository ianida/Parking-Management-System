<?php
session_start();
require '../config/function.php';

if (!isset($_GET['pidx'])) {
    die("Payment ID missing from Khalti callback.");
}

$userId = $_SESSION['id'];
$pidx   = $_GET['pidx'];

// 1. Update user's balance (clear it to 0 after payment)
$stmt = $conn->prepare("UPDATE users SET balance = 0 WHERE id=?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$stmt->close();

// 2. Mark the payment as paid in tblpayment (for report/log)
$stmt = $conn->prepare("UPDATE tblpayment 
                        SET paid_status='paid', payment_method='khalti', paid_time=NOW() 
                        WHERE space_user_id=? AND paid_status='pending'");
$stmt->bind_param("i", $userId);
$stmt->execute();
$stmt->close();

// Include your site's header
include('include/header.php');
?>

<div class="content">
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-lg-12">
                <div class="card mb-4">
                    <div class="card-header bg-success text-white">
                        <strong>Payment Successful</strong>
                    </div>
                    <div class="card-body text-center">
                        <p class="text-success fw-bold">Your pending balance has been cleared!</p>
                        <a href="userbalance.php" class="btn btn-primary mt-3">Back to Balance</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('include/footer.php'); ?>
