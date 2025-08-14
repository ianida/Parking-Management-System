<?php
session_start();
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

include('../config/function.php');
include('include/header.php');

$userId = $_SESSION['id'];

// Prepare and execute the SQL to get balance
$sql = "SELECT balance FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

// Assign balance first
if ($result->num_rows === 1) {
    $row = $result->fetch_assoc();
    $balance = $row['balance'];
} else {
    $balance = 0;
}

// Then compute class
$balanceClass = $balance < 0 ? 'negative-balance' : 'positive-balance';
?>

<!DOCTYPE html>
<html lang="en">
<head>
<style>

* { 
    margin: 0; 
    padding: 0; 
    box-sizing: 
    border-box; 
}

body { 
    background-color: #1f1f1f; 
    font-family: Arial, sans-serif; 
    color: #fff; 
    height: 100vh; 
    display: flex; 
    margin-left: 100px; 
    align-items: center; 
}

.balance-container { 
    background-color: #f4f4f4; 
    color: #333; 
    margin-top: 60px; 
    margin-left: 60px; 
    padding: 40px 60px; 
    border-radius: 10px; 
    box-shadow: 0 0 25px rgba(0, 0, 0, 0.5); 
    text-align: center; 
    min-width: 600px; 
}

.balance-container h2 { 
    margin-bottom: 20px; 
    margin-top: 60px; 
    font-size: 1.6rem; 
}

.amount { 
    font-size: 2.5rem; 
    margin-bottom: 30px; 
}

.positive-balance { 
    color: #27ae60; 
}

.negative-balance { 
    color: rgb(224, 49, 18); 
}

.settle-btn { 
    padding: 12px 30px; 
    background-color: #27ae60; 
    border: none; 
    border-radius: 5px; 
    margin-top: 40px; 
    margin-left: 260px; 
    color: white; 
    font-size: 1rem; 
    cursor: pointer; 
    transition: background-color 0.3s ease; 
}

.settle-btn:hover { 
    background-color: #219150; 
}

</style>
</head>
<body>
<div class="main-content">
    <div class="balance-container">
        <h2>Your Current Balance </h2>
        <div class="amount <?php echo $balanceClass; ?>">
            Rs. <?php echo number_format($balance, 2); ?>
        </div>
    </div>
</div>
<form action="settle_balance.php" method="post">
    <input type="hidden" name="user_id" value="<?php echo $_SESSION['id']; ?>">
    <button type="submit" class="settle-btn">Settle Balance</button>
</form>
</body>
</html>
