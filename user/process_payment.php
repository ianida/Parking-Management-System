<?php
session_start();
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

require '../config/config.php';
require '../config/function.php';

if (!isset($_POST['payment_id'])) {
    die("Payment ID missing.");
}

$userId = $_SESSION['id'];

// Get payment info
$stmt = $conn->prepare("SELECT id, balance FROM users WHERE id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Payment not found or already paid.");
}

$payment = $result->fetch_assoc();
$stmt->close();

// Prepare Khalti request
$url = "https://a.khalti.com/api/v2/epayment/initiate/";

$data = [
    "return_url" => BASE_URL_KHALTI . "success.php",
    "website_url" => BASE_URL_KHALTI,
    // "amount" => $payment['balance'] * (-100), // in paisa
    "amount" => abs($payment['balance']) * 100, // convert negative balance to positive paisa
    "purchase_order_id" => $_POST['payment_id'],
    "purchase_order_name" => "Parking Payment"
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Key " . KHALTI_SECRET_KEY,
    "Content-Type: application/json"
]);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

$response = curl_exec($ch);
curl_close($ch);

$result = json_decode($response, true);

if (isset($result['payment_url'])) {
    header("Location: " . $result['payment_url']);
    exit();
} else {
    echo "<pre>";
    print_r($result);
    echo "</pre>";
}
