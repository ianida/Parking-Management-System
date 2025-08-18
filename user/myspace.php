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
                $query = "
                    SELECT s.space_id, s.lat, s.lng, s.location, s.vehicletype, s.status,
                           u2.name AS booked_by_name, u2.email AS booked_by_email, u2.phone AS booked_by_phone,
                           v.VehicleModel, v.RegistrationNumber, v.Color, v.VehicleCompanyname
                    FROM space s
                    LEFT JOIN userspace us ON s.space_id = us.spaceid AND us.status = '1'
                    LEFT JOIN users u2 ON us.userid = u2.id
                    LEFT JOIN tblvehicle v ON us.vehicle_id = v.ID
                    WHERE s.user_id = ?
                ";
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
                            <th>Actions</th>
                          </tr></thead><tbody>';

                    while ($row = $result->fetch_assoc()) {
                        $lat = htmlspecialchars($row['lat']);
                        $lng = htmlspecialchars($row['lng']);
                        $location = htmlspecialchars($row['location']);
                        $vehicle_type = htmlspecialchars($row['vehicletype']);
                        $status = $row['status'];

                        $BookingStatus = ($status == '1') ? 'Booked' : 'Unbooked';

                        echo "<tr>
                                <td>{$location}</td>
                                <td>
                                    <a href='https://www.google.com/maps/search/?api=1&query={$lat},{$lng}' target='_blank' class='btn btn-primary btn-sm'>View on Map</a>
                                </td>
                                <td>{$vehicle_type}</td>
                                <td>{$BookingStatus}</td>
                                <td>";

                        if ($status == '1') {
                            $details = [
                                'Name' => $row['booked_by_name'],
                                'Email' => $row['booked_by_email'],
                                'Phone' => $row['booked_by_phone'],
                                'Vehicle Model' => $row['VehicleModel'],
                                'Registration' => $row['RegistrationNumber'],
                                'Color' => $row['Color'],
                                'Company' => $row['VehicleCompanyname']
                            ];
                            $detailsJson = htmlspecialchars(json_encode($details));
                            echo "<button class='btn btn-info btn-sm' onclick='showBookingDetails({$detailsJson})'>View Details</button>";
                        } else {
                            echo "-";
                        }

                        echo "</td></tr>";
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

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
function showBookingDetails(details) {
    let htmlContent = "<ul style='text-align:left;'>";
    for (let key in details) {
        htmlContent += `<li><strong>${key}:</strong> ${details[key] ?? 'N/A'}</li>`;
    }
    htmlContent += "</ul>";

    Swal.fire({
        title: 'Booking Details',
        html: htmlContent,
        icon: 'info',
        confirmButtonText: 'Close'
    });
}
</script>

<?php include('include/footer.php'); ?>
