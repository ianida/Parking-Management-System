<?php include('include/header.php'); ?>

<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="text-center">Search Between Dates Reports</h4>
                </div>
                <div class="card-body card-block">
                    <form action="bwdates-reports-details.php" method="post" enctype="multipart/form-data" class="form-horizontal" name="bwdatesreport" style="max-width: 450px; margin: 0 auto;" onsubmit="return validateDates()">
                        <div class="row form-group justify-content-center align-items-center" style="margin-bottom: 25px;">
                            <label for="fromdate" class="col-md-4 col-form-label text-md-end">From Date</label>
                            <div class="col-md-6">
                                <input type="date" id="fromdate" name="fromdate" class="form-control" required max="<?php echo date('Y-m-d'); ?>">
                            </div>
                        </div>
                        <div class="row form-group justify-content-center align-items-center" style="margin-bottom: 25px;">
                            <label for="todate" class="col-md-4 col-form-label text-md-end">To Date</label>
                            <div class="col-md-6">
                                <input type="date" id="todate" name="todate" class="form-control" required max="<?php echo date('Y-m-d'); ?>">
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

<script>
// read this: yo chai to 'Prevent FromDate > ToDate' ani make 'ToDate >= FromDate'
var fromInput = document.getElementById('fromdate');
var toInput = document.getElementById('todate');

fromInput.addEventListener('change', function() {
    // Sets min of To date to From date
    toInput.min = this.value;
});

toInput.addEventListener('change', function() {
    if (fromInput.value > this.value) {
        alert("To Date cannot be earlier than From Date.");
        this.value = fromInput.value; // resets to valid date
    }
});

function validateDates() {
    var fromDate = fromInput.value;
    var toDate = toInput.value;

    if (fromDate > toDate) {
        alert("From Date cannot be greater than To Date.");
        return false;
    }
    return true;
}
</script>

<?php include('include/footer.php'); ?>
