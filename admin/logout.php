<?php

require '../config/function.php';

if(isset($_SESSION['auth']))
{
    logoutSession();
    redirect('loginform.php','Logged Out Successfully');
}

?>