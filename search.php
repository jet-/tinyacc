<?php
require_once("menu.php");
require_once("conf.php");
?>


<br>
<form name="form" action="<?php echo $PHP_SELF;?>" method="post" enctype="multipart/form-data">

    &nbsp;&nbsp;&nbsp; <b> Text</b>: 	<input type="text" name="txt" value="<?php echo $_POST['txt'] ; ?>"size=40 maxlength=40  		  style="background: #FFFFCC;"  autofocus>
		<input type="submit" name="send" value="Search">
    <br><br>
</form>

<?php
$query ="SELECT ledger.id AS id,  t1.name AS name_dt, ledger.amount, t2.name AS name_ct, date, time, created, accounted, text 
	FROM items t1, items t2, ledger 
	WHERE t1.id=ledger.item_dt AND t2.id=ledger.item_ct AND text LIKE \"%". $_POST['txt']."%\"  
	ORDER BY ledger.date desc,ledger.id desc;";

$result = $mysqli->query($query);
$rowCount = mysqli_num_rows($result);

if ($_POST['txt'] <> ""){

        echo '<table class="table table-bordered tablesorter">  ';
	echo "<caption>  Search text: &nbsp;&nbsp;&nbsp; \"".$_POST['txt'] . "\"  &nbsp;&nbsp; ". $rowCount . " results found" ;
	echo "</caption> ";

	$i=1;
	$turn = 0;


	if ($result = $mysqli->query($query)) {
	   echo "<thead><tr > <th> </th> <th>Item DT</th>  <th> amount </th> <th>Item CT</th> <th> Date </th> <th>Text</th>  <th>Status</th> <th> Created</th>  <th>Last Modified</th> </tr> </thead>";

	 $output = fopen('data.csv', 'w');

	 // output the column headings
	 fputcsv($output, array('#', 'Item DT', 'Amount', 'Item CT', 'Date', 'Last Modified', 'Created', 'Stat', 'Text'));

  while($row = $result->fetch_assoc()) {
	fputcsv($output, $row);
	echo "<tr>";
	if ($i%2 ==0 ) {
		 echo "<tr style=\"background: #eeeeee;\" >";
	  } else {
		 echo "<tr style=\"background: #cccccc;\" >";
	}
	$i++;

		 echo "<td width=\"20\"> <a href=\"entry.php?order=" . $row['id'] . "&curr=" . $_GET['curr'] . "\">". $row['id'] ." </a></td>";
		 echo "<td width=\"120\"> " . $row['name_dt'] . " </td>";
		 echo "<td width=\"70\" align=\"right\"> " . number_format($row['amount'],2) . " </td>";
		 echo "<td width=\"120\"> " . $row['name_ct'] . " </td>";
		 echo "<td width=\"100\" align=\"center\"> " . $row['date'] . " </td>";
		 echo "<td width=\"400\"> " . ($row['text']=="" ? ".": $row['text']) . " </td>";
         echo "<td width=\"10\" align=\"center\"> "; 				
         if  ($row['accounted'] == "1") {
	 		echo '<img src="images/checkmark.png" width="23" height="23" alt="" />';
	 		} else {
			echo '<img src="images/red-x.png" width="20" height="20" alt="" />';
	 		}
         echo 		" </td>";         
		 echo "<td align=\"center\"> <h6>" . $row['created'] . " </h6></td>";
		 echo "<td align=\"center\"> <h6>" . $row['time'] . " </h6></td>";
		 echo "</tr>";
	
         $turn = $turn + $row['amount'];
	 } 

	echo "</pre>";	} else { echo " <hr> Sorry, no records found! <hr> ";	}
	fclose($output);
	echo "</table>";

}
echo "<pre>";
echo "             Turnover: " .  number_format($turn,2) ." <br>";
echo "</pre><br>";
?>
<a href="data.csv" target="_blank">
<input type="button" class="button" value="Export CSV" />
</a>
<?php
$mysqli->close();

?>
