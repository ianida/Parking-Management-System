<?php
require 'config/function.php';

if (isset($_GET['token'])) {
    $token = $_GET['token'];
    $check = mysqli_query($conn, "SELECT * FROM email_verifications WHERE token='$token' LIMIT 1");
    if (mysqli_num_rows($check) > 0) {
        mysqli_query($conn, "DELETE FROM email_verifications WHERE token='$token'");
        redirect(BASE_URL . 'loginform.php', 'Email verified! You can now log in.');
    } else {
        redirect(BASE_URL . 'loginform.php', 'Invalid or expired token.');
    }
} else {
    redirect(BASE_URL . 'loginform.php', 'No token provided.');
}
?>
