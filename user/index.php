<?php 
include ('../config/function.php');
include('include/header.php'); 

if (!isset($_SESSION['id'])) {
  header("Location:../loginform.php");
  exit();
}
alertMessage(); 



echo("<h3>Dashboard</h3>")
?>

<br><br><h5>Your Space Information:</h5>

<div class="row">
<div class="col-md-4 mb-4">
          <div class="card card-body p-3">
                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Total Space Added</p>
                    <h5 class="font-weight-bolder mb-0">
                      <?php
                        $user_id = $_SESSION['id'];
                        $sql = "SELECT COUNT(*) AS space_count FROM space WHERE user_id = ?";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("i", $user_id);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        
                        $row = $result->fetch_assoc();
                        $total_spaces = $row['space_count'];
                        
                        echo "$total_spaces";
                        
                        
                      ?>
                    </h5>
            </div>
          </div>

        <div class="col-md-4 mb-4">
          <div class="card card-body p-3">
                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Your Booked Space</p>
                    <h5 class="font-weight-bolder mb-0">
                    <?php
                        $user_id = $_SESSION['id'];
                        $sql = "SELECT COUNT(*) AS space_count FROM space WHERE user_id = ? AND status=1";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("i", $user_id);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        
                        $row = $result->fetch_assoc();
                        $total_spaces = $row['space_count'];
                        
                        echo "$total_spaces";
                        
                        
                      ?>
                    </h5>
            </div>
          </div>

          <div class="col-md-4 mb-4">
          <div class="card card-body p-3">
                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Unbooked Space</p>
                    <h5 class="font-weight-bolder mb-0">
                    <?php
                        $user_id = $_SESSION['id'];
                        $sql = "SELECT COUNT(*) AS space_count FROM space WHERE user_id = ? AND status=0";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("i", $user_id);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        
                        $row = $result->fetch_assoc();
                        $total_spaces = $row['space_count'];
                        
                        echo "$total_spaces";
                        
                        
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
                        $user_id = $_SESSION['id'];
                        $sql = "SELECT COUNT(*) AS space_count FROM userspace WHERE userid = ? AND status=1";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("i", $user_id);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        
                        $row = $result->fetch_assoc();
                        $total_spaces = $row['space_count'];
                        
                        echo "$total_spaces";
                        
                        // Close connection
                        $conn->close();
                      ?>
                    </h5>
            </div>
          </div>
</div>
<?php include('include/footer.php'); ?>