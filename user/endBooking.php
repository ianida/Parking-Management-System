<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

session_start();
include('include/dbcon.php');
require 'calculate_fare.php';

// PHPMailer includes
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require_once '../PHPMailer/PHPMailer.php';
require_once '../PHPMailer/SMTP.php';
require_once '../PHPMailer/Exception.php';

header('Content-Type: application/json');

// Set timezone to Nepal
date_default_timezone_set('Asia/Kathmandu');

if (!isset($_SESSION['id'])) {
    echo json_encode(['status'=>'error','message'=>'User session not found.']);
    exit();
}

if (!isset($_POST['space_id']) || empty($_POST['space_id'])) {
    echo json_encode(['status'=>'error','message'=>'Space ID is missing.']);
    exit();
}

$space_id = (int)$_POST['space_id'];
$user_id = (int)$_SESSION['id'];

// Get all active bookings for this user and space
$sql = "SELECT userSpaceId, StartTime, vehicleCategoryId 
        FROM userspace 
        WHERE userid=? AND spaceid=? AND EndTime IS NULL";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $user_id, $space_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['status'=>'error','message'=>'No active booking found']);
    exit();
}

$totalFare = 0;
while ($booking = $result->fetch_assoc()) {
    $user_space_id = $booking['userSpaceId'];
    $start_time = $booking['StartTime'];
    $vehicle_category_id = $booking['vehicleCategoryId'];

    // Get vehicle type
    $stmt_vehicle = $conn->prepare("SELECT VehicleCat FROM tblcategory WHERE ID=?");
    $stmt_vehicle->bind_param("i", $vehicle_category_id);
    $stmt_vehicle->execute();
    $stmt_vehicle->bind_result($vehicle_type);
    $stmt_vehicle->fetch();
    $stmt_vehicle->close();

    $end_time = date('Y-m-d H:i:s'); // Nepal time
    $fare = calculateParkingFare($start_time, $end_time, $vehicle_type);
    $commission = calculateComission($fare);

    // Update userspace
    $update_sql = "UPDATE userspace SET EndTime=?, Fare=?, status='0' WHERE userSpaceId=?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("sdi", $end_time, $fare, $user_space_id);
    $update_stmt->execute();
    $update_stmt->close();


    // Option 1: Old Way (Negative Commission)
    // Update user balance (adds negative commission → reduces balance)
    $balance_sql = "UPDATE users SET balance = balance + ? WHERE id=?";
    $bal_stmt = $conn->prepare($balance_sql);
    $bal_stmt->bind_param("di", $commission, $user_id); // commission is negative
    $bal_stmt->execute();
    $bal_stmt->close();


    // Option 2: Store negative balance so user owes money
    // $netPayment = $fare - $commission;
    // $balance_sql = "UPDATE users SET balance = balance - ? WHERE id=?";
    // $bal_stmt = $conn->prepare($balance_sql);
    // $bal_stmt->bind_param("di", $netPayment, $user_id);
    // $bal_stmt->execute();
    // $bal_stmt->close();

    $totalFare += $fare;


    
}


// Update space status to unbooked
$space_sql = "UPDATE space SET status='0' WHERE space_id=?";
$space_stmt = $conn->prepare($space_sql);
$space_stmt->bind_param("i", $space_id);
$space_stmt->execute();
$space_stmt->close();

// Send JSON response immediately
ob_clean();
echo json_encode(['status'=>'success','fare'=>$totalFare]);
$conn->close();
flush(); // send response immediately

// Emails sent asynchronously
try {
    $conn2 = new mysqli('localhost', 'root', '', 'parkingdb');

    // Owner info
    $stmtOwner = $conn2->prepare("SELECT u.email, u.name FROM users u JOIN space s ON u.id = s.user_id WHERE s.space_id=?");
    $stmtOwner->bind_param("i", $space_id);
    $stmtOwner->execute();
    $owner = $stmtOwner->get_result()->fetch_assoc();
    $stmtOwner->close();

    // User info
    $stmtUser = $conn2->prepare("SELECT email, name FROM users WHERE id=?");
    $stmtUser->bind_param("i", $user_id);
    $stmtUser->execute();
    $user = $stmtUser->get_result()->fetch_assoc();
    $stmtUser->close();
    $conn2->close();

    // Mail to owner
    $mailOwner = new PHPMailer(true);
    $mailOwner->isSMTP();
    $mailOwner->Host = 'smtp.gmail.com';
    $mailOwner->SMTPAuth = true;
    $mailOwner->Username = 'middlebreakfast03@gmail.com';
    $mailOwner->Password = 'gghobgtyblqtoujt';
    $mailOwner->SMTPSecure = 'tls';
    $mailOwner->Port = 587;
    $mailOwner->setFrom('middlebreakfast03@gmail.com','Parking Management System');
    $mailOwner->addAddress($owner['email'],$owner['name']);
    $mailOwner->isHTML(false);
    $mailOwner->Subject = "Booking Ended for Your Parking Space";
    $mailOwner->Body = "Dear {$owner['name']},\n\nThe booking for your parking space has been ended by {$user['name']}.\n\nRegards,\nParking Management System";
    $mailOwner->send();

    // Mail to user
    $mailUser = new PHPMailer(true);
    $mailUser->isSMTP();
    $mailUser->Host = 'smtp.gmail.com';
    $mailUser->SMTPAuth = true;
    $mailUser->Username = 'middlebreakfast03@gmail.com';
    $mailUser->Password = 'gghobgtyblqtoujt';
    $mailUser->SMTPSecure = 'tls';
    $mailUser->Port = 587;
    $mailUser->setFrom('middlebreakfast03@gmail.com','Parking Management System');
    $mailUser->addAddress($user['email'],$user['name']);
    $mailUser->isHTML(false);
    $mailUser->Subject = "Booking Ended Confirmation";
    $mailUser->Body = "Dear {$user['name']},\n\nYou have successfully ended the booking for the parking space.\n\nRegards,\nParking Management System";
    $mailUser->send();

} catch(Exception $e){
    error_log("Email error: ".$e->getMessage());
}

exit();
?>