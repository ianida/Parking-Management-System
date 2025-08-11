<?php
include('../config/function.php');
include ('include/header.php');

// Check if user is logged in
if (!isset($_SESSION['id'])) {     
    header("Location:../loginform.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Select Location with Map Preview</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 20px;
    }
    
    h6 {
      font-size: 1.2em;
      margin-bottom: 10px;
    }

    #map {
      height: 400px;
      width: 60%;
      margin-bottom: 20px;
      border: 2px solid #ccc;
      border-radius: 10px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      position: relative;
    }

    #centerMarker {
      position: absolute;
      top: 50%;
      left: 50%;
      width: 30px;
      height: 30px;
      background: url('https://maps.gstatic.com/mapfiles/api-3/images/spotlight-poi2.png') no-repeat;
      background-size: cover;
      transform: translate(-50%, -100%); /* Adjust to make the point of the marker at the center */
      pointer-events: none; /* Make the marker non-interactive */
    }

    #controls {
      margin-top: 10px;
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

    #submit,button {
      padding: 10px 20px;
      font-size: 1em;
      color: white;
      background: #007BFF;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
      transition: background-color 0.3s ease;
    }

    button:hover {
      background-color: #0056b3;
    }

    p {
      font-size: 1em;
      margin: 5px 0;
    }
  </style>
  <script>
    let map;

    function getLocation(callback) {
      if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition((position) => {
          const lat = position.coords.latitude;
          const lng = position.coords.longitude;
          callback(lat, lng); // Pass the coordinates to the callback function
        }, () => {
          console.error("Error getting location");
        });
      } else {
        console.error("Browser doesn't support Geolocation");
      }
    }

    function updateCoordinates() {
      const center = map.getCenter();
      const lat = center.lat();
      const lng = center.lng();
      document.getElementById('latitude').value = `${lat}`;
      document.getElementById('longitude').value = `${lng}`;
    }

    function initMap() {
      const defaultCenter = {lat: 27.6958, lng: 85.32123}; // Corrected default center

      map = new google.maps.Map(document.getElementById('map'), {
        center: defaultCenter,
        zoom: 18
      });
    //   marker = new google.maps.Marker({
    //     map: map,
    //     position: defaultCenter,
    //     draggable: false,
    //     title: "Drag me!"
    //   });
    marker = new google.maps.Marker({
          position: defaultCenter,
          map: map,
          title: "Center Marker"
        });
        map.addListener('center_changed', () => {
          const newCenter = map.getCenter();
          marker.setPosition(newCenter);
        });

      getLocation((lat, lng) => {
        const currentLocation = {lat: lat, lng: lng};
        map.setCenter(currentLocation);
        updateCoordinates();
      });

      // Update displayed coordinates when button is clicked
      document.querySelector('button').addEventListener('click', updateCoordinates);
    }

    window.onload = initMap;
  </script>
</head>
<body>
  
  <h6>Select the precise location:</h6>
  <div id="map">
    <div id="centerMarker"></div> <!-- This will be the fixed marker at the center -->
  </div>
  <div id="controls">
    <form action="submit" method="POST"></form>
    <button>Set Space</button>
    <!-- <p id="latitude" name="lat" style="display:none"></p>
    <p id="longitude" name="lng" style="display:none"></p> -->
    
    <form method="POST" >
        <label  for="location_name" style="font-size: 18px;" >Enter the area name:</label>
        <input type="text" name="location" required><br>

        <label for="vehicle type" style="font-size: 18px;" >Choose your vehicle type:<label><br>
        <!-- <select name="vehicle-type" id="vehicle-type">
        <option value="" style="font-size: 18px;">Select an option</option>
        <option value="car"  style="font-size: 18px;">Car</option>
        <option value="bike" style="font-size: 18px;" >Bike</option>
        <option value="taxi" style="font-size: 18px;">Taxi</option>
        <option value="cycle" style="font-size: 18px;">Bicycle</option>
        </select> -->
        <select name="vehicle-type" id="vehicle-type" style="width:230px">
           <option style="font-size: 16px;">Select a vehicle category:</option>
            <?php
                $sqlVehicleCat = "SELECT VehicleCat FROM tblcategory";
                $resultVehicle = $conn->query($sqlVehicleCat);
                    if ($resultVehicle->num_rows > 0) {
                        // Output data of each row
                        while($row = $resultVehicle->fetch_assoc()) {
                            echo '<option value="' . $row['VehicleCat'] . '" style="font-size: 16px;">' . $row['VehicleCat'] . '</option>';
                        }
                    } else {
                        echo '<option value="">No categories available</option>';
                    }
                    echo'<br>';
                    ?>
          </select>

        <input type="hidden" id="latitude" name="latitude">
        <input type="hidden" id="longitude" name="longitude">
        <input type="submit" id="submit" name="submit" value="confirm">
        <?php
            global $conn;
            if (isset($_POST['submit'])){
                $latitude = $_POST['latitude'];
                $longitude = $_POST['longitude'];
                $vehicle_type=$_POST['vehicle-type'];
                $user_id = $_SESSION['id'];
                $location=$_POST['location'];

                $stmt = $conn->prepare("INSERT INTO space (lat, lng,vehicletype,user_id,location) VALUES (?, ?,?,?,?)");
                $stmt->bind_param("ddsis", $latitude, $longitude,$vehicle_type,$user_id,$location);

                if ($stmt->execute()) {
                    echo "Location saved successfully!";
                } else {
                    echo "Error: " . $stmt->error;
                }

                $stmt->close();
                } else {
                echo "You are yet to confirm the location.";
                }

            
        ?>
    </form>
  </div>
  <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCGR1rHLlDjRghCLJ0LyHJfN6JTmhi4_N4&libraries=places&callback=initMap"></script>
</body>
</html>

<?php 
//ob_start();
//ob_end_flush(); 
?>