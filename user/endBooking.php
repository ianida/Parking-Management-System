<?php
session_start();
include('include/dbcon.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once '../PHPMailer/PHPMailer.php';
require_once '../PHPMailer/SMTP.php';
require_once '../PHPMailer/Exception.php';

if (!isset($_SESSION['id'])) {
    die('User session not found. Please login again.');
}

if (!isset($_POST['space_id']) || empty($_POST['space_id'])) {
    die('Space ID is missing.');
}

$space_id = (int)$_POST['space_id'];
$user_id = (int)$_SESSION['id'];

// Function to send email using PHPMailer
function sendEmail($to, $toName, $subject, $body) {
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'middlebreakfast03@gmail.com';     // Your SMTP username
        $mail->Password = 'gghobgtyblqtoujt';               // SMTP password or app password
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('middlebreakfast03@gmail.com', 'Parking Management System');
        $mail->addAddress($to, $toName);

        $mail->isHTML(false);
        $mail->Subject = $subject;
        $mail->Body    = $body;

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Mail Error: " . $mail->ErrorInfo);
        return false;
    }
}

// Update space status to 0 (available)
$query = "UPDATE space SET status = '0' WHERE space_id = ?";
if ($stmt = $conn->prepare($query)) {
    $stmt->bind_param("i", $space_id);
    if (!$stmt->execute()) {
        die("Failed to update space status: " . $stmt->error);
    }
    $stmt->close();
} else {
    die("Failed to prepare update statement: " . $conn->error);
}

// Delete booking record only for this user and space
$deleteQuery = "DELETE FROM userspace WHERE userid = ? AND spaceid = ?";
if ($delStmt = $conn->prepare($deleteQuery)) {
    $delStmt->bind_param("ii", $user_id, $space_id);
    if (!$delStmt->execute()) {
        die("Failed to delete booking record: " . $delStmt->error);
    }
    $delStmt->close();
} else {
    die("Failed to prepare delete statement: " . $conn->error);
}

// Get owner info for email
$sqlOwner = "SELECT u.email, u.name FROM users u JOIN space s ON u.id = s.user_id WHERE s.space_id = ?";
if ($stmtOwner = $conn->prepare($sqlOwner)) {
    $stmtOwner->bind_param("i", $space_id);
    $stmtOwner->execute();
    $resultOwner = $stmtOwner->get_result();
    $owner = $resultOwner->fetch_assoc();
    $stmtOwner->close();
} else {
    die("Failed to prepare owner query: " . $conn->error);
}

// Get user info for email
$sqlUser = "SELECT email, name FROM users WHERE id = ?";
if ($stmtUser = $conn->prepare($sqlUser)) {
    $stmtUser->bind_param("i", $user_id);
    $stmtUser->execute();
    $resultUser = $stmtUser->get_result();
    $user = $resultUser->fetch_assoc();
    $stmtUser->close();
} else {
    die("Failed to prepare user query: " . $conn->error);
}

// Prepare email content
$subjectOwner = "Booking Ended for Your Parking Space";
$messageOwner = "Dear " . $owner['name'] . ",\n\nThe booking for your parking space has been ended by " . $user['name'] . ".\n\nRegards,\nParking Management System";

$subjectUser = "Booking Ended Confirmation";
$messageUser = "Dear " . $user['name'] . ",\n\nYou have successfully ended the booking for the parking space.\n\nRegards,\nParking Management System";

// Send emails
sendEmail($owner['email'], $owner['name'], $subjectOwner, $messageOwner);
sendEmail($user['email'], $user['name'], $subjectUser, $messageUser);

$_SESSION['message'] = "Booking Ended Successfully.";
$_SESSION['message_type'] = "success";

$conn->close();

header("Location: bookedspace.php");
exit();
