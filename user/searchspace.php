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

<div class="row">
    <div class="col-md-12">
        <div class="card mt-3">
            <div class="card-header">
                <h4>Search Parking Space</h4>
            </div>
            <div class="card-body">

                <!-- Search Form -->
                <form method="POST" class="mb-4" style="display: flex; align-items: stretch; gap: 10px; max-width: 400px;">
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
                    <button type="submit" class="btn btn-primary" style="padding: 0.5rem 1.25rem; font-weight: 600;">Search</button>
                </form>


                <!-- Results -->
                <div class="results">
                    <?php
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        $vehicle_type = trim($_POST['vehicle_type'] ?? '');
                        if (empty($vehicle_type)) {
                            echo '<div class="alert alert-danger">Please select a vehicle type.</div>';
                        } else {
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
                                echo '<table class="table table-bordered table-striped">';
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
                                            <td><a href='https://www.google.com/maps/search/?api=1&query={$lat},{$lng}' target='_blank' class='text-primary'>View on Map</a></td>
                                            <td>
                                                <form action='book.php' method='post' class='d-inline'>
                                                    <input type='hidden' name='space_id' value='{$space_id}'>
                                                    <button type='submit' class='btn btn-success btn-sm'>Book</button>
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
        </div>
    </div>
</div>

<?php include('include/footer.php'); ?>
