<?php 
session_start();
include('../config/function.php');
include_once('../config/dbcon.php');
include('include/header.php'); 

if (!isset($_SESSION['id'])) {
  header("Location:../loginform.php");
  exit();
}

$user_id = $_SESSION['id'];
$username = $_SESSION['loggedInUser']['name']; // gets username from session
?>

<!-- Alert Message -->
<?php alertMessage(); ?>

<!-- Hi! username -->
<h5 style="margin-bottom:10px; color:#555;">Hi! <?php echo htmlspecialchars($username); ?></h5>

<!-- Dashboard -->
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
          $sql = "SELECT IFNULL(SUM(Fare),0) AS total_fees FROM userspace WHERE userid = ? AND status = '0'";
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
      <p class="text-sm mb-0 text-capitalize font-weight-bold">Total Commission (NRs.)</p>
      <h5 class="font-weight-bolder mb-0">
        <?php
          $sql = "SELECT IFNULL(SUM(Fare*0.15),0) AS total_commission FROM userspace WHERE userid = ? AND status = '0'";
          $stmt = $conn->prepare($sql);
          $stmt->bind_param("i", $user_id);
          $stmt->execute();
          $result = $stmt->get_result();
          $row = $result->fetch_assoc();
          echo number_format($row['total_commission'], 2);
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

          $conn->close();
        ?>
      </h5>
    </div>
  </div>

</div>

<?php include('include/footer.php'); ?>


<!-- fade-out alert script -->
<script>
window.addEventListener('DOMContentLoaded', (event) => {
    const alertBox = document.getElementById('alert-msg');
    if(alertBox){
        setTimeout(() => {
            alertBox.style.transition = "opacity 0.8s"; 
            alertBox.style.opacity = '0'; 
            setTimeout(() => alertBox.remove(), 800);
        }, 1500); 
    }
});
</script>
