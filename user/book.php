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

if (!isset($_SESSION['id'])) {
    header("Location: ../loginform.php");
    exit();
}

$userId = $_SESSION['id'];

// Get space_id from POST or GET
$space_id = intval($_POST['space_id'] ?? $_GET['space_id'] ?? 0);
if ($space_id <= 0) {
    echo "<div class='alert alert-danger p-3'>Invalid space selected.</div>";
    exit();
}

// Fetch space info (optional)
$stmtSpace = $conn->prepare("SELECT lat, lng, location, vehicletype FROM space WHERE space_id = ?");
$stmtSpace->bind_param("i", $space_id);
$stmtSpace->execute();
$resultSpace = $stmtSpace->get_result();

if ($resultSpace->num_rows === 0) {
    echo "<div class='alert alert-danger p-3'>Selected space does not exist.</div>";
    exit();
}

$space = $resultSpace->fetch_assoc();
$stmtSpace->close();

$message = "";
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['vehicle_id'])) {
    $vehicle_id = intval($_POST['vehicle_id']);

    // Validate vehicle belongs to user
    $stmtCheckVehicle = $conn->prepare("SELECT ID FROM tblvehicle WHERE ID = ? AND UserId = ?");
    $stmtCheckVehicle->bind_param("ii", $vehicle_id, $userId);
    $stmtCheckVehicle->execute();
    $resCheck = $stmtCheckVehicle->get_result();

    if ($resCheck->num_rows === 0) {
        $error = "Invalid vehicle selected.";
    } else {
        // Update space status = 1 (booked)
        $updateSpaceQuery = "UPDATE space SET status = 1 WHERE space_id = ?";
        $stmtUpdateSpace = $conn->prepare($updateSpaceQuery);
        $stmtUpdateSpace->bind_param("i", $space_id);

        if ($stmtUpdateSpace->execute()) {
            // Insert booking into userspace with status=1, StartTime=NOW(), EndTime=NULL, ParkingFees=0
            $insertBooking = "INSERT INTO userspace (userid, spaceid, vehicle_id, status, StartTime, EndTime, ParkingFees) VALUES (?, ?, ?, '1', NOW(), NULL, 0)";
            $stmtInsert = $conn->prepare($insertBooking);
            $stmtInsert->bind_param("iii", $userId, $space_id, $vehicle_id);

            if ($stmtInsert->execute()) {
                // Send emails
                
                // Owner info
                $sqlOwner = "SELECT u.email, u.name FROM users u JOIN space s ON u.id = s.user_id WHERE s.space_id = ?";
                $stmtOwner = $conn->prepare($sqlOwner);
                $stmtOwner->bind_param("i", $space_id);
                $stmtOwner->execute();
                $resultOwner = $stmtOwner->get_result();
                $owner = $resultOwner->fetch_assoc();
                $stmtOwner->close();

                // User info
                $sqlUser = "SELECT email, name FROM users WHERE id = ?";
                $stmtUser = $conn->prepare($sqlUser);
                $stmtUser->bind_param("i", $userId);
                $stmtUser->execute();
                $resultUser = $stmtUser->get_result();
                $user = $resultUser->fetch_assoc();
                $stmtUser->close();

                // Prepare email content
                $subjectOwner = "New booking for your parking space";
                $messageOwner = "Dear " . $owner['name'] . ",\n\nYour parking space has been booked by " . $user['name'] . ".\n\nRegards,\nParking Management System";

                $subjectUser = "Booking Confirmation";
                $messageUser = "Dear " . $user['name'] . ",\n\nThank you for booking the parking space.\n\nRegards,\nParking Management System";

                // Send emails
                sendEmail($owner['email'], $owner['name'], $subjectOwner, $messageOwner);
                sendEmail($user['email'], $user['name'], $subjectUser, $messageUser);

                $message = "Booking successful! Emails have been sent.";
            } else {
                $error = "Error inserting booking record: " . $stmtInsert->error;
            }
            $stmtInsert->close();
        } else {
            $error = "Error updating space status: " . $stmtUpdateSpace->error;
        }
        $stmtUpdateSpace->close();
    }
    $stmtCheckVehicle->close();
}

// Fetch user's vehicles for dropdown
$stmtVehicles = $conn->prepare("SELECT ID, VehicleModel, VehicleCategory, RegistrationNumber FROM tblvehicle WHERE UserId = ?");
$stmtVehicles->bind_param("i", $userId);
$stmtVehicles->execute();
$resultVehicles = $stmtVehicles->get_result();

include('include/header.php');
?>

<div class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
  <div class="container-fluid py-4">
    <h2 class="mb-4">Book Parking Space</h2>

    <p><strong>Location:</strong> <?= htmlspecialchars($space['location']) ?></p>
    <p><strong>Vehicle Type Allowed:</strong> <?= htmlspecialchars($space['vehicletype']) ?></p>

    <?php if ($message): ?>
      <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
      <a href="bookedspace.php" class="btn btn-primary">View My Bookings</a>
    <?php elseif ($error): ?>
      <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php if (!$message): // Show booking form only if not booked ?>
      <?php if ($resultVehicles->num_rows === 0): ?>
        <div class="alert alert-warning">You have no registered vehicles. Please <a href="add_vehicle.php">add a vehicle</a> first.</div>
      <?php else: ?>
        <form method="post" action="book.php">
          <input type="hidden" name="space_id" value="<?= $space_id ?>">
          <div class="mb-3">
            <label for="vehicle_id" class="form-label">Select Your Vehicle</label>
            <select name="vehicle_id" id="vehicle_id" class="form-select" required>
              <option value="" selected disabled>Select a vehicle</option>
              <?php while ($v = $resultVehicles->fetch_assoc()): ?>
                <option value="<?= $v['ID'] ?>">
                  <?= htmlspecialchars($v['VehicleModel']) ?> (<?= htmlspecialchars($v['VehicleCategory']) ?>) - Reg#: <?= htmlspecialchars($v['RegistrationNumber']) ?>
                </option>
              <?php endwhile; ?>
            </select>
          </div>
          <button type="submit" class="btn btn-success">Confirm Booking</button>
        </form>
      <?php endif; ?>
    <?php endif; ?>
  </div>
</div>

<?php
$stmtVehicles->close();
include('include/footer.php');
$conn->close();
?>
