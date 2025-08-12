<?php
session_start();
include('../config/function.php');
if (!isset($_SESSION['id'])) {
    header("Location: ../loginform.php");
    exit();
}
include('include/header.php');

$userId = $_SESSION['id'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <title>Search Parking Space</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 20px;
      background-color: #f8f9fa;
    }

    .container {
      max-width: 900px;
      background: white;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 3px 10px rgb(0 0 0 / 0.2);
    }

    h6 {
      font-size: 1.4rem;
      margin-bottom: 20px;
    }

    select {
      width: 100%;
      max-width: 300px;
      margin-bottom: 15px;
    }

    .location-link {
      color: #0d6efd;
      font-weight: 600;
      text-decoration: none;
    }

    .location-link:hover {
      text-decoration: underline;
    }

    table th,
    table td {
      vertical-align: middle !important;
    }
    .btn-success {
  font-weight: 600;
  padding: 0.5rem 1.25rem;
  font-size: 1rem;
  transition: background-color 0.3s ease;
}

.btn-success:hover {
  background-color: #198754; /* Bootstrap's hover green */
}

  </style>
</head>

<body>
  <div class="container">
    <h6>Search for space by vehicle type:</h6>
    <form method="POST" class="row g-3 align-items-center">
      <div class="col-auto">
        <select name="vehicle_type" id="vehicle_type" class="form-select" required>
          <option value="" selected disabled>Select a vehicle category</option>
          <?php
          $sqlVehicleCat = "SELECT VehicleCat FROM tblcategory";
          $resultVehicle = $conn->query($sqlVehicleCat);
          if ($resultVehicle && $resultVehicle->num_rows > 0) {
              while ($row = $resultVehicle->fetch_assoc()) {
                  $selected = (isset($_POST['vehicle_type']) && $_POST['vehicle_type'] === $row['VehicleCat']) ? "selected" : "";
                  echo '<option value="' . htmlspecialchars($row['VehicleCat']) . '" ' . $selected . '>' . htmlspecialchars($row['VehicleCat']) . '</option>';
              }
          } else {
              echo '<option value="" disabled>No categories available</option>';
          }
          ?>
        </select>
      </div>
      <div class="col-auto">
        <button type="submit" class="btn btn-primary">Search</button>
      </div>
    </form>

    <div class="results mt-4">
      <?php
      if ($_SERVER['REQUEST_METHOD'] === 'POST') {
          $vehicle_type = trim($_POST['vehicle_type'] ?? '');
          if (empty($vehicle_type)) {
              echo '<div class="alert alert-danger">Please select a vehicle type.</div>';
          } else {
              // Updated query: exclude current user's own spaces
              $query = "SELECT u.name, u.phone, s.space_id, s.lat, s.lng, s.location, s.status 
                        FROM users u 
                        JOIN space s ON u.id = s.user_id 
                        WHERE s.vehicletype = ? AND s.status = '0' AND s.user_id != ?";

              $stmt = $conn->prepare($query);
              $stmt->bind_param("si", $vehicle_type, $userId);
              $stmt->execute();
              $result = $stmt->get_result();

              if ($result->num_rows > 0) {
                  echo "<h6>Spaces available for <strong>" . htmlspecialchars($vehicle_type) . "</strong>:</h6>";
                  echo '<table class="table table-striped table-bordered">';
                  echo '<thead><tr>
                          <th>User Name</th>
                          <th>Phone</th>
                          <th>Tole Name</th>
                          <th>Location Link</th>
                          <th>Action</th>
                        </tr></thead><tbody>';

                  while ($row = $result->fetch_assoc()) {
                      $lat = htmlspecialchars($row['lat']);
                      $lng = htmlspecialchars($row['lng']);
                      $user_name = htmlspecialchars($row['name']);
                      $location = htmlspecialchars($row['location']);
                      $phone_number = htmlspecialchars($row['phone']);
                      $space_id = intval($row['space_id']);

                      echo "<tr>
                              <td>{$user_name}</td>
                              <td>{$phone_number}</td>
                              <td>{$location}</td>
                              <td><a href='https://www.google.com/maps/search/?api=1&query={$lat},{$lng}' target='_blank' class='location-link'>View on Map</a></td>
                              <td>
                                <form action='book.php' method='post'>
                                  <input type='hidden' name='space_id' value='{$space_id}'>
                                  <button type='submit' class='btn btn-success'>Book the location</button>
                                </form>
                              </td>
                            </tr>";
                  }
                  echo '</tbody></table>';
              } else {
                  echo '<div class="alert alert-warning">No spaces available for the selected vehicle type.</div>';
              }

              $stmt->close();
          }
      }

      $conn->close();
      ?>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
