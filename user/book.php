<?php
session_start();
include('../config/function.php');
include('include/dbcon.php');


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once '../PHPMailer/PHPMailer.php';
require_once '../PHPMailer/SMTP.php';
require_once '../PHPMailer/Exception.php';

// Email function
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

// Check login
if (!isset($_SESSION['id'])) {
    header("Location: ../loginform.php");
    exit();
}

$userId = $_SESSION['id'];
$space_id = intval($_POST['space_id'] ?? $_GET['space_id'] ?? 0);
if ($space_id <= 0) die("Invalid space selected.");

// Fetch space details
$stmtSpace = $conn->prepare("SELECT lat, lng, location, vehicletype FROM space WHERE space_id = ?");
$stmtSpace->bind_param("i", $space_id);
$stmtSpace->execute();
$space = $stmtSpace->get_result()->fetch_assoc();
$stmtSpace->close();
if (!$space) die("Selected space does not exist.");

$message = "";
$error = "";

// Booking logic
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['vehicle_id'])) {
    $vehicle_id = intval($_POST['vehicle_id']);

    // Check vehicle belongs to user
    $stmtVehicle = $conn->prepare("SELECT ID, VehicleCategory FROM tblvehicle WHERE ID=? AND UserId=?");
    $stmtVehicle->bind_param("ii", $vehicle_id, $userId);
    $stmtVehicle->execute();
    $vehicle = $stmtVehicle->get_result()->fetch_assoc();
    $stmtVehicle->close();

    if (!$vehicle) {
        $error = "Invalid vehicle selected.";
    } else {
        // Update space status
        $stmtUpdateSpace = $conn->prepare("UPDATE space SET status=1 WHERE space_id=?");
        $stmtUpdateSpace->bind_param("i", $space_id);
        $stmtUpdateSpace->execute();
        $stmtUpdateSpace->close();

        // Generate unique parking number
        do {
            $parkingNumber = rand(1, 999);
            $stmtCheckPN = $conn->prepare("SELECT userSpaceId FROM userspace WHERE ParkingNumber=? AND status=1");
            $stmtCheckPN->bind_param("i", $parkingNumber);
            $stmtCheckPN->execute();
            $stmtPNResult = $stmtCheckPN->get_result();
            $stmtCheckPN->close();
        } while ($stmtPNResult->num_rows > 0);

        $start_time = date('Y-m-d H:i:s');

        // Get vehicleCategoryId from tblcategory
        $vehicleCategoryId = null;
        $stmtCat = $conn->prepare("SELECT ID FROM tblcategory WHERE VehicleCat=?");
        $stmtCat->bind_param("s", $vehicle['VehicleCategory']);
        $stmtCat->execute();
        $stmtCat->bind_result($vehicleCategoryId);
        $stmtCat->fetch();
        $stmtCat->close();

        // Insert booking
        $stmtInsert = $conn->prepare("INSERT INTO userspace(userid, spaceid, vehicle_id, status, StartTime, EndTime, ParkingNumber, Fare, vehicleCategoryId) VALUES(?, ?, ?, 1, ?, NULL, ?, 0, ?)");
        $stmtInsert->bind_param("iiisii", $userId, $space_id, $vehicle_id, $start_time, $parkingNumber, $vehicleCategoryId);
        if ($stmtInsert->execute()) {
            $_SESSION['start_time'] = $start_time;

            // Send emails
            $stmtOwner = $conn->prepare("SELECT u.email, u.name FROM users u JOIN space s ON u.id = s.user_id WHERE s.space_id=?");
            $stmtOwner->bind_param("i", $space_id);
            $stmtOwner->execute();
            $owner = $stmtOwner->get_result()->fetch_assoc();
            $stmtOwner->close();

            $stmtUser = $conn->prepare("SELECT email, name FROM users WHERE id=?");
            $stmtUser->bind_param("i", $userId);
            $stmtUser->execute();
            $user = $stmtUser->get_result()->fetch_assoc();
            $stmtUser->close();

            $subjectOwner = "New booking for your parking space";
            $messageOwner = "Dear " . $owner['name'] . ",\n\nYour parking space has been booked by " . $user['name'] . ".\n\nRegards,\nParking Management System";

            $subjectUser = "Booking Confirmation";
            $messageUser = "Dear " . $user['name'] . ",\n\nThank you for booking the parking space.\n\nRegards,\nParking Management System";

            sendEmail($owner['email'], $owner['name'], $subjectOwner, $messageOwner);
            sendEmail($user['email'], $user['name'], $subjectUser, $messageUser);

            $message = "Booking successful! Emails have been sent.";
        } else {
            $error = "Error inserting booking record: " . $stmtInsert->error;
        }
        $stmtInsert->close();
    }
}

// Fetch user's vehicles
$stmtVehicles = $conn->prepare("SELECT ID, VehicleModel, VehicleCategory, RegistrationNumber FROM tblvehicle WHERE UserId=?");
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

    <?php if (!$message): ?>
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
