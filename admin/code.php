<?php
require '../config/function.php';


if(isset($_POST['saveuser']))
{
    $name = validate($_POST['name']);
    $username = validate($_POST['username']);
    $email = validate($_POST['email']);
    $phone = validate($_POST['phone']);
    $password = validate($_POST['password']);
    $role = validate($_POST['role']);

    if ($name !='' || $email !='' || $phone !='' || $password !='')
    {
        $query = "INSERT INTO users (name,username,phone,email,password,role) 
        VALUES ('$name','$username','$email','$phone','$password','$role')";
        $result = mysqli_query($conn,$query);

        if($result){
            redirect('users.php','User/Admin Added Successfully');
        }else{
            redirect('users-create.php','Something went wrong');
        }
    }
    else{
        redirect('users-create.php','Please fill all the input fields');
    }

}

if(isset($_POST['updateuser']))
{
    $name = validate($_POST['name']);
    $username = validate($_POST['username']);
    $email = validate($_POST['email']);
    $phone = validate($_POST['phone']);
    $password = validate($_POST['password']);
    $role = validate($_POST['role']);

    $userId=validate($_POST['userId']);
    $user = getById('users',$userId);
    if($user['status'] != 200)
    {
        redirect('users-edit.php?id='.$userId,'No such id found');
    }

    if ($name !='' || $email !='' || $phone !='' || $password !='')
    {
        $query = "UPDATE users SET 
        name='$name',
        username='$username',
        phone='$phone',
        email='$email',
        password='$password',
        role='$role'
        WHERE id='$userId' ";
        $result = mysqli_query($conn,$query);

        if($result){
            redirect('users.php','User/Admin updated Successfully');
        }else{
            redirect('users-create.php','Something went wrong');
        }
    }
    else{
        redirect('users-create.php','Please fill all the input fields');
    }
}





?>