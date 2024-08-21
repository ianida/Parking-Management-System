<?php
require '../config/function.php';

$_SESSION=array();
session_destroy();
header("location:../loginform.php");