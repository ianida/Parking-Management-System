<?php 
include('include/header.php'); 

// Optional: check if user is logged in and is admin before showing this page
if (!isset($_SESSION['auth']) || $_SESSION['loggedInUserRole'] !== 'admin') {
    header('Location: ../loginform.php'); // redirect non-admin users
    exit;
}
?>

<div class="row"></div>
<div class="col-md-12">
    <div class="card">
        <div class="card-header">
            <h4>
                Users List
                <a href="users-create.php" class="btn btn-primary float-end">Add User</a>
            </h4>
        </div>
        <div class="card-body">

        <?= alertMessage(); ?>

        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Username</th>
                    <th>FullName</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Role</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                   $users = getAll('users');
                   if(mysqli_num_rows($users) > 0)
                   {
                    foreach($users as $userItem)
                    {
                        ?>
                <tr>
                    <td><?= htmlspecialchars($userItem['id']); ?></td>
                    <td><?= htmlspecialchars($userItem['username']); ?></td>
                    <td><?= htmlspecialchars($userItem['name']); ?></td>
                    <td><?= htmlspecialchars($userItem['email']); ?></td>
                    <td><?= htmlspecialchars($userItem['phone']); ?></td>
                    <td><?= htmlspecialchars($userItem['role']); ?></td>
                    <td>
                        <a href="users-edit.php?id=<?= urlencode($userItem['id']); ?>" class="btn btn-success btn-sm">Edit</a>
                        <a href="users-delete.php?id=<?= urlencode($userItem['id']); ?>" 
                           class="btn btn-danger btn-sm mx-2"
                           onclick="return confirm('Are you sure you want to delete this data?')"
                           > Delete</a>
                    </td>

                </tr>
                        <?php
                    }
                   }
                   else
                   {
                    ?>
                    <tr>
                        <td colspan="7">No Record Found</td>
                    </tr>
                    <?php
                   } 
                ?>
                
            </tbody>
        </table>

        </div>
    </div>
</div>

<?php include('include/footer.php'); ?>
