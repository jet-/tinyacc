<?php

$curr = $_GET['curr'];
$hostName = "localhost";
$userName = "root";
$password = "password";
$dbName = "acc_" . $curr;

// make connection to database
mysql_connect($hostName, $userName, $password) or die("Unable to connect to host $hostName");

mysql_select_db($dbName) or die( "Unable to select database $dbName");


date_default_timezone_set("America/New_York");

?>
