<?php
session_start();
require '../config/function.php';

// Include your site's header
include('include/header.php');
?>

<div class="content">
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-lg-12">
                <div class="card mb-4">
                    <div class="card-header bg-danger text-white">
                        <strong>Payment Failed</strong>
                    </div>
                    <div class="card-body text-center">
                        <p class="text-danger fw-bold">Unfortunately, your payment was not successful.</p>
                        <p class="text-muted">Please try again!</p>
                        <a href="userbalance.php" class="btn btn-primary mt-3">Back to Balance</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('include/footer.php'); ?>
