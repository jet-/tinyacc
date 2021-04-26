<?php

$curr = $_GET['curr'];
$hostName = isset($_ENV['TINYACC_DB_HOSTNAME']) ? $_ENV['TINYACC_DB_HOSTNAME'] : 'localhost';
$userName = isset($_ENV['MYSQL_USER']) ? $_ENV['MYSQL_USER'] : 'tinyacc';
$password = isset($_ENV['MYSQL_PASSWORD']) ? $_ENV['MYSQL_PASSWORD'] : 'password';
$dbName = "acc_" . $curr;

// make connection to database
// mysqli
$mysqli = new mysqli($hostName, $userName, $password, $dbName) or die("Unable to connect to host $hostName");



date_default_timezone_set("America/New_York");

?>
