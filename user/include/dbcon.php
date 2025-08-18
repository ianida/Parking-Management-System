<!-- <?php
        /*$dbhost = "localhost";
        $dbuser = "root";
        $dbpass = "";
        $dbname = "parkingdb";
        //database connection
        global $conn ;
        $conn= mysqli_connect($dbhost, $dbuser, $dbpass,$dbname);*/
?> -->


<?php
$dbhost = "localhost";
$dbuser = "root";
$dbpass = "";
$dbname = "parkingdb";

// Database connection
global $conn;
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}


// Set default timezone to Nepal Time
date_default_timezone_set('Asia/Kathmandu');

?>
