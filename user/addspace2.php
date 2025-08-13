<?php
include('../config/function.php');
include('include/header.php');

if (!isset($_SESSION['id'])) {     
    header("Location:../loginform.php");
    exit();
}
$user_id = $_SESSION['id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Select Location with Map Preview</title>
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
  <style>
    body { font-family: Arial; margin: 20px; }
    #map { height: 400px; width: 60%; border:2px solid #ccc; border-radius:10px; margin-bottom:10px; }
    input, select, button { padding:8px; margin:5px 0; border-radius:5px; font-size:16px; }
    button { background:#007BFF; color:white; border:none; cursor:pointer; }
    button:hover { background:#0056b3; }
  </style>
</head>
<body>

<h6>Select the precise location:</h6>

<input id="location_search" type="text" placeholder="Type area name and press Enter" style="width:60%;">
<div id="map"></div>

<form method="POST">
    <label>Area Name:</label>
    <input type="text" name="location" id="location_name" required><br>

    <label>Choose Vehicle Category:</label><br>
    <select name="vehicle-type" id="vehicle_type" required>
        <option value="">Select a vehicle category</option>
        <?php
        $sqlVehicleCat = "SELECT VehicleCat FROM tblcategory";
        $resultVehicle = $conn->query($sqlVehicleCat);
        if ($resultVehicle->num_rows > 0) {
            while($row = $resultVehicle->fetch_assoc()){
                echo '<option value="'.$row['VehicleCat'].'">'.$row['VehicleCat'].'</option>';
            }
        }
        ?>
    </select><br><br>

    <input type="hidden" id="latitude" name="latitude">
    <input type="hidden" id="longitude" name="longitude">

    <input type="submit" name="submit" value="Confirm">
</form>

<?php
if (isset($_POST['submit'])) {
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];
    $vehicle_type = $_POST['vehicle-type'];
    $location = $_POST['location'];
    $status = '0'; // default unbooked

    $stmt = $conn->prepare("INSERT INTO space (lat, lng, vehicletype, user_id, location, status) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ddsiss", $latitude, $longitude, $vehicle_type, $user_id, $location, $status);

    if ($stmt->execute()) {
        echo "<p style='color:green;'>Location saved successfully!</p>";
    } else {
        echo "<p style='color:red;'>Error: ".$stmt->error."</p>";
    }
    $stmt->close();
}
?>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
let map = L.map('map').setView([27.6958, 85.32123], 13); // Default Kathmandu

// OpenStreetMap tiles
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap contributors'
}).addTo(map);

// Center marker
let marker = L.marker(map.getCenter(), {draggable:true}).addTo(map);

// Update hidden fields when marker moves
marker.on('move', function(e){
    document.getElementById('latitude').value = e.latlng.lat;
    document.getElementById('longitude').value = e.latlng.lng;
});

// Update marker and map when user types a location
document.getElementById('location_search').addEventListener('keydown', function(e){
    if(e.key === "Enter"){
        e.preventDefault();
        const query = this.value;
        fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&countrycodes=NP`)
        .then(res => res.json())
        .then(data => {
            if(data && data.length > 0){
                const loc = data[0];
                const lat = parseFloat(loc.lat);
                const lon = parseFloat(loc.lon);
                map.setView([lat, lon], 16);
                marker.setLatLng([lat, lon]);
                document.getElementById('latitude').value = lat;
                document.getElementById('longitude').value = lon;

                // Do NOT overwrite area name; keep what user typed
                // document.getElementById('location_name').value = loc.display_name; // REMOVE THIS
            } else {
                alert("Location not found in Nepal!");
            }
        });
    }
});

</script>
</body>
</html>
