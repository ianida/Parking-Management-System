<?php
session_start();
include('../config/function.php');
include('include/header.php');

if (!isset($_SESSION['id'])) {
    header("Location: ../loginform.php");
    exit();
}

$userId = $_SESSION['id'];
$vehicleId = intval($_GET['id'] ?? 0);
$message = "";

if ($vehicleId <= 0) {
    echo "Invalid Vehicle ID.";
    include('include/footer.php');
    exit();
}

// Fetch vehicle details to edit (only if belongs to user)
$stmt = $conn->prepare("SELECT VehicleModel, VehicleCategory, VehicleCompanyname, Color, RegistrationNumber FROM tblvehicle WHERE ID = ? AND UserId = ?");
$stmt->bind_param("ii", $vehicleId, $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Vehicle not found or you do not have permission.";
    include('include/footer.php');
    exit();
}

$vehicle = $result->fetch_assoc();
$stmt->close();

// Fetch categories from tblcategory for dropdown
$categories = [];
$res = $conn->query("SELECT VehicleCat FROM tblcategory ORDER BY VehicleCat ASC");
if ($res && $res->num_rows > 0) {
    while ($row = $res->fetch_assoc()) {
        $categories[] = $row['VehicleCat'];
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $vehicleModel = trim($_POST['vehicle_model'] ?? '');
    $vehicleCategory = trim($_POST['vehicle_category'] ?? '');
    $vehicleCompany = trim($_POST['vehicle_company'] ?? '');
    $color = trim($_POST['color'] ?? '');
    $registrationNumber = trim($_POST['registration_number'] ?? '');

    // All fields required validation
    if ($vehicleModel === '' || $vehicleCategory === '' || $vehicleCompany === '' || $color === '' || $registrationNumber === '') {
        $message = "All fields are required.";
    } else {
        $stmt = $conn->prepare("UPDATE tblvehicle SET VehicleModel=?, VehicleCategory=?, VehicleCompanyname=?, Color=?, RegistrationNumber=? WHERE ID=? AND UserId=?");
        $stmt->bind_param("sssssii", $vehicleModel, $vehicleCategory, $vehicleCompany, $color, $registrationNumber, $vehicleId, $userId);

        if ($stmt->execute()) {
            $message = "Vehicle updated successfully.";
            // Refresh vehicle data
            $vehicle['VehicleModel'] = $vehicleModel;
            $vehicle['VehicleCategory'] = $vehicleCategory;
            $vehicle['VehicleCompanyname'] = $vehicleCompany;
            $vehicle['Color'] = $color;
            $vehicle['RegistrationNumber'] = $registrationNumber;
        } else {
            $message = "Error updating vehicle: " . $stmt->error;
        }
        $stmt->close();
    }
}
?>

<div class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
  <div class="container-fluid py-4">
    <h2>Edit Vehicle</h2>

    <?php if ($message): ?>
      <div class="alert alert-info"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <form method="post" action="">
      <div class="mb-3">
        <label for="vehicle_model" class="form-label">Vehicle Model *</label>
        <input type="text" id="vehicle_model" name="vehicle_model" class="form-control" required value="<?= htmlspecialchars($vehicle['VehicleModel']) ?>">
      </div>

      <div class="mb-3">
        <label for="vehicle_category" class="form-label">Vehicle Category *</label>
        <select id="vehicle_category" name="vehicle_category" class="form-control" required>
          <option value="">Select Vehicle Category</option>
          <?php foreach ($categories as $cat): ?>
            <option value="<?= htmlspecialchars($cat) ?>" <?= ($vehicle['VehicleCategory'] === $cat) ? 'selected' : '' ?>>
              <?= htmlspecialchars($cat) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="mb-3">
        <label for="vehicle_company" class="form-label">Vehicle Company Name *</label>
        <input type="text" id="vehicle_company" name="vehicle_company" class="form-control" required value="<?= htmlspecialchars($vehicle['VehicleCompanyname']) ?>">
      </div>

      <div class="mb-3">
        <label for="color" class="form-label">Color *</label>
        <input type="text" id="color" name="color" class="form-control" required value="<?= htmlspecialchars($vehicle['Color']) ?>">
      </div>

      <div class="mb-3">
        <label for="registration_number" class="form-label">Registration Number *</label>
        <input type="text" id="registration_number" name="registration_number" class="form-control" required value="<?= htmlspecialchars($vehicle['RegistrationNumber']) ?>">
      </div>

      <button type="submit" class="btn btn-primary">Update Vehicle</button>
      <a href="manage_vehicles.php" class="btn btn-secondary">Back</a>
    </form>
  </div>
</div>

<?php include('include/footer.php'); ?>
