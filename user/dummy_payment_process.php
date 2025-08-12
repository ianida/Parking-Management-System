<?php
session_start();
include('include/dbcon.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once '../PHPMailer/PHPMailer.php';
require_once '../PHPMailer/SMTP.php';
require_once '../PHPMailer/Exception.php';

if (!isset($_SESSION['id'])) {
    die("Please login.");
}

$userId = $_SESSION['id'];
$space_id = intval($_POST['space_id'] ?? 0);
$amount = floatval($_POST['amount'] ?? 0);

if ($space_id <= 0 || $amount <= 0) {
    die("Invalid payment data.");
}

// Update ParkingFees in userspace for latest ended booking (status=0)
$stmt = $conn->prepare("UPDATE userspace SET ParkingFees = ? WHERE userid = ? AND spaceid = ? AND status = '0' ORDER BY StartTime DESC LIMIT 1");
$stmt->bind_param("dii", $amount, $userId, $space_id);

if ($stmt->execute()) {
    // Fetch owner email & name
    $stmtOwner = $conn->prepare("SELECT u.email, u.name FROM users u JOIN space s ON u.id = s.user_id WHERE s.space_id = ?");
    $stmtOwner->bind_param("i", $space_id);
    $stmtOwner->execute();
    $owner = $stmtOwner->get_result()->fetch_assoc();
    $stmtOwner->close();

    // Fetch user email & name
    $stmtUser = $conn->prepare("SELECT email, name FROM users WHERE id = ?");
    $stmtUser->bind_param("i", $userId);
    $stmtUser->execute();
    $user = $stmtUser->get_result()->fetch_assoc();
    $stmtUser->close();

    function sendEmail($to, $toName, $subject, $body) {
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'middlebreakfast03@gmail.com';
            $mail->Password = 'gghobgtyblqtoujt';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('middlebreakfast03@gmail.com', 'Parking Management System');
            $mail->addAddress($to, $toName);

            $mail->isHTML(false);
            $mail->Subject = $subject;
            $mail->Body = $body;

            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log("Mail error: ".$mail->ErrorInfo);
            return false;
        }
    }

    $subjectUser = "Payment Successful";
    $messageUser = "Dear ".$user['name'].",\n\nYour payment of Rs. ".$amount." for parking space has been received. Thank you!\n\nRegards,\nParking System";
    sendEmail($user['email'], $user['name'], $subjectUser, $messageUser);

    $subjectOwner = "Payment Received";
    $messageOwner = "Dear ".$owner['name'].",\n\nYou have received a payment of Rs. ".$amount." for your parking space.\n\nRegards,\nParking System";
    sendEmail($owner['email'], $owner['name'], $subjectOwner, $messageOwner);

    $_SESSION['message'] = "Payment successful. Thank you!";
    $_SESSION['message_type'] = "success";

    header("Location: bookedspace.php");
    exit();

} else {
    die("Failed to update payment: ".$stmt->error);
}
