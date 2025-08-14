<?php
include('../config/function.php');
include('include/header.php');
?>

<div class="row">
    <div class="col-md-12">
        <div class="card mt-3">
            <div class="card-header">
                <h4>Your Space</h4>
            </div>
            <div class="card-body">

                <?php
                $query = "SELECT * FROM space WHERE user_id = ?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("i", $_SESSION['id']);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    echo '<table class="table table-bordered table-striped">';
                    echo '<thead><tr>
                            <th>Tole Name</th>
                            <th>Location</th>
                            <th>Vehicle Type</th>
                            <th>Status</th>
                          </tr></thead><tbody>';

                    while ($row = $result->fetch_assoc()) {
                        $lat = htmlspecialchars($row['lat']);
                        $lng = htmlspecialchars($row['lng']);
                        $location = htmlspecialchars($row['location']);
                        $vehicle_type = htmlspecialchars($row['vehicletype']);
                        $space_id = intval($row['space_id']);
                        $status = $row['status'];

                        $BookingStatus = ($status == '1') ? 'Booked' : 'Unbooked';

                        echo "<tr>
                                <td>{$location}</td>
                                <td>
                                    <a href='https://www.google.com/maps/search/?api=1&query={$lat},{$lng}' target='_blank' class='btn btn-primary btn-sm'>View on Map</a>
                                </td>
                                <td>{$vehicle_type}</td>
                                <td>{$BookingStatus}</td>
                                
                              </tr>";
                    }

                    echo '</tbody></table>';
                } else {
                    echo '<div class="alert alert-warning">You have not listed any space.</div>';
                }

                $stmt->close();
                $conn->close();
                ?>

            </div>
        </div>
    </div>
</div>

<?php include('include/footer.php'); ?>
