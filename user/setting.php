<?php
include('../config/function.php');
//Check if user is logged in
if (!isset($_SESSION['id'])) {
    header("location:logout.php");
    exit();
}
include('include/header.php');
include('include/dbcon.php');

// Initialize an array to store validation errors
$errors = array(); 
$user_id = $_SESSION['id'];

// Fetch user details from database
$query = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$focusField;
if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    echo "User not found.";
    exit();
}

// Update name if form submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['changeName'])) {
    $newName = $_POST['name'];

    // Validate name (example: required and minimum length)
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
            // Update session data if needed
            $_SESSION['name'] = $newName;
            echo "<script>alert('Name updated successfully.');</script>";
        } else {
            echo "<script>alert('Failed to update name.');</script>";
        }
        $stmtUpdateName->close();
    }
}

// Update phone number if form submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['changePhone'])) {
    $newPhone = $_POST['phone'];

    // Validate phone number (example: numeric and length)
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
        $stmtUpdatePhone->bind_param("di", $newPhone, $user_id);
        if ($stmtUpdatePhone->execute()) {
            
            header('location:setting.php');
            echo "<script>alert('Phone number updated successfully.');</script>";
            
        } else {
            echo "<script>alert('Failed to update phone number.');</script>";
        }
        $stmtUpdatePhone->close();
    }
}

// Update username if form submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['changeUsername'])) {
    $newUsername = $_POST['username'];

    // Validate username (example: alphanumeric and length)
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
            // Update session data if needed
            $_SESSION['username'] = $newUsername;
            echo "<script>alert('Username updated successfully.');</script>";

        } else {
            echo "<script>alert('Failed to update username.');</script>";
            echo "<script>document.gerElementById('username').focus();</script>";
        }
        $stmtUpdateUsername->close();
    }
}

