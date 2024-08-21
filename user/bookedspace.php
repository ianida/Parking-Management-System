<?php
include('../config/function.php');
include ('include/header.php');
include ('include/dbcon.php');

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
  <h2>Your Booked space: </h2>
</body>
</html>


<?php
$userid=$_SESSION['id'];
$query = "
    SELECT *
        FROM userspace
        INNER JOIN space ON userspace.spaceid = space.space_id
        INNER JOIN users ON space.user_id=users.id
        WHERE userspace.userid = ? AND userspace.status='1'
";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $userid);
$stmt->execute();
$result = $stmt->get_result();


if ($result->num_rows > 0) {

    echo "<table>
            <tr>
              <th>Space Owner</th>
              <th>Owner Contact</th>
              <th>Tole Name</th>
              <th>Location</th>
              <th>Remark</th>
            </tr>";
    while ($row = $result->fetch_assoc()) {
        $lat = $row['lat'];
        $lng = $row['lng'];
        $contact=$row['phone'];
        $user_name = $row['name'];
        $location = $row['location'];
        $phone_number = $row['phone'];
        $status = $row['status'];
        $space_id = $row['space_id'];
        echo "<tr>
                <td>{$user_name}</td>
                <td>{$contact}</td>
                <td>{$location}</td>
                <td><a href='https://www.google.com/maps/search/?api=1&query={$lat},{$lng}' target='_blank' class='location-link'>Go to the location</a></td>
                <td>
                    <form action='endBooking.php' method='post'>
                      <input type='hidden' name='space_id' value='".$space_id."'/>
                      <button type='submit' class='book-btn' data-id='{$space_id}' data-status='{$status}'>End booking</button>
                    </form>
                </td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "<p style='color: #ff0000'>You have no booking to show.<p>";
}


?>
