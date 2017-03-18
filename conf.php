<?php

$curr = $_GET['curr'];
$hostName = "localhost";
$userName = "root";
$password = "password";
$dbName = "acc_" . $curr;

#print "|";
#echo $_GET["$curr"];
#print "*****";
#print "$curr";
#print "n";
#print "$dbName";
#print "n";

#$dbName="acc_usd";
#var_dump($_GET);
#exit;

// make connection to database
mysql_connect($hostName, $userName, $password) or die("Unable to connect to host $hostName");

mysql_select_db($dbName) or die( "Unable to select database $dbName");


date_default_timezone_set("America/New_York");

?>
