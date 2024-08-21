<?php include('include/header.php'); ?>

<?= alertMessage(); ?>

<h4>EasyPark</h4>

<div class="row">
<div class="col-md-4 mb-4">
          <div class="card card-body p-3">
                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Vehicle Parked</p>
                    <h5 class="font-weight-bolder mb-0">
                      2
                    </h5>
            </div>
          </div>

        <div class="col-md-4 mb-4">
          <div class="card card-body p-3">
                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Vehicle Checkout</p>
                    <h5 class="font-weight-bolder mb-0">
                      5
                    </h5>
            </div>
          </div>

          <div class="col-md-4 mb-4">
          <div class="card card-body p-3">
                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Remaining slots</p>
                    <h5 class="font-weight-bolder mb-0">
                      20
                    </h5>
            </div>
          </div>
</div>

<?php include('include/footer.php'); ?>