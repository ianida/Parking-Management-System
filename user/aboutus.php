<?php
session_start();
if (!isset($_SESSION['id'])) {
    header("Location:../loginform.php");
    exit();
}


include('include/header.php');
include('include/dbcon.php');

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>About Us</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 80%;
            margin: 50px auto;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
        }

        h4 {
            color: #007BFF;
            font-size: 24px;
            text-align: center;
            margin-bottom: 20px;
        }

        p {
            font-size: 16px;
            line-height: 1.6;
            color: #333;
            text-align: justify;
        }

        .mission {
            background-color: #f0f0f0;
            padding: 20px;
            border-radius: 5px;
            margin-top: 30px;
        }

        .mission h5 {
            color: #007BFF;
            font-size: 20px;
            margin-bottom: 10px;
        }

        .cta {
            text-align: center;
            margin-top: 30px;
        }

        .cta a {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007BFF;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .cta a:hover {
            background-color: #0056b3;
            
        }
    </style>
</head>
<body>
    <div class="container">
        <h4>About Us</h4>
        <p>
            At Parking Management System, we're passionate about making parking a breeze. Our innovative management system is designed to simplify the parking experience for drivers, property owners, and operators alike. With our cutting-edge technology, you can easily find, reserve, and pay for parking spots, reducing congestion and frustration.
        </p>
        <div class="mission">
            <h5>Our Mission</h5>
            <p>
                Our mission is to provide a seamless, efficient, and sustainable parking solution that benefits everyone involved. We believe that parking should be a convenience, not a chore. That's why we're dedicated to delivering a user-friendly, data-driven platform that streamlines parking operations and enhances the overall parking experience.
            </p>
        </div>
        <div class="cta">
            <a href="#" target="_blank">Join the parking revolution with us!</a>
        </div>
    </div>
</body>
</html>
