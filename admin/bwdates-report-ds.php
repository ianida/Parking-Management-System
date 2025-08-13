<?php include('include/header.php'); ?>

<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="text-center">Search Between Dates Reports</h4>
                </div>
                <div class="card-body card-block">
                    <form action="bwdates-reports-details.php" method="post" enctype="multipart/form-data" class="form-horizontal" name="bwdatesreport" style="max-width: 450px; margin: 0 auto;">
                        <div class="row form-group justify-content-center align-items-center" style="margin-bottom: 25px;">
                            <label for="fromdate" class="col-md-4 col-form-label text-md-end">From Date</label>
                            <div class="col-md-6">
                                <input type="date" id="fromdate" name="fromdate" class="form-control" required>
                            </div>
                        </div>
                        <div class="row form-group justify-content-center align-items-center" style="margin-bottom: 25px;">
                            <label for="todate" class="col-md-4 col-form-label text-md-end">To Date</label>
                            <div class="col-md-6">
                                <input type="date" id="todate" name="todate" class="form-control" required>
                            </div>
                        </div>
                        <p style="text-align: center; margin-top: 30px;">
                            <button type="submit" class="btn btn-primary btn-sm" name="submit">Submit</button>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('include/footer.php'); ?>
