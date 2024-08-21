<?php
        $dbhost = "localhost";
        $dbuser = "root";
        $dbpass = "";
        $dbname = "parkingdb";
        //database connection
        global $conn ;
        $conn= mysqli_connect($dbhost, $dbuser, $dbpass,$dbname);
?>