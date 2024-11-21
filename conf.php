<?php

$curr = $_GET['curr'];
if (empty($_GET['curr'])) {
    $curr='usd';
}


// Fill these variables
$hostName = isset($_ENV['TINYACC_DB_HOSTNAME']) ? $_ENV['TINYACC_DB_HOSTNAME'] : 'localhost';
$userName = isset($_ENV['MYSQL_USER']) ? $_ENV['MYSQL_USER'] : 'tinyacc';
$password = isset($_ENV['MYSQL_PASSWORD']) ? $_ENV['MYSQL_PASSWORD'] : 'password';
$dbName = "acc_" . $curr;
date_default_timezone_set("America/New_York");
// till here



$mysqli = new mysqli($hostName, $userName, $password, $dbName) or die("Unable to connect to host $hostName");
?>
