<?php
include('../config/function.php');
include('include/header.php');
include('include/dbcon.php');

if(!isset($_SESSION['id'])){
    header("Location: ../loginform.php");
    exit();
}
$userid = $_SESSION['id'];
?>

<div class="row">
    <div class="col-md-12">
        <div class="card mt-3">
            <div class="card-header"><h4>Your Booked Spaces</h4></div>
            <div class="card-body">
                <?php
                $query = "
                    SELECT userspace.userSpaceId, space.space_id, space.location, space.lat, space.lng, users.name, users.phone
                    FROM userspace
                    INNER JOIN space ON userspace.spaceid = space.space_id
                    INNER JOIN users ON space.user_id = users.id
                    WHERE userspace.userid=? AND userspace.status='1'
                ";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("i",$userid);
                $stmt->execute();
                $result = $stmt->get_result();

                if($result->num_rows >0){
                    echo '<table class="table table-bordered table-striped">';
                    echo '<thead><tr>
                            <th>Space Owner</th>
                            <th>Owner Contact</th>
                            <th>Location</th>
                            <th>View Map</th>
                            <th>Owner Info</th>
                            <th>Action</th>
                          </tr></thead><tbody>';
                    while($row=$result->fetch_assoc()){
                        $lat = htmlspecialchars($row['lat']);
                        $lng = htmlspecialchars($row['lng']);
                        $user_name = htmlspecialchars($row['name']);
                        $location = htmlspecialchars($row['location']);
                        $space_id = intval($row['space_id']);
                        $ownerPhone = htmlspecialchars($row['phone']);

                        echo "<tr>
                                <td>{$user_name}</td>
                                <td>{$ownerPhone}</td>
                                <td>{$location}</td>
                                <td>
                                    <a href='https://www.google.com/maps/search/?api=1&query={$lat},{$lng}' target='_blank' class='btn btn-primary btn-sm'>View on Map</a>
                                </td>
                                <td>
                                    <button class='btn btn-info btn-sm' onclick=\"showOwnerDetails('{$user_name}','{$ownerPhone}')\">See Details</button>
                                </td>
                                <td>
                                    <button class='btn btn-danger btn-sm endBookingBtn' data-spaceid='{$space_id}'>End Booking</button>
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

<!-- Owner Details Modal -->
<div id="ownerModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.6); z-index:9999; justify-content:center; align-items:center;">
    <div style="background:white; padding:20px; border-radius:8px; min-width:300px; text-align:center;">
        <h5>Space Owner Details</h5>
        <p><strong>Name:</strong> <span id="ownerName"></span></p>
        <p><strong>Phone:</strong> <span id="ownerPhone"></span></p>
        <button class="btn btn-primary btn-sm" onclick="closeModal()">Close</button>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
function showOwnerDetails(name, phone){
    document.getElementById('ownerName').textContent=name;
    document.getElementById('ownerPhone').textContent=phone;
    document.getElementById('ownerModal').style.display='flex';
}
function closeModal(){
    document.getElementById('ownerModal').style.display='none';
}

// AJAX for ending booking
$(document).ready(function(){
    $('.endBookingBtn').click(function(){
        let spaceId = $(this).data('spaceid');
        Swal.fire({
            title: 'Ending Booking...',
            text: 'Please wait',
            allowOutsideClick: false,
            didOpen: () => { Swal.showLoading() }
        });
        $.ajax({
            url: 'endbooking.php',
            type: 'POST',
            data: {space_id: spaceId},
            dataType: 'json',
            success: function(response){
                if(response.status=='success'){
                    Swal.fire({
                        title:'Booking Ended',
                        text:'Your fare is Rs. '+response.fare,
                        icon:'info',
                        confirmButtonText:'OK'
                    }).then(()=> location.reload());
                } else {
                    Swal.fire('Error',response.message,'error');
                }
            },
            error: function(xhr, status, error){
                let msg = 'Something went wrong!';
                try {
                    let resp = JSON.parse(xhr.responseText);
                    if(resp.message) msg = resp.message; // show server-provided message
                } catch(e){}
                Swal.fire('Error', msg, 'error');
                console.log(xhr.responseText);
            }

        });
    });
});
</script>

<?php include('include/footer.php'); ?>
