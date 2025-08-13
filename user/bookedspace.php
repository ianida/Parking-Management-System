<?php
include('../config/function.php');
include('include/header.php');
include('include/dbcon.php');

$userid = $_SESSION['id'];
?>

<div class="row">
    <div class="col-md-12">
        <div class="card mt-3">
            <div class="card-header">
                <h4>Your Booked Spaces</h4>
            </div>
            <div class="card-body">

                <?php
                $query = "
                    SELECT *
                    FROM userspace
                    INNER JOIN space ON userspace.spaceid = space.space_id
                    INNER JOIN users ON space.user_id = users.id
                    WHERE userspace.userid = ? AND userspace.status='1'
                ";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("s", $userid);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    echo '<table class="table table-bordered table-striped">';
                    echo '<thead><tr>
                            <th>Space Owner</th>
                            <th>Owner Contact</th>
                            <th>Tole Name</th>
                            <th>Location</th>
                            <th>Action</th>
                          </tr></thead><tbody>';

                    while ($row = $result->fetch_assoc()) {
                        $lat = htmlspecialchars($row['lat']);
                        $lng = htmlspecialchars($row['lng']);
                        $user_name = htmlspecialchars($row['name']);
                        $location = htmlspecialchars($row['location']);
                        $space_id = intval($row['space_id']);

                        echo "<tr>
                                <td>{$user_name}</td>
                                <td>" . htmlspecialchars($row['phone']) . "</td>
                                <td>{$location}</td>
                                <td>
                                    <a href='https://www.google.com/maps/search/?api=1&query={$lat},{$lng}' target='_blank' class='btn btn-primary btn-sm'>View on Map</a>
                                </td>
                                <td>
                                    <form action='endBooking.php' method='post' class='d-inline'>
                                        <input type='hidden' name='space_id' value='{$space_id}'>
                                        <button type='submit' class='btn btn-danger btn-sm'>End Booking</button>
                                    </form>
                                </td>
                              </tr>";
                    }

                    echo '</tbody></table>';
                } else {
                    echo '<div class="alert alert-warning">You have no booking to show.</div>';
                }

                $stmt->close();
                $conn->close();
                ?>

            </div>
        </div>
    </div>
</div>

<?php include('include/footer.php'); ?>
