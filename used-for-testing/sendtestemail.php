<?php
session_start();

require '../PHPMailer/PHPMailer.php';
require '../PHPMailer/SMTP.php';
require '../PHPMailer/Exception.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $to_email = trim($_POST['email']);

    if (filter_var($to_email, FILTER_VALIDATE_EMAIL)) {
        $mail = new PHPMailer\PHPMailer\PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'middlebreakfast03@gmail.com';  // your Gmail
            $mail->Password = 'gghobgtyblqtoujt';             // your Gmail app password
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('middlebreakfast03@gmail.com', 'Parking System');
            $mail->addAddress($to_email);

            $mail->isHTML(true);
            $mail->Subject = 'Test Email from PHPMailer';
            $mail->Body = '<h3>This is a test email sent using PHPMailer and Gmail SMTP.</h3>';

            $mail->send();
            echo "<p style='color:green;'>Email sent successfully to $to_email</p>";
        } catch (PHPMailer\PHPMailer\Exception $e) {
            echo "<p style='color:red;'>Mailer Error: {$mail->ErrorInfo}</p>";
        }
    } else {
        echo "<p style='color:red;'>Invalid email address.</p>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Send Test Email</title>
</head>
<body>
    <h2>Send Test Email with PHPMailer</h2>
    <form method="POST" action="">
        <label>Enter recipient email:</label><br>
        <input type="email" name="email" required>
        <button type="submit">Send Email</button>
    </form>
</body>
</html>
