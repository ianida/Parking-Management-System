<?php
session_start();
if (!isset($_SESSION['id'])) {
    header("Location:../loginform.php");
    exit();
}

include('include/header.php');
include('include/dbcon.php');
?>

<div class="row">
    <div class="col-md-12">
        <div class="card mt-3">
            <div class="card-header text-center">
                <h4>About Us</h4>
            </div>
            <div class="card-body">

                <p>
                    At Parking Management System, we're passionate about making parking a breeze. Our innovative management system is designed to simplify the parking experience for drivers, property owners, and operators alike. With our cutting-edge technology, you can easily find, reserve, and pay for parking spots, reducing congestion and frustration.
                </p>

                <div class="card mt-3 p-3" style="background-color: #f8f9fa;">
                    <h5>Our Mission</h5>
                    <p>
                        Our mission is to provide a seamless, efficient, and sustainable parking solution that benefits everyone involved. We believe that parking should be a convenience, not a chore. That's why we're dedicated to delivering a user-friendly, data-driven platform that streamlines parking operations and enhances the overall parking experience.
                    </p>
                </div>

                <div class="text-center mt-4">
                    <a href="#" target="_blank" class="btn btn-primary">Join the parking revolution with us!</a>
                </div>

            </div>
        </div>
    </div>
</div>

<?php include('include/footer.php'); ?>
