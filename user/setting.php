<?php
include('../config/function.php');
// Check if user is logged in
if (!isset($_SESSION['id'])) {
    header("location:logout.php");
    exit();
}
include('include/header.php');
include('include/dbcon.php');

// Initialize errors array
$errors = array(); 
$user_id = $_SESSION['id'];

// Fetch user details
$query = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    echo "User not found.";
    exit();
}

// Variables for toast message
$toastMsg = null;
$toastError = false;

// Change Name
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['changeName'])) {
    $newName = $_POST['name'];
    if (empty($newName)) {
        $errors['name'] = "Name is required.";
    } elseif (strlen($newName) < 5) {
        $errors['name'] = "Name must be at least 5 characters.";
    }
    if (empty($errors['name'])) {
        $queryUpdateName = "UPDATE users SET name = ? WHERE id = ?";
        $stmtUpdateName = $conn->prepare($queryUpdateName);
        $stmtUpdateName->bind_param("si", $newName, $user_id);
        if ($stmtUpdateName->execute()) {
            $_SESSION['name'] = $newName;
            $toastMsg = 'Name updated successfully.';
            $toastError = false;
            $user['name'] = $newName; // update current value for form
        } else {
            $toastMsg = 'Failed to update name.';
            $toastError = true;
        }
        $stmtUpdateName->close();
    }
}

// Change Phone
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['changePhone'])) {
    $newPhone = $_POST['phone'];
    if (empty($newPhone)) {
        $errors['phone'] = "Phone number is required.";
    } elseif (!is_numeric($newPhone)) {
        $errors['phone'] = "Phone number must be numeric.";
    } elseif (strlen($newPhone) !== 10) {
        $errors['phone'] = "Phone number must be 10 digits.";
    }
    if (empty($errors['phone'])) {
        $queryUpdatePhone = "UPDATE users SET phone = ? WHERE id = ?";
        $stmtUpdatePhone = $conn->prepare($queryUpdatePhone);
        $stmtUpdatePhone->bind_param("si", $newPhone, $user_id);
        if ($stmtUpdatePhone->execute()) {
            $toastMsg = 'Phone number updated successfully.';
            $toastError = false;
            $user['phone'] = $newPhone; // update current value for form
        } else {
            $toastMsg = 'Failed to update phone number.';
            $toastError = true;
        }
        $stmtUpdatePhone->close();
    }
}

// Change Username
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['changeUsername'])) {
    $newUsername = $_POST['username'];
    if (empty($newUsername)) {
        $errors['username'] = "Username is required.";
    } elseif (!ctype_alnum($newUsername)) {
        $errors['username'] = "Username must be alphanumeric.";
    } elseif (strlen($newUsername) < 6) {
        $errors['username'] = "Username must be at least 6 characters.";
    }
    if (empty($errors['username'])) {
        $queryUpdateUsername = "UPDATE users SET username = ? WHERE id = ?";
        $stmtUpdateUsername = $conn->prepare($queryUpdateUsername);
        $stmtUpdateUsername->bind_param("si", $newUsername, $user_id);
        if ($stmtUpdateUsername->execute()) {
            $_SESSION['username'] = $newUsername;
            $toastMsg = 'Username updated successfully.';
            $toastError = false;
            $user['username'] = $newUsername; // update current value for form
        } else {
            $toastMsg = 'Failed to update username.';
            $toastError = true;
            echo "<script>document.getElementsByName('username')[0].focus();</script>";
        }
        $stmtUpdateUsername->close();
    }
}

