<?php
require 'config/function.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';
require 'PHPMailer/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (isset($_POST['signup'])) {
    $username = validate($_POST['username']);
    $name = validate($_POST['name']);
    $email = validate($_POST['email']);
    $phone = validate($_POST['phone']);
    $password = validate($_POST['password']);

    // Basic check for empty fields
    if ($username && $name && $email && $phone && $password) {

        // Save plain password without hashing
        $stmt = $conn->prepare("INSERT INTO users (username, name, email, phone, password, role, Created_date) VALUES (?, ?, ?, ?, ?, 'user', CURDATE())");
        $stmt->bind_param("sssss", $username, $name, $email, $phone, $password);

        if ($stmt->execute()) {
            $user_id = $stmt->insert_id;

            // Create unique token for email verification
            $token = md5(uniqid(rand(), true));

            $stmt2 = $conn->prepare("INSERT INTO email_verifications (user_id, token) VALUES (?, ?)");
            $stmt2->bind_param("is", $user_id, $token);
            $stmt2->execute();
            $stmt2->close();

            // Send verification email
            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'middlebreakfast03@gmail.com'; // your email here
                $mail->Password = 'gghobgtyblqtoujt';  // your app password here
                $mail->SMTPSecure = 'tls';
                $mail->Port = 587;

                $mail->setFrom('middlebreakfast03@gmail.com', 'Parking System');
                $mail->addAddress($email, $name);

                $mail->isHTML(true);
                $mail->Subject = 'Email Verification';

                // Use BASE_URL constant for link
                $verify_link = BASE_URL . "verify.php?token=$token";
                $mail->Body = "Hello $name,<br>Click here to verify your email: <a href='$verify_link'>$verify_link</a>";

                $mail->send();

                redirect(BASE_URL . 'loginform.php', 'Please check your email to verify your account.');
            } catch (Exception $e) {
                // Email sending failed but user is registered
                redirect(BASE_URL . 'loginform.php', 'Signup successful, but failed to send verification email.');
            }
        } else {
            redirect(BASE_URL . 'loginform.php', 'Something went wrong during signup.');
        }
        $stmt->close();
    } else {
        redirect(BASE_URL . 'loginform.php', 'All fields are required.');
    }
}
?>
