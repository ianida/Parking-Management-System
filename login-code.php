<?php
require 'config/function.php';

if(isset($_POST['login']))
{
    $emailInput = validate($_POST['email']);
    $passwordInput = validate($_POST['password']);

    $email = filter_var($emailInput,FILTER_SANITIZE_EMAIL);
    $password = filter_var($passwordInput,FILTER_SANITIZE_STRING);

    if($email != '' && $password != '')
    {
        // 1. Check user with email and password
        $query = "SELECT * FROM users WHERE email='$email' AND password='$password' LIMIT 1";
        $result = mysqli_query($conn,$query);

        if($result)
        {
            if(mysqli_num_rows($result) == 1)
            {   
                $row = mysqli_fetch_array($result,MYSQLI_ASSOC);

                // 2. Check if user email is verified (no row in email_verifications)
                $userId = $row['id'];
                $verifyCheck = mysqli_query($conn, "SELECT * FROM email_verifications WHERE user_id='$userId'");
                if(mysqli_num_rows($verifyCheck) > 0){
                    // Email NOT verified
                    redirect(BASE_URL . 'loginform.php', 'Please verify your email before logging in.');
                    exit;
                }

                // 3. Email verified, set session and redirect
                $_SESSION['auth'] = true;
                $_SESSION['loggedInUserRole'] = $row['role'];
                $_SESSION['id'] = $row['id'];
                $_SESSION['password'] = $row['password'];
                $_SESSION['phone'] = $row['phone'];
                $_SESSION['loggedInUser'] = [
                    'name' => $row['name'],
                    'email' => $row['email']
                ];

                if($row['role'] == 'admin')
                {   
                    redirect(BASE_URL . 'admin/index.php', 'Logged In Successfully');
                }
                else
                {   
                    redirect(BASE_URL . 'user/index.php', 'Logged In Successfully');
                }
            }
            else
            {
                redirect(BASE_URL . 'loginform.php', 'Invalid Email ID or Password');
            }
        }
        else
        {
            redirect(BASE_URL . 'loginform.php', 'Something went wrong');
        }
    }else
    {
        redirect(BASE_URL . 'loginform.php', 'All fields are mandatory');
    }
}
?>
