<?php

require_once("conf.php");
require_once("menu.php");


$tables_heather ="
	<table class=\"ref\"  bgcolor=\"#DDD4FF\">
	<tr>
	    <th>Year</th> 
	    <th>January</th> 
	    <th>February</th> 
	    <th>March</th> 
	    <th>April</th> 
	    <th>May</th> 
	    <th>June</th> 
	    <th>July</th> 
	    <th>August</th> 
	    <th>September</th> 
	    <th>October</th> 
	    <th>November</th> 
	    <th>December</th>
	    <th>Total for the Year</th>
	</tr>";


echo $tables_heather;
echo 	"<caption> Monthly Profit </caption>"; 

for ($yyyy=2006;$yyyy<2030;$yyyy++) {
	echo "<tr> <td><b> $yyyy </b></td>";
	$total = 0;
	for ($mm=01;$mm<13;$mm++) {
	        $mm =  str_pad($mm, 2, '0', STR_PAD_LEFT);
		list ($revenue, $expenses) = getmonthly($yyyy, $mm);
		$profit = $revenue - $expenses;
		$total = $total + $profit;
		echo "<td align=\"right\"> " . number_format($profit,0) . "</td>";
        }
	echo "<td align=\"right\"><b>" . number_format($total,2) . "</b></td></tr>";
}
echo "</table>";



echo $tables_heather;
echo 	"<caption> Monthly Revenue </caption>"; 

for ($yyyy=2006;$yyyy<2030;$yyyy++) {
	echo "<tr> <td><b> $yyyy </b></td>";
	$total = 0;
	for ($mm=01;$mm<13;$mm++) {
	        $mm =  str_pad($mm, 2, '0', STR_PAD_LEFT);
		list ($revenue, $expenses) = getmonthly($yyyy, $mm);
		$total = $total + $revenue;
		echo "<td align=\"right\"> " . number_format($revenue,0) . "</td>";
        }
	echo "<td align=\"right\"><b>" . number_format($total,2) . "</b></td></tr>";
}
echo "</table>";


echo $tables_heather;
echo 	"<caption> Monthly Expenses </caption>"; 

for ($yyyy=2006;$yyyy<2030;$yyyy++) {
	echo "<tr> <td><b> $yyyy </b></td>";
	$total = 0;
	for ($mm=01;$mm<13;$mm++) {
	        $mm =  str_pad($mm, 2, '0', STR_PAD_LEFT);
		list ($revenue, $expenses) = getmonthly($yyyy, $mm);
		$total = $total + $expenses;
		echo "<td align=\"right\"> " . number_format($expenses,0) . "</td>";
        }
	echo "<td align=\"right\"><b>" . number_format($total,2) . "</b></td></tr>";
}
echo "</table>";




function getmonthly($yyyy, $mm) {
	global $mysqli;
	$revenue = 0;
	$expenses = 0;
	$query = "SELECT sum(amount) as amnt
	    FROM ledger
	    Left JOIN  items ON ledger.item_ct=items.id
	    WHERE accounted 
		AND DATE_FORMAT(ledger.date,'%Y %m') = \"$yyyy $mm\"
		AND items.type = 'A' ";

	$result = $mysqli->query($query);
	while($row = $result->fetch_assoc() ) {
		$revenue = $row['amnt'];
	}  


	$query = "SELECT sum(amount) as amnt
	    FROM ledger
	    Left JOIN  items ON ledger.item_dt=items.id
	    WHERE accounted 
		AND DATE_FORMAT(ledger.date,'%Y %m') = \"$yyyy $mm\"
		AND items.type = 'L' ";

	$result = $mysqli->query($query);
	while($row = $result->fetch_assoc() ) {
		$expenses = $row['amnt'];
	}  


	return array($revenue, $expenses);
}

$mysqli->close();
?>


