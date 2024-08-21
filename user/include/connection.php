<?php

if (isset($_POST['login'])) {
    $dbhost = "localhost";
    $dbuser = "root";
    $dbpass = "";
    $dbname = "parkingdb";
    //database connection
    global $conn ;
    $conn= mysqli_connect($dbhost, $dbuser, $dbpass,$dbname);
    if($conn){
        //echo"Database connected successfully";
    }else {
        echo"connection failed";
    }
    }

if (isset($_POST['signup'])) {
    $dbhost = "localhost";
    $dbuser = "root";
    $dbpass = "";
    $dbname = "parkingdb";
    $conn = mysqli_connect($dbhost, $dbuser, $dbpass,$dbname); 
    if($conn){
        //echo"Database connected successfully";
    }else {
        echo"connection failed";
    }
}
?>