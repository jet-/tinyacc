<?php
function printday($caption, $query)
{

$result = mysql_query ($query);


echo "<table class=ref>  ";
echo "<caption> ". $caption;
echo "</caption> ";
$i=1;
if ($row = mysql_fetch_array($result)) {

echo "<tr> 
        <th> Date </th> 
        <th> Time </th> 
        <th> Subject </th> 
        <th width=670>Text</th>
      </tr>";

  do {
#	echo "<tr>";
	if ($i%2 ==0 ) {
		 echo '<tr style="background: #eeeeee;" >';
	  } else {
		 echo '<tr style="background: #cccccc;" >';
	}
	$i++;
	 echo "<td width=\"70\" align=\"right\"> " 		. substr($row['start_ts'],0,10) . " </td>";
	 echo "<td width=\"120\"> " 				. $row['start_ts'] 		. " </td>";
	 echo "<td width=\"120\"> "				. $row['subject'] 		. " </td>";
	 echo "<td width=\"420\"> " 				. $row['description'] 		. " </td>";
	 echo 		" </td>";         
	 echo "</tr>";
  } while($row = mysql_fetch_array($result));

} else { 
	#echo " <hr> no records found! <hr> ";
	}

echo "</table>";
echo "<br><hr><br>";
}

require_once("conf.php");
require_once("menu.php");

$dbName = "calendar";
mysql_select_db($dbName) or die( "Unable to select database $dbName");


#Dnes
$query = "SELECT 	t1.eid, end_date, start_ts, end_ts, sent_dnes, sent_utre,
				t2.eid, subject, description".
			" FROM phpc_occurrences t1 LEFT JOIN phpc_events t2 ".
			" ON t1.eid=t2.eid ".
			" WHERE  substring(t1.start_ts,1,10)=\"". date("Y-m-d") ."\" ".
			" LIMIT 1;";

printday("TODAY", $query);


$query = "SELECT 	t1.eid, end_date, start_ts, end_ts, sent_dnes, sent_utre,
				t2.eid, subject, description".
			" FROM phpc_occurrences t1 LEFT JOIN phpc_events t2 ".
			" ON t1.eid=t2.eid ".
			" WHERE  TO_DAYS(substring(t1.start_ts,1,10)) - TO_DAYS(\"". date("Y-m-d") . "\")=\"1\"".
			" LIMIT 1;";

printday("TOMORROW", $query);

$query = "SELECT 	t1.eid, end_date, start_ts, end_ts, sent_vcera,
				t2.eid, subject, description".
			" FROM phpc_occurrences t1 LEFT JOIN phpc_events t2 ".
			" ON t1.eid=t2.eid ".
			" WHERE  TO_DAYS(\"". date("Y-m-d") . "\") - TO_DAYS(substring(t1.start_ts,1,10))=\"1\"".
			" LIMIT 1;";

printday("YESTERDAY", $query);

$query = "SELECT 	t1.eid, end_date, start_ts, end_ts, sent_dnes, sent_utre,
				t2.eid, subject, description".
			" FROM phpc_occurrences t1 LEFT JOIN phpc_events t2 ".
			" ON t1.eid=t2.eid ".
			" WHERE  TO_DAYS(substring(t1.start_ts,1,10)) - TO_DAYS(\"". date("Y-m-d") . "\")<\"120\" and".
			" TO_DAYS(substring(t1.start_ts,1,10)) - TO_DAYS(\"". date("Y-m-d") . "\")>\"0\"" .
			" ORDER by start_ts";

printday("SOON", $query);


mysql_close();

?>
