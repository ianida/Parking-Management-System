<?php
include('../config/function.php');
if (!isset($_SESSION['id'])) {
  header("Location:../loginform.php");
    exit();
  }
include('include/header.php');


?>
<!DOCTYPE html>
<html lang="en">
<head>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 20px;
    }
    
    h6 {
      font-size: 1.2em;
      margin-bottom: 10px;
    }

    select {
      width: 200px;
      padding: 10px;
      margin-bottom: 20px;
      border: 1px solid #ccc;
      border-radius: 5px;
      font-size: 1em;
      background-color: #f9f9f9;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
      transition: background-color 0.3s ease;
    }

    select:hover {
      background-color: #e9e9e9;
    }

    button {
      padding: 10px 20px;
      font-size: 1em;
      color: white;
      background-color: #007BFF;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
      transition: background-color 0.3s ease;
    }

    button:hover {
      background-color: #0056b3;
    }

    .results {
      margin-top: 20px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }

    table, th, td {
      border: 1px solid #ccc;
    }

    th, td {
      padding: 10px;
      text-align: left;
    }

    th {
      background-color: #f2f2f2;
    }

    tr:nth-child(even) {
      background-color: #f9f9f9;
    }

    tr:hover {
      background-color: #f1f1f1;
    }

    .location-link {
      color: #007BFF;
      text-decoration: none;
      font-weight: bold;
    }

    .location-link:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>
  
  <h6>Search for space by vehicle type:</h6>
  <form  method="POST">
    <select name="vehicle-type" id="vehicle-type" style="width:230px">
      <option>Select a vehicle category:</option>
    <?php
        $sqlVehicleCat = "SELECT VehicleCat FROM tblcategory";
        $resultVehicle = $conn->query($sqlVehicleCat);
            if ($resultVehicle->num_rows > 0) {
                // Output data of each row
                while($row = $resultVehicle->fetch_assoc()) {
                    echo '<option value="' . $row['VehicleCat'] . '">' . $row['VehicleCat'] . '</option>';
                }
            } else {
                echo '<option value="">No categories available</option>';
            }
            ?>
    </select>
    <button type="submit">Search</button>
  </form>
  <div class="results" id="results">
  <p style="color:red">
    <?php
            if (isset($_SESSION['message'])) {
              echo '<div class="message ' . $_SESSION['message_type'] . '">' . $_SESSION['message'] . '</div>';
              // Clear the message after displaying it
              unset($_SESSION['message']);
              unset($_SESSION['message_type']);
          }
      ?>
  </p>

  </div>

</body>
</html>
<?php
if (!isset($_POST['vehicle-type']) || empty($_POST['vehicle-type'])) {
  echo "<p style='color:#ff0000' >Please select a vehicle type.<p>";
  exit();
}

$vehicle_type = $_POST['vehicle-type'];

$query = "
    SELECT u.name, u.phone, s.space_id, s.lat, s.lng, s.location, s.status
        FROM users u
        JOIN space s ON u.id = s.user_id
        WHERE s.vehicletype = ? AND s.status='0'
";

$stmt = $conn->prepare($query);
$stmt->bind_param("s", $vehicle_type);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo "<h6>Spaces available for $vehicle_type:</h6>";
    echo "<table>
            <tr>
              <th>User Name</th>
              <th>Tole Name</th>
              <th>Location</th>
              <th>Remark</th>
            </tr>";
    while ($row = $result->fetch_assoc()) {
        $lat = $row['lat'];
        $lng = $row['lng'];
        $user_name = $row['name'];
        $location = $row['location'];
        $phone_number = $row['phone'];
        $status = $row['status'];
        $space_id = $row['space_id'];
        echo "<tr>
                <td>{$user_name}</td>
                <td>{$location}</td>
                <td><a href='https://www.google.com/maps/search/?api=1&query={$lat},{$lng}' target='_blank' class='location-link'>Go to the location</a></td>
                <td>
                    <form action='book.php' method='post'>
                      <input type='hidden' name='space_id' value='".$space_id."'/>
                      <button type='submit' class='book-btn' data-id='{$space_id}' data-status='{$status}'>Book the location </button>
                    </form>
                </td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "<p style='color: #ff0000'>No spaces available for the selected vehicle type.<p>";
}


    
$stmt->close();
$conn->close();
?>
