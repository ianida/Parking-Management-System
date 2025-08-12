<?php include('include/header.php'); ?>

<?= alertMessage(); ?>

<h4>EasyPark Dashboard</h4>

<?php
// Connect to database via $conn (already included in header)

// 1. Vehicle Parked = count of userspace where EndTime IS NULL (still parked)
$queryParked = "SELECT COUNT(*) AS parked_count FROM userspace WHERE EndTime IS NULL";
$resultParked = mysqli_query($conn, $queryParked);
$rowParked = mysqli_fetch_assoc($resultParked);
$vehicleParked = $rowParked['parked_count'] ?? 0;

// 2. Vehicle Checkout = count where EndTime IS NOT NULL (checked out)
$queryCheckout = "SELECT COUNT(*) AS checkout_count FROM userspace WHERE EndTime IS NOT NULL";
$resultCheckout = mysqli_query($conn, $queryCheckout);
$rowCheckout = mysqli_fetch_assoc($resultCheckout);
$vehicleCheckout = $rowCheckout['checkout_count'] ?? 0;

// 3. Total Parking Spaces = count all rows in space table
$querySpaces = "SELECT COUNT(*) AS total_spaces FROM space";
$resultSpaces = mysqli_query($conn, $querySpaces);
$rowSpaces = mysqli_fetch_assoc($resultSpaces);
$totalSpaces = $rowSpaces['total_spaces'] ?? 0;

// 4. Total Registered Users = count all users
$queryUsers = "SELECT COUNT(*) AS total_users FROM users";
$resultUsers = mysqli_query($conn, $queryUsers);
$rowUsers = mysqli_fetch_assoc($resultUsers);
$totalUsers = $rowUsers['total_users'] ?? 0;
?>

<div class="row">
  <div class="col-md-3 mb-4">
    <div class="card card-body p-3">
      <p class="text-sm mb-0 text-capitalize font-weight-bold">Vehicle Parked</p>
      <h5 class="font-weight-bolder mb-0"><?= htmlspecialchars($vehicleParked) ?></h5>
    </div>
  </div>

  <div class="col-md-3 mb-4">
    <div class="card card-body p-3">
      <p class="text-sm mb-0 text-capitalize font-weight-bold">Vehicle Checkout</p>
      <h5 class="font-weight-bolder mb-0"><?= htmlspecialchars($vehicleCheckout) ?></h5>
    </div>
  </div>

  <div class="col-md-3 mb-4">
    <div class="card card-body p-3">
      <p class="text-sm mb-0 text-capitalize font-weight-bold">Total Parking Spaces</p>
      <h5 class="font-weight-bolder mb-0"><?= htmlspecialchars($totalSpaces) ?></h5>
    </div>
  </div>

  <div class="col-md-3 mb-4">
    <div class="card card-body p-3">
      <p class="text-sm mb-0 text-capitalize font-weight-bold">Total Registered Users</p>
      <h5 class="font-weight-bolder mb-0"><?= htmlspecialchars($totalUsers) ?></h5>
    </div>
  </div>
</div>

<?php include('include/footer.php'); ?>
