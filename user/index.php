<?php 
include ('../config/function.php');
include('include/header.php'); 

if (!isset($_SESSION['id'])) {
  header("Location:../loginform.php");
  exit();
}

alertMessage(); 

$user_id = $_SESSION['id'];
?>

<h3>Dashboard</h3>

<br><br><h5>Your Space Information:</h5>

<div class="row">

  <div class="col-md-4 mb-4">
    <div class="card card-body p-3">
      <p class="text-sm mb-0 text-capitalize font-weight-bold">Total Space Added</p>
      <h5 class="font-weight-bolder mb-0">
        <?php
          $sql = "SELECT COUNT(*) AS space_count FROM space WHERE user_id = ?";
          $stmt = $conn->prepare($sql);
          $stmt->bind_param("i", $user_id);
          $stmt->execute();
          $result = $stmt->get_result();
          $row = $result->fetch_assoc();
          echo $row['space_count'];
          $stmt->close();
        ?>
      </h5>
    </div>
  </div>

  <div class="col-md-4 mb-4">
    <div class="card card-body p-3">
      <p class="text-sm mb-0 text-capitalize font-weight-bold">Your Booked Space</p>
      <h5 class="font-weight-bolder mb-0">
        <?php
          $sql = "SELECT COUNT(*) AS space_count FROM space WHERE user_id = ? AND status='1'";
          $stmt = $conn->prepare($sql);
          $stmt->bind_param("i", $user_id);
          $stmt->execute();
          $result = $stmt->get_result();
          $row = $result->fetch_assoc();
          echo $row['space_count'];
          $stmt->close();
        ?>
      </h5>
    </div>
  </div>

  <div class="col-md-4 mb-4">
    <div class="card card-body p-3">
      <p class="text-sm mb-0 text-capitalize font-weight-bold">Unbooked Space</p>
      <h5 class="font-weight-bolder mb-0">
        <?php
          $sql = "SELECT COUNT(*) AS space_count FROM space WHERE user_id = ? AND status='0'";
          $stmt = $conn->prepare($sql);
          $stmt->bind_param("i", $user_id);
          $stmt->execute();
          $result = $stmt->get_result();
          $row = $result->fetch_assoc();
          echo $row['space_count'];
          $stmt->close();
        ?>
      </h5>
    </div>
  </div>

</div>

<br><br><h5>Your Booking Information:</h5>

<div class="row">

  <div class="col-md-4 mb-4">
    <div class="card card-body p-3">
      <p class="text-sm mb-0 text-capitalize font-weight-bold">Booked Space</p>
      <h5 class="font-weight-bolder mb-0">
        <?php
          $sql = "SELECT COUNT(*) AS space_count FROM userspace WHERE userid = ? AND status='1'";
          $stmt = $conn->prepare($sql);
          $stmt->bind_param("i", $user_id);
          $stmt->execute();
          $result = $stmt->get_result();
          $row = $result->fetch_assoc();
          echo $row['space_count'];
          $stmt->close();
        ?>
      </h5>
    </div>
  </div>

  <div class="col-md-4 mb-4">
    <div class="card card-body p-3">
      <p class="text-sm mb-0 text-capitalize font-weight-bold">Total Vehicles</p>
      <h5 class="font-weight-bolder mb-0">
        <?php
          $sql = "SELECT COUNT(*) AS vehicle_count FROM tblvehicle WHERE UserId = ?";
          $stmt = $conn->prepare($sql);
          $stmt->bind_param("i", $user_id);
          $stmt->execute();
          $result = $stmt->get_result();
          $row = $result->fetch_assoc();
          echo $row['vehicle_count'];
          $stmt->close();
        ?>
      </h5>
    </div>
  </div>

  <div class="col-md-4 mb-4">
    <div class="card card-body p-3">
      <p class="text-sm mb-0 text-capitalize font-weight-bold">Total Fees Paid (NRs.)</p>
      <h5 class="font-weight-bolder mb-0">
        <?php
          $sql = "SELECT IFNULL(SUM(ParkingFees),0) AS total_fees FROM userspace WHERE userid = ? AND status = '0'";
          $stmt = $conn->prepare($sql);
          $stmt->bind_param("i", $user_id);
          $stmt->execute();
          $result = $stmt->get_result();
          $row = $result->fetch_assoc();
          echo number_format($row['total_fees'], 2);
          $stmt->close();
        ?>
      </h5>
    </div>
  </div>

  <div class="col-md-4 mb-4">
    <div class="card card-body p-3">
      <p class="text-sm mb-0 text-capitalize font-weight-bold">Total Bookings</p>
      <h5 class="font-weight-bolder mb-0">
        <?php
          $sql = "SELECT COUNT(*) AS booking_count FROM userspace WHERE userid = ?";
          $stmt = $conn->prepare($sql);
          $stmt->bind_param("i", $user_id);
          $stmt->execute();
          $result = $stmt->get_result();
          $row = $result->fetch_assoc();
          echo $row['booking_count'];
          $stmt->close();
        ?>
      </h5>
    </div>
  </div>

  <div class="col-md-4 mb-4">
    <div class="card card-body p-3">
      <p class="text-sm mb-0 text-capitalize font-weight-bold">Last Booking Date</p>
      <h5 class="font-weight-bolder mb-0">
        <?php
          $sql = "SELECT MAX(StartTime) AS last_booking FROM userspace WHERE userid = ?";
          $stmt = $conn->prepare($sql);
          $stmt->bind_param("i", $user_id);
          $stmt->execute();
          $result = $stmt->get_result();
          $row = $result->fetch_assoc();
          echo $row['last_booking'] ? date('Y-m-d H:i', strtotime($row['last_booking'])) : 'No bookings';
          $stmt->close();

          // Close DB connection after all queries
          $conn->close();
        ?>
      </h5>
    </div>
  </div>

</div>

<?php include('include/footer.php'); ?>
