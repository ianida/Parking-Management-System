<?php include('include/header.php'); ?>

<div class="container">
    <div class="row">
        <div class="col-lg-6">
            <div class="card">
            </div>
        </div>
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4> Search Between Dates Reports </h4>
                </div>
                <div class="card-body card-block">
                    <?php
                    $msg = "";
                    if ($msg) {
                        echo '<p style="font-size:16px; color:red" align="center">' . $msg . '</p>';
                    }
                    ?>
                    <form action="bwdates-reports-details.php" method="post" enctype="multipart/form-data" class="form-horizontal" name="bwdatesreport">
                        <div class="row form-group">
                            <label for="fromdate" class="col-md-3 col-form-label">From Date</label>
                            <div class="col-md-9">
                                <input type="date" id="fromdate" name="fromdate" class="form-control" required>
                            </div>
                        </div>
                        <div class="row form-group">
                            <label for="todate" class="col-md-3 col-form-label">To Date</label>
                            <div class="col-md-9">
                                <input type="date" id="todate" name="todate" class="form-control" required>
                            </div>
                        </div>
                        <p style="text-align: center;"><button type="submit" class="btn btn-primary btn-sm" name="submit">Submit</button></p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>




<?php include('include/footer.php'); ?>