// Delete account if form submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['deleteAccount'])) {
    $queryDeleteSpace="Delete from space where user_id=?";
    $stmtDeleteSpace=$conn->prepare($queryDeleteSpace);
    $stmtDeleteSpace->bind_param("i", $user_id);
    $stmtDeleteSpace->execute();

    $queryDeleteVehicle="Delete from tblvehicle where Userid=?";
    $stmtDeleteVehicle=$conn->prepare($queryDeleteVehicle);
    $stmtDeleteVehicle->bind_param("i", $user_id);
    $stmtDeleteVehicle->execute();

    $queryDeleteUserSpace="Delete from userspace where userid=?";
    $stmtDeleteUserSpace=$conn->prepare($queryDeleteUserSpace);
    $stmtDeleteSpace->bind_param("i", $user_id);
    $stmtDeleteUserSpace->execute();

    $queryDeleteAccount = "DELETE FROM users WHERE id = ?";
    $stmtDeleteAccount = $conn->prepare($queryDeleteAccount);
    $stmtDeleteAccount->bind_param("i", $user_id);
    if ($stmtDeleteAccount->execute()) {
        // Clear session and redirect to login page
        session_destroy();
        echo "<script>alert('Account deleted successfully.');</script>";
        // Redirect to login page after account deletion
        echo "<script>window.location.replace('../loginform.php');</script>";
        exit();
    } else {
        echo "<script>alert('Failed to delete account.');</script>";
    }
    $stmtDeleteAccount->close();
}
if (isset($_POST['changePassword'])) {
    // Validate passwords
    $currentPassword = filter_var($_POST['currentPassword'], FILTER_SANITIZE_STRING);
    $newPassword = filter_var($_POST['newPassword'],FILTER_SANITIZE_STRING);
    $confirmPassword = filter_var($_POST['confirmPassword'],FILTER_SANITIZE_STRING);
    $currentActualpassword=$_SESSION['password'];
    

    if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
      $errors['newpassword'] = "All password fields must be filled out.";
      $focusField = empty($currentPassword) ? 'currentPassword' : (empty($newPassword) ? 'newPassword' : 'confirmPassword');
    } elseif($currentActualpassword!=$currentPassword){
      $errors['currentpassword'] = "Current password doesnot" ;
      $focusField = 'currentPassword';
    }elseif ($newPassword !== $confirmPassword) {
      $errors['newpassword'] = "New password and confirm password do not match.";
      $focusField = 'newPassword';
    } else {
      $query="Update table user set password=? where id=?";
      $stmt = $conn->prepare($query);
      $stmt->bind_param("si", $currentPassword, $user_id);
      if ($stmt->execute()){
        echo "<script>alert('Password updated successfully.');</script>";
      }else{
        echo "<script>alert('Password updated Failed.');</script>";
      }
      
    }
  }

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Setting</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        form {
            margin-bottom: 20px;
        }

        input[type="text"], input[type="password"] {
            padding: 8px;
            font-size: 1em;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-bottom: 10px;
            width: 300px; /* Adjust input width as needed */
        }

        .error {
            color: red;
            font-size: 0.9em;
            margin-top: 2px;
        }

        button {
            padding: 10px 20px;
            font-size: 1em;
            color: white;
            background-color: #007BFF;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #0056b3;
        }

        .success {
            color: green;
            font-size: 0.9em;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <h3>Setting</h3>
    
    <form method="POST">
        <label for="name">Name:</label><br>
        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($_SESSION['loggedInUser']['name']); ?>"><br>
        <?php if (isset($errors['name'])) { echo "<span class='error'>" . $errors['name'] . "</span><br>"; } ?>
        <button type="submit" name="changeName">Change Name</button>
    </form>

    <form method="POST">
        <label for="phone">Phone Number:</label><br>
        <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>"><br>
        <?php if (isset($errors['phone'])) { echo "<span class='error'>" . $errors['phone'] . "</span><br>"; } ?>
        <button type="submit" name="changePhone">Change Phone Number</button>
    </form>

    <form method="POST">
        <label for="username">Username:</label><br>
        <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>"><br>
        <?php if (isset($errors['username'])) { echo "<span class='error'>" . $errors['username'] . "</span><br>"; } ?>
        <button type="submit" name="changeUsername">Change Username</button>
    </form>
    <form method="POST" onsubmit="return validatePassword(event)">
        <label for="currentPassword">Current Password:</label><br>
        <input type="password" id="currentPassword" name="currentPassword"><br>
        <?php if (isset($errors['currentpassword'])) { echo "<span class='error'>" . $errors['currentpassword'] . "</span><br>"; } ?>
        <label for="newPassword">New Password:</label><br>
        <input type="password" id="newPassword" name="newPassword" ><br>
        <label for="confirmPassword">Confirm New Password:</label><br>
        <input type="password" id="confirmPassword" name="confirmPassword" ><br>
        <?php if (isset($errors['newpassword'])) { echo "<span class='error'>" . $errors['newpassword'] . "</span><br>"; } ?>
        <div id="password" class="password"></div>
     <button type="submit" name="changePassword">Change Password</button>
    </form>

    <form method="POST" onsubmit="return confirm('Are you sure you want to delete your account?');">
        <button type="submit" name="deleteAccount" style="background-color: #dc3545;">Delete Account</button>
    </form>


    <script>
    // Frontend validation functions (can be expanded as needed)
    function validateName() {
      var name = document.getElementById('name').value.trim();
      if (name === '') {
        displayMessage('Name cannot be empty.', 'error');
        document.getElementById('name').focus();
        return false;
      }
      return true;
    }

    function validatePhone() {
      var phone = document.getElementById('phone').value.trim();
      if (phone === '') {
        displayMessage('Enter valid phone.', 'error');
        document.getElementById('phone').focus();
        return false;
      }
      if (!/^\d{10}$/.test(phone)) {
        displayMessage('Phone number must be 10 digits.', 'error');
        document.getElementById('phone').focus();
        return false;
      }
      return true;
    }

    function validateUsername() {
      var username = document.getElementById('username').value.trim();
      if (username === '') {
        displayMessage('Username cannot be empty.', 'error');
        document.getElementById('username').focus();
        return false;
      }
      return true;
    }

    function validatePassword() {
      event.preventDefault();
      var currentPassword = document.getElementById('currentPassword').value.trim();
      var newPassword = document.getElementById('newPassword').value.trim();
      var confirmPassword = document.getElementById('confirmPassword').value.trim();

      if (currentPassword === '' || newPassword === '' || confirmPassword === '') {
        //displayMessage('All password fields must be filled out.', 'error');
        if (currentPassword === '') {
          document.getElementById('currentPassword').focus();
        } else if (newPassword === '') {
          document.getElementById('newPassword').focus();
        } else {
          document.getElementById('confirmPassword').focus();
        }
        return false;
      }

      if (newPassword !== confirmPassword) {
        displayMessage('New password and confirm password do not match.', 'error');
        document.getElementById('newPassword').focus();
        return false;
      }

      return true;
    }

    function displayMessage(message, type) {
      var div = document.createElement('div');
      div.className = 'message ' + type;
      div.textContent = message;
      document.body.insertBefore(div, document.body.firstChild);
      setTimeout(function() {
        div.style.display = 'none';
      }, 5000); // Hide message after 3 seconds
    }
  </script>

</body>
</html>
