<?php
session_start();
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

date_default_timezone_set('Asia/Kathmandu');

// echo 'Current PHP timezone: ' . date_default_timezone_get() . '<br>';
// echo 'Current time: ' . date("F j, Y, g:i a") . '<br>';


include('../config/function.php');
include('include/header.php');

$userId = $_SESSION['id'] ?? 0;

// Fetch user's current balance
$sql = "SELECT id, balance FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $row = $result->fetch_assoc();
    $balance = $row['balance'];
} else {
    $balance = 0;
}
?>

<div class="content">
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-lg-12">
                <div class="card mb-4">
                    <!-- Card Header -->
                    <div class="card-header bg-primary text-white">
                        <strong>Your Account Balance</strong>
                    </div>

                    <!-- Card Body -->
                    <div class="card-body text-center">
                        <?php if ($balance == 0): ?>
                            <p class="text-success">You’re all settled! No pending payments.</p>

                        <?php elseif ($balance < 0): ?>
                            <p class="text-danger fw-bold">Pending Payment</p>
                            <h4 class="fw-bold text-danger">Rs. <?php echo number_format(abs($balance), 2); ?></h4>
                            <p class="text-muted">Please settle your balance to continue using services.</p>
                            <form action="process_payment.php" method="post">
                                <input type="hidden" name="user_id" value="<?php echo $userId; ?>">
                                <button type="submit" name="payment_id" value="<?php echo $row['id']; ?>"
                                        class="btn btn-danger btn-sm mt-5">Settle Balance</button>
                            </form>

                        <?php else: ?>
                            <p class="text-primary fw-bold">Available Balance</p>
                            <h4 class="fw-bold text-success">Rs. <?php echo number_format($balance, 2); ?></h4>
                            <p class="text-muted">Great! You have sufficient balance.</p>
                        <?php endif; ?>
                    </div>

                    <!-- Card Footer -->
                    <div class="card-footer text-center">
                        <small class="text-muted">Updated on <?php echo date("F j, Y, g:i a"); ?></small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('include/footer.php'); ?>
