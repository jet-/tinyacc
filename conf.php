<?php

$curr = $_GET['curr'];
$hostName = "localhost";
$userName = "tinyacc";
$password = "password";
$dbName = "acc_" . $curr;

// make connection to database
// mysqli
$mysqli = new mysqli($hostName, $userName, $password, $dbName) or die("Unable to connect to host $hostName");



date_default_timezone_set("America/New_York");

?>
