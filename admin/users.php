<?php include('include/header.php'); 
session_start();
$_SESSION['auth'] = true;
                    $_SESSION['loggedInUserRole'] = $row['role'];
                    $_SESSION['loggedInUser'] = [
                        'name' => $row['name'],
                        'email' => $row['email']
                    ];
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

        <?=alertMessage(); ?>

        <table class="table table-bordered table-stripped">
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
                    <td><?= $userItem['id']; ?></td>
                    <td><?= $userItem['username']; ?></td>
                    <td><?= $userItem['name']; ?></td>
                    <td><?= $userItem['email']; ?></td>
                    <td><?= $userItem['phone']; ?></td>
                    <td><?= $userItem['role']; ?></td>
                    <td>
                        <a href="users-edit.php?id=<?= $userItem['id']; ?>" class="btn btn-success btn-sm">Edit</a>
                        <a href="users-delete.php?id=<?= $userItem['id']; ?>" 
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