// Change Password
if (isset($_POST['changePassword'])) {
    $currentPassword = filter_var($_POST['currentPassword'], FILTER_SANITIZE_STRING);
    $newPassword = filter_var($_POST['newPassword'], FILTER_SANITIZE_STRING);
    $confirmPassword = filter_var($_POST['confirmPassword'], FILTER_SANITIZE_STRING);
    $currentActualpassword = $_SESSION['password'];

    if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
        $errors['newpassword'] = "All password fields must be filled out.";
    } elseif ($currentActualpassword != $currentPassword) {
        $errors['currentpassword'] = "Current password does not match.";
    } elseif ($newPassword !== $confirmPassword) {
        $errors['newpassword'] = "New password and confirm password do not match.";
    } else {
        $query = "UPDATE users SET password=? WHERE id=?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("si", $newPassword, $user_id);
        if ($stmt->execute()) {
            $toastMsg = 'Password updated successfully.';
            $toastError = false;
            $_SESSION['password'] = $newPassword; // update session password
        } else {
            $toastMsg = 'Password update failed.';
            $toastError = true;
        }
    }
}

// Delete Account
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['deleteAccount'])) {
    $queryDeleteSpace = "DELETE FROM space WHERE user_id=?";
    $stmtDeleteSpace = $conn->prepare($queryDeleteSpace);
    $stmtDeleteSpace->bind_param("i", $user_id);
    $stmtDeleteSpace->execute();

    $queryDeleteVehicle = "DELETE FROM tblvehicle WHERE Userid=?";
    $stmtDeleteVehicle = $conn->prepare($queryDeleteVehicle);
    $stmtDeleteVehicle->bind_param("i", $user_id);
    $stmtDeleteVehicle->execute();

    $queryDeleteUserSpace = "DELETE FROM userspace WHERE userid=?";
    $stmtDeleteUserSpace = $conn->prepare($queryDeleteUserSpace);
    $stmtDeleteUserSpace->bind_param("i", $user_id);
    $stmtDeleteUserSpace->execute();

    $queryDeleteAccount = "DELETE FROM users WHERE id = ?";
    $stmtDeleteAccount = $conn->prepare($queryDeleteAccount);
    $stmtDeleteAccount->bind_param("i", $user_id);
    if ($stmtDeleteAccount->execute()) {
        session_destroy();
        echo "<script>alert('Account deleted successfully.');</script>";
        echo "<script>window.location.replace('../loginform.php');</script>";
        exit();
    } else {
        $toastMsg = 'Failed to delete account.';
        $toastError = true;
    }
    $stmtDeleteAccount->close();
}

$stmt->close();
$conn->close();
?>

<!-- Toast message container -->
<div id="toastMessage" style="
    position: fixed;
    top: 10px;
    right: 10px;
    background: #28a745;
    color: white;
    padding: 10px 20px;
    border-radius: 5px;
    font-weight: bold;
    display: none;
    z-index: 9999;
    box-shadow: 0 0 10px rgba(0,0,0,0.3);
"></div>

