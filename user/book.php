<?php
session_start();
include('../config/function.php');
include('include/dbcon.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once '../PHPMailer/PHPMailer.php';
require_once '../PHPMailer/SMTP.php';
require_once '../PHPMailer/Exception.php';

// Function to send email using PHPMailer
function sendEmail($to, $toName, $subject, $body) {
    $mail = new PHPMailer(true);

    try {
        // SMTP server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Your SMTP host
        $mail->SMTPAuth = true;
        $mail->Username = 'middlebreakfast03@gmail.com';      // SMTP username
        $mail->Password = 'gghobgtyblqtoujt';         // SMTP password or app password
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        // Email headers and body
        $mail->setFrom('middlebreakfast03@gmail.com', 'Parking System');
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

$space_id = $_POST['space_id'];
$new_val = '1'; 
$query = "UPDATE space SET status = 1 WHERE space_id = ?";

// Prepare the statement
if ($stmt = $conn->prepare($query)) {
    // Bind parameters (s for string, i for integer)
    $stmt->bind_param("i", $space_id);
    // Execute the statement
    if ($stmt->execute()) {
        $_SESSION['message'] = "Space booked successfully.";
        $_SESSION['message_type'] = "success";

        $updateUserSpace="INSERT into userspace (userid,spaceid,status) VALUES ('$_SESSION[id]', '$space_id',1)";
        if($stmt2=$conn->prepare($updateUserSpace)){
            if($stmt2->execute()) {
                // Booking inserted successfully, now send emails

                // Get owner info
                $sqlOwner = "SELECT u.email, u.name FROM users u JOIN space s ON u.id = s.user_id WHERE s.space_id = ?";
                $stmtOwner = $conn->prepare($sqlOwner);
                $stmtOwner->bind_param("i", $space_id);
                $stmtOwner->execute();
                $resultOwner = $stmtOwner->get_result();
                $owner = $resultOwner->fetch_assoc();

                // Get user info
                $user_id = $_SESSION['id'];
                $sqlUser = "SELECT email, name FROM users WHERE id = ?";
                $stmtUser = $conn->prepare($sqlUser);
                $stmtUser->bind_param("i", $user_id);
                $stmtUser->execute();
                $resultUser = $stmtUser->get_result();
                $user = $resultUser->fetch_assoc();

                // Prepare email contents
                $subjectOwner = "New booking for your parking space";
                $messageOwner = "Dear " . $owner['name'] . ",\n\nYour parking space has been booked by " . $user['name'] . ".\n\nRegards,\nParking Management System";

                $subjectUser = "Booking Confirmation";
                $messageUser = "Dear " . $user['name'] . ",\n\nThank you for booking the parking space.\n\nRegards,\nParking Management System";

                // Send emails
                sendEmail($owner['email'], $owner['name'], $subjectOwner, $messageOwner);
                sendEmail($user['email'], $user['name'], $subjectUser, $messageUser);

                echo "<script>alert('Booking successful and emails sent.');</script>";
            } else {
                echo "Error inserting booking record: " . $stmt2->error;
            }
        } else {
            echo "Error preparing booking insert: " . $conn->error;
        }

    } else {
        $_SESSION['message'] = "Error updating record: " . $stmt->error;
        $_SESSION['message_type'] = "error";
    }
    // Close the statement
    $stmt->close();
} else {
    $_SESSION['message'] = "Error preparing statement: " . $conn->error;
    $_SESSION['message_type'] = "error";
}

// Close the connection
$conn->close();

// Redirect back to bookedspace.php
header("Location: bookedspace.php");
exit();
?>
