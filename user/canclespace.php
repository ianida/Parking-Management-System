<?php
include('../config/function.php');
include('include/dbcon.php');

if (!isset($_SESSION['id'])) {
    header("Location: ../loginform.php");
    exit();
}

$space_id = intval($_POST['space_id']);
$user_id = $_SESSION['id'];

// Get status of the space
$stmt = $conn->prepare("SELECT status FROM space WHERE space_id=? AND user_id=?");
$stmt->bind_param("ii", $space_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Space not found or you do not have permission.";
    exit();
}

$space = $result->fetch_assoc();
$stmt->close();

if ($space['status'] == 0) {
    // Check if any bookings exist in userspace
    $stmt = $conn->prepare("SELECT COUNT(*) as cnt FROM userspace WHERE spaceid=?");
    $stmt->bind_param("i", $space_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();

    if ($row['cnt'] > 0) {
        echo "Cannot delete: There are existing bookings for this space.";
    } else {
        // Safe to delete
        $stmt = $conn->prepare("DELETE FROM space WHERE space_id=? AND user_id=?");
        $stmt->bind_param("ii", $space_id, $user_id);
        if ($stmt->execute()) {
            echo "Space removed successfully.";
            header("Location: myspace.php");
            exit();
        } else {
            echo "Error deleting space: " . $stmt->error;
        }
        $stmt->close();
    }
} else {
    echo "Booked space cannot be deleted.";
}

$conn->close();
?>
