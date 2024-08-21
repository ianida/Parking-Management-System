<?php include('include/header.php'); ?>

<div class="row"></div>
<div class="col-md-12">
    <div class="card">
        <div class="card-header">
            <h4>
                Add Users
                <a href="users.php" class="btn btn-danger float-end">Back</a>
            </h4>
        </div>
        <div class="card-body">
            <?=alertMessage(); ?>
            <form action="code.php" method="POST">

            <div class="row">
                <div class="col-md-6">
                <div class="mb-3">
                    <label> Full Name </label>
                    <input type="text" name="name" class="form-control">
                </div>
                </div>

                <div class="col-md-6">
                <div class="mb-3">
                    <label> Username </label>
                    <input type="text" name="username" class="form-control">
                </div>
                </div>

                <div class="col-md-6">
                <div class="mb-3">
                    <label> Email </label>
                    <input type="email" name="email" class="form-control">
                </div>
                </div>

                <div class="col-md-6">
                <div class="mb-3">
                    <label> Contact Information </label>
                    <input type="tel" name="phone" class="form-control">
                </div>
                </div>

                <div class="col-md-6">
                <div class="mb-3">
                    <label> Password </label>
                    <input type="password" name="password" class="form-control">
                </div>
                </div>

                <div class="col-md-3">
                <div class="mb-3">
                    <label> Select role </label>
                    <select name="role" class="form-select">
                        <option value="">Select role</option>
                        <option value="admin">Admin</option>
                        <option value="user">User</option>
                    </select>
                    </div>
                </div>

                <div class="col-md-6">
                <div class="mb-3 text-end">
                    <br>
                        <button type="submit" name="saveuser" class="btn btn-primary">Save</button>
                    </div>

            </div>

            </div>
        
            </form>
        </div>
    </div>
</div>

<?php include('include/footer.php'); ?>