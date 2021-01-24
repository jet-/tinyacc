<?php
require_once("menu.php");
require_once("conf.php");


$query = "
    SELECT ledger.id as id,  t1.name as name_dt, ledger.amount, t2.name as name_ct, date, time, created, accounted, texts.text as text 
    FROM items t1, items t2, ledger 
    LEFT JOIN texts on ledger.id=texts.docnum 
    WHERE t1.id=ledger.item_dt and t2.id=ledger.item_ct and ledger.item_dt=ledger.item_ct 
    ORDER BY ledger.date desc,ledger.id desc";


#$result = $mysqli->query($query);

echo "<table class=\"table table-bordered tablesorter\">";
echo "<caption> List of transactions with Dt account == Ct account";
echo "</caption> ";



$i=1;

if ($result = $mysqli->query($query) ) {

echo "<tr> 
        <th> # </th> 
        <th>Item DT</th>  
        <th> amount </th> 
        <th>Item CT</th> 
        <th> Date </th> 
        <th width=670>Text</th>  
        <th>Stat</th> 
        <th width=25 > Created</th>  
        <th width=25 >Last Modified</th> 
      </tr>";

  while($row = $result->fetch_assoc() ) {

	echo "<tr>";
if ($i%2 ==0 ) {
	 echo "<tr style=\"background: #eeeeee;\" >";
} else {
	 echo "<tr style=\"background: #cccccc;\" >";
}
$i++;
	 echo "<td width=\"20\"> <a href=\"entry.php?order=" . $row['id'] .  "&curr=" .   $_GET['curr'] . "\">". $row['id'] ."</a> </td>";
	 echo "<td width=\"120\"> " . $row['name_dt'] . " </td>";
	 echo "<td width=\"70\" align=\"right\"> " . number_format($row['amount'],2) . " </td>";
	 echo "<td width=\"120\"> " . $row['name_ct'] . " </td>";
	 echo "<td width=\"100\" align=\"center\"> " . $row['date'] . " </td>";
	 echo "<td width=\"400\"> " . ($row['text']=="" ? ".": $row['text']) . " </td>";
	 echo "<td width=\"10\" align=\"center\"> "; 				
	 	if  ($row['accounted'] == "1") {
	 		echo '<img src="images/checkmark.png" width="26" height="26" alt="" />';
	 		} else {
			echo '<img src="images/red-x.png" width="24" height="24" alt="" />';
	 		}
	 echo 		" </td>";         
	 echo "<td align=\"center\"> <h6>" . $row['created'] . " </h6></td>";
	 echo "<td align=\"center\"> <h6>" . $row['time'] . " </h6></td>";
	 echo "</tr>";

  }

} else { echo " <hr> no records found! <hr> ";
}
echo "</table>";
echo "<br><br>";


$mysqli->close();
?>
