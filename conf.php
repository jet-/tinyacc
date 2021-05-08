<?php

$curr = $_GET['curr'];
if (empty($_GET['curr'])) {
    $curr='usd';
}


// Fill these variables
$hostName = "localhost";
$userName = "tinyacc";
$password = "password";
$dbName = "acc_" . $curr;
date_default_timezone_set("America/New_York");
// till here



$mysqli = new mysqli($hostName, $userName, $password, $dbName) or die("Unable to connect to host $hostName");
?>
