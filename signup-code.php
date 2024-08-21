<?php
require 'config/function.php';
if(isset($_POST['signup']))
{
    $usernameInput = validate($_POST['username']);
    $nameInput = validate($_POST['name']);
    $emailInput = validate($_POST['email']);
    $phoneInput = validate($_POST['phone']);
    $passwordInput = validate($_POST['password']);

    $username = filter_var($usernameInput,FILTER_SANITIZE_STRING);
    $name = filter_var($nameInput,FILTER_SANITIZE_STRING);
    $email = filter_var($emailInput,FILTER_SANITIZE_EMAIL);
    $phone = filter_var($phoneInput,FILTER_SANITIZE_STRING);
    $password = filter_var($passwordInput,FILTER_SANITIZE_STRING);
    

    if($username != '' && $name != '' &&  $email != '' &&  $phone != '' && $password != '')
    {
        $sql = "INSERT INTO users (username , name , email , phone , password) VALUES ('$username' , '$name' , '$email' , '$phone' , '$password')";
        $result = mysqli_query($conn, $sql);
        if($result)
        {
            redirect('loginform.php','Signed Up Successfully. Please log in.');
        }
        else
        {
            redirect('loginform.php','Something went wrong');
        }
    }
    else
    {
        redirect('loginform.php','All fields are mandatory');
    }
}
?>
