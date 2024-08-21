<?php
include('../config/function.php');
include('include/header.php');

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">


    <style>
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
    </style>
</head>
<body>
    <h4>Your Space</h4>

</body>
</html>
<?php
$query = "
SELECT *
    FROM space
    WHERE user_id = ?
";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $_SESSION['id']);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {

echo "<table>
        <tr>
          <th>Tole Name</th>
          <th>Location</th>
          <th>Vehicle Type</th>
          <th>Status</th>
          <th>Remark</th>
        </tr>";
while ($row = $result->fetch_assoc()) {
    $lat = $row['lat'];
    $lng = $row['lng'];
    $location = $row['location'];
    $vehicle_type=$row['vehicletype'];
    $space_id=$row['space_id'];
    $status=$row['status'];
    if($status==1){
        $BookingStatus='Booked';
    }else{
        $BookingStatus='Unbooked';
    }

    echo "<tr>
            <td>{$location}</td>
            <td><a href='https://www.google.com/maps/search/?api=1&query={$lat},{$lng}' target='_blank' class='location-link'>Go to the location</a></td>
            <td>{$vehicle_type}</td>
            <td>{$BookingStatus}</td>
            <td> 
                <form action='canclespace.php' method='post'>
                    <input type='hidden' name='space_id' value='".$space_id."'/>
                    <input type='hidden' name='status' value='".$status."'/>
                    <button type='submit' class='book-btn' data-id='{$space_id}' data-status='{$status}'>Remove Location </button>
                </form>
            </td>
          </tr>";
}
echo "</table>";
} else {
echo "<p style='color: #ff0000'>You have not listed any space.<p>";
}



$stmt->close();
$conn->close();
?>