<div class="row"></div>
<div class="col-md-12">
    <div class="card mt-4">
        <div class="card-header">
            <h4>User Settings</h4>
        </div>
        <div class="card-body">
          <!-- Change Name Form -->
          <form method="POST" class="mb-4">
              <div class="row">
                  <div class="col-md-9 mb-3">
                      <label>Name</label>
                      <input type="text" name="name" value="<?= htmlspecialchars($user['name']); ?>" required class="form-control">
                      <?php if (isset($errors['name'])) { echo "<small class='text-danger'>" . $errors['name'] . "</small>"; } ?>
                  </div>
                  <div class="col-md-3 mb-3">
                      <button type="submit" name="changeName" class="btn btn-primary w-100" style="margin-top: 29px;">Change Name</button>
                  </div>
              </div>
          </form>

          <!-- Change Username Form -->
          <form method="POST" class="mb-4">
              <div class="row">
                  <div class="col-md-9 mb-3">
                      <label>Username</label>
                      <input type="text" name="username" value="<?= htmlspecialchars($user['username']); ?>" required class="form-control">
                      <?php if (isset($errors['username'])) { echo "<small class='text-danger'>" . $errors['username'] . "</small>"; } ?>
                  </div>
                  <div class="col-md-3 mb-3">
                      <button type="submit" name="changeUsername" class="btn btn-primary w-100" style="margin-top: 29px;">Change Username</button>
                  </div>
              </div>
          </form>

          <!-- Change Phone Form -->
          <form method="POST" class="mb-4">
              <div class="row">
                  <div class="col-md-9 mb-3">
                      <label>Phone Number</label>
                      <input type="text" name="phone" value="<?= htmlspecialchars($user['phone']); ?>" required class="form-control">
                      <?php if (isset($errors['phone'])) { echo "<small class='text-danger'>" . $errors['phone'] . "</small>"; } ?>
                  </div>
                  <div class="col-md-3 mb-3">
                      <button type="submit" name="changePhone" class="btn btn-primary w-100" style="margin-top: 29px;">Change Phone Number</button>
                  </div>
              </div>
          </form>

          <!-- Change Password Form -->
          <form method="POST" onsubmit="return validatePassword(event);" class="mb-4">
              <div class="row">
                  <div class="col-md-4 mb-3">
                      <label>Current Password</label>
                      <input type="password" name="currentPassword" id="currentPassword" class="form-control" required>
                      <?php if (isset($errors['currentpassword'])) { echo "<small class='text-danger'>" . $errors['currentpassword'] . "</small>"; } ?>
                  </div>
                  <div class="col-md-4 mb-3">
                      <label>New Password</label>
                      <input type="password" name="newPassword" id="newPassword" class="form-control" required>
                  </div>
                  <div class="col-md-4 mb-3 d-flex flex-column">
                      <label>Confirm New Password</label>
                      <input type="password" name="confirmPassword" id="confirmPassword" class="form-control" required>
                  </div>
              </div>
              <div class="row">
                  <div class="col-md-12">
                      <?php if (isset($errors['newpassword'])) { ?>
                          <small class="text-danger mb-2"><?= $errors['newpassword']; ?></small>
                      <?php } ?>
                  </div>
              </div>
              <div class="row">
                  <div class="col-md-3">
                      <button type="submit" name="changePassword" class="btn btn-primary w-100">Change Password</button>
                  </div>
              </div>
          </form>

          <!-- Delete Account Button triggers modal -->
          <div class="mb-3 text-end">
              <button type="button" class="btn btn-danger" style="width:150px;" data-bs-toggle="modal" data-bs-target="#deleteConfirmModal">
                Delete Account
              </button>
          </div>

          <!-- Delete Account Modal -->
          <div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-labelledby="deleteConfirmModalLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <form method="POST">
                  <div class="modal-header">
                    <h5 class="modal-title" id="deleteConfirmModalLabel">Confirm Delete Account</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                    Are you sure you want to delete your account? This action cannot be undone.
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" name="deleteAccount" class="btn btn-danger">Yes, Delete</button>
                  </div>
                </form>
              </div>
            </div>
          </div>

      </div>
    </div>
</div>

<script>
function validatePassword(event) {
    var currentPassword = document.getElementById('currentPassword').value.trim();
    var newPassword = document.getElementById('newPassword').value.trim();
    var confirmPassword = document.getElementById('confirmPassword').value.trim();

    if (!currentPassword || !newPassword || !confirmPassword) {
        alert('Please fill all password fields.');
        return false;
    }
    if (newPassword !== confirmPassword) {
        document.getElementById('newPassword').focus();
        return false; // prevent submit, PHP error shows message
    }
    return true; // allow submit
}

// Toast message function
function showToast(message, isError = false) {
    const toast = document.getElementById('toastMessage');
    toast.textContent = message;
    toast.style.backgroundColor = isError ? '#dc3545' : '#28a745';
    toast.style.display = 'block';
    setTimeout(() => {
        toast.style.display = 'none';
    }, 2000);
}

// Show toast if message available
<?php if ($toastMsg !== null): ?>
document.addEventListener('DOMContentLoaded', function () {
    showToast(<?= json_encode($toastMsg) ?>, <?= $toastError ? 'true' : 'false' ?>);
});
<?php endif; ?>
</script>

<!-- Add Bootstrap JS for modal functionality -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<?php include('include/footer.php'); ?>
