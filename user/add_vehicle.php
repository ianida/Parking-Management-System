<?php
session_start();
include('../config/function.php');  // your DB connection and helpers
include('include/header.php');      // includes head, body start, sidebar

if (!isset($_SESSION['id'])) {
    header("Location: ../loginform.php");
    exit();
}

$userId = $_SESSION['id'];
$message = "";

// Fetch categories from tblcategory to use in dropdown
$categories = [];
$result = $conn->query("SELECT VehicleCat FROM tblcategory ORDER BY VehicleCat ASC");
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row['VehicleCat'];
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $vehicleModel = trim($_POST['vehicle_model'] ?? '');
    $vehicleCategory = trim($_POST['vehicle_category'] ?? '');
    $vehicleCompany = trim($_POST['vehicle_company'] ?? '');
    $color = trim($_POST['color'] ?? '');
    $registrationNumber = trim($_POST['registration_number'] ?? '');

    // Server-side validation: all fields required
    if ($vehicleModel === '' || $vehicleCategory === '' || $vehicleCompany === '' || $color === '' || $registrationNumber === '') {
        $message = "All fields are required.";
    } else {
        $parkingNumber = 0;  // default value

        $stmt = $conn->prepare("INSERT INTO tblvehicle (UserId, Color, VehicleModel, VehicleCategory, VehicleCompanyname, RegistrationNumber, ParkingNumber) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("issssss", $userId, $color, $vehicleModel, $vehicleCategory, $vehicleCompany, $registrationNumber, $parkingNumber);

        if ($stmt->execute()) {
            $message = "Vehicle added successfully.";
        } else {
            $message = "Error adding vehicle: " . $conn->error;
        }
        $stmt->close();
    }
}
?>

<div class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
  <div class="container-fluid py-4">
    <h2 class="mb-4">Add Vehicle</h2>

    <?php if ($message): ?>
      <div class="alert alert-info"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <form method="post" action="add_vehicle.php">
      <div class="mb-3">
        <label for="vehicle_model" class="form-label">Vehicle Model *</label>
        <input type="text" id="vehicle_model" name="vehicle_model" class="form-control" required>
      </div>

      <div class="mb-3">
        <label for="vehicle_category" class="form-label">Vehicle Category *</label>
        <select id="vehicle_category" name="vehicle_category" class="form-control" required>
          <option value="">Select Vehicle Category</option>
          <?php foreach ($categories as $cat): ?>
            <option value="<?= htmlspecialchars($cat) ?>"><?= htmlspecialchars($cat) ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="mb-3">
        <label for="vehicle_company" class="form-label">Vehicle Company Name *</label>
        <input type="text" id="vehicle_company" name="vehicle_company" class="form-control" required>
      </div>

      <div class="mb-3">
        <label for="color" class="form-label">Color *</label>
        <input type="text" id="color" name="color" class="form-control" required>
      </div>

      <div class="mb-3">
        <label for="registration_number" class="form-label">Registration Number *</label>
        <input type="text" id="registration_number" name="registration_number" class="form-control" required>
      </div>

      <button type="submit" class="btn btn-primary">Add Vehicle</button>
    </form>

    <div class="mt-4">
      <a href="manage_vehicles.php" class="btn btn-secondary">Manage Vehicles</a>
    </div>
  </div>
</div>

<?php include('include/footer.php'); // closes main, body, html, includes scripts ?>
