<?php
session_start();
include('../config/function.php');

header('Content-Type: application/json');

if (!isset($_SESSION['id'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit();
}

$userId = $_SESSION['id'];
$vehicleId = intval($_POST['vehicleId'] ?? 0);

if ($vehicleId <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid vehicle ID']);
    exit();
}

$stmt = $conn->prepare("DELETE FROM tblvehicle WHERE ID = ? AND UserId = ?");
$stmt->bind_param("ii", $vehicleId, $userId);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Vehicle deleted successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error deleting vehicle: ' . $stmt->error]);
}
$stmt->close();
?>
