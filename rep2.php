<?php
require_once("menu.php");
require_once("conf.php");


# fill the droplist list
$result = $mysqli->query("select  id, name from items ORDER BY orderby");
$counter=1;
while ($row = $result->fetch_assoc() ) {
    $data[$counter][1]= $row['id'];
    $data[$counter][2]= $row['name'];
    $counter++;
}

?>
<br><br><br>
<form name="form" action="<?php echo $PHP_SELF;?>" method="post" enctype="multipart/form-data">
<fieldset>
<legend>Report</legend>
<?php 
    echo "    <td>  <select name=\"dt1\"> ";

    for ($i=1;$i<$counter;$i++) {
        echo "<option value=\"".$data[$i][1]."\"";
        if ($data[$i][1] == $_GET['account'] ) { 	
  	  echo "selected=\"selected\" "; 
        }
        echo ">" . $data[$i][2] . "</option>";
    }
    echo "</select> </td>";

?>
    &nbsp;&nbsp;&nbsp; 
   <b> From date</b>: 
	<input type="text"   name="from" value= "<?php echo date("Y-m")."-01"; ?>" size=10 maxlength=10  style="background: #FFFFCC;" > &nbsp;&nbsp;&nbsp; 
   <b> To date</b>: 
	<input type="text"   name="to"  value= "<?php echo date("Y-m-d"); ?>" size=10 maxlength=10 style="background: #FFFFCC;" >
	<input type="checkbox" name="table" value="yes" checked> Table &nbsp;&nbsp;
	<input type="checkbox" name="graph" value="yes" > Chart &nbsp;&nbsp;
	<input type="checkbox" name="rel"   value="yes" > Relative &nbsp;&nbsp;
	<input type="checkbox" name="diff"  value="yes" > Differential &nbsp;&nbsp;
	<input type="submit"   name="send"  value="Generate" autofocus>
<br><br>

</fieldset>
</form>


<?php

$acnt = $_POST['dt1'];

$query="select name, notes from items WHERE id=". $acnt ;
$result = $mysqli->query($query);
$row = $result->fetch_assoc();
$name=$row['name'];
$notes=$row['notes'];

#get start ballance for the account
$query="
    SELECT sum(amount) as amount from ledger 
    WHERE ledger.date<=\"". $_POST['to']."\" and ledger.item_dt=\"" . $acnt . "\" ";

$result = $mysqli->query($query);
$row = $result->fetch_assoc();
$dt_turn=$row['amount'];

$query="
  SELECT sum(amount) as amount from ledger 
  WHERE ledger.date<=\"". $_POST['to']."\" and  ledger.item_ct=\"" . $acnt . "\" ";

$result = $mysqli->query($query);
$row = $result->fetch_assoc();
$ct_turn=$row['amount'];


$start_saldo=$dt_turn-$ct_turn;
$start_saldo1=$dt_turn-$ct_turn;
$start_saldo1=0;

if ($_POST['rel'] == "yes" ) { 
	$start_saldo1=0;
} else {
	$start_saldo1=$dt_turn-$ct_turn;
}

$query="
  SELECT sum(amount) as amount from ledger 
  WHERE ledger.date>=\"". $_POST['from']."\" and ledger.date<=\"". $_POST['to']."\" and ledger.item_dt=\"" . $acnt . "\" ";

$result = $mysqli->query($query);
$row = $result->fetch_assoc();
$dt_turn=$row['amount'];

$query="
  SELECT sum(amount) as amount from ledger 
  WHERE ledger.date>=\"". $_POST['from']."\" and ledger.date<=\"". $_POST['to']."\" and  ledger.item_ct=\"" . $acnt . "\" ";

$result = $mysqli->query($query);
$row = $result->fetch_assoc();
$ct_turn=$row['amount'];



$query = "
  SELECT ledger.id as id,  t1.name as name_dt, ledger.amount, t2.name as name_ct, date, time, created, accounted, text, ledger.item_dt as item_dt
  FROM items t1, items t2, ledger 
  WHERE     t1.id=ledger.item_dt 
	and t2.id=ledger.item_ct 
	and ledger.date>=\"". $_POST['from']."\" 
	and ledger.date<=\"". $_POST['to']."\" 
	and (ledger.item_dt=\"" . $acnt . "\" 
	 or ledger.item_ct=\"" . $acnt . "\") 
  ORDER BY 
	ledger.date desc,ledger.id desc;";

$result = $mysqli->query($query);
$rowCount = mysqli_num_rows($result);


if ($_POST['table'] == "yes" and $rowCount > 0 ){ 
	echo "<table class=\"table table-bordered tablesorter\"> 
		<caption> 
			STATEMENT OF ACCOUNT:  $name &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;from: " . $_POST['from'] . " to: " . $_POST['to'] . "&nbsp;&nbsp;&nbsp;&nbsp; $rowCount rows 
		</caption> ";

$i=1;
if ($result = $mysqli->query($query) ) {
   echo "<thead><tr align=\"center\"> 
	<th> # </th> 
	<th>Item DT</th>  
	<th> Amount </th> 
	<th>Item CT</th> 
	<th> Date </th> 
	<th>Text</th>  
	<th>Status</th> 
	<th> Created</th>  
	<th>Last Modified</th> 
	<th>Balance</th> 
      </tr></thead>";

 $output = fopen('data.csv', 'w');

// output the column headings
 fputcsv($output, array('#', 'Item DT', 'Amount', 'Item CT', 'Date', 'Last Modified', 'Created', 'Stat', 'Text'));

  while ($row = $result->fetch_assoc() ) {
	fputcsv($output, $row);
        if ($i%2 ==0 ) {
                 echo "<tr style=\"background: #eeeeee;\" >";
          } else {
                 echo "<tr style=\"background: #cccccc;\" >";
        }
        $i++;
	 echo  "<td width=\"20\"> <a href=\"entry.php?order=" . $row['id'] . "&curr=" . $_GET['curr'] . "\">". $row['id'] ."</a></td>
	 	<td width=\"120\"> " . $row['name_dt'] . "</td>
		<td width=\"70\" align=\"right\"> " . number_format($row['amount'],2) . "</td>
		<td width=\"120\"> " . $row['name_ct'] . "</td>
		<td width=\"100\" align=\"center\"> " . $row['date'] . "</td>
		<td width=\"400\"> " . ($row['text']=="" ? ".": stripslashes($row['text'])) . "</td>
		<td width=\"10\" align=\"center\"> "; 				
	 	if  ($row['accounted'] == "1") {
	 		echo '<img src="images/checkmark.png" width="23" height="23" alt="" />';
	 		} else {
			echo '<img src="images/red-x.png" width="20" height="20" alt="" />';
	 		}
	 echo 		" </td>
		<td align=\"center\"> <h6>" . $row['created'] . "</h6></td>
		<td align=\"center\"> <h6>" . $row['time']    . "</h6></td>";

	 echo "<td align=\"right\"> <h6>" . number_format($start_saldo,2) .  " </h6></td>";
         if ( $acnt ==  $row['item_dt'] ) {
            $start_saldo = $start_saldo - $row['amount'];
         } else {
            $start_saldo = $start_saldo + $row['amount'];
         }
	 echo "</tr>";
	
} 

} else { echo " <hr> no records found! <hr> ";}
fclose($output);

echo "</table>
	<pre>
Turnover DT: " .  number_format($dt_turn,2) ."
Turnover CT: " .  number_format($ct_turn,2) ."
     Amount: " .  number_format($dt_turn - $ct_turn,2) . "
<br><br>
Notes: 
" . $name . ": " . $notes . "
</pre>";
}
?>
<a href="data.csv" target="_blank">
<input type="button" class="button" value="Export CSV" />
</a>
<?


?>

<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript">
google.load("visualization", "1", {packages:["corechart"]});
google.setOnLoadCallback(drawChart);
function drawChart() {
var data = google.visualization.arrayToDataTable([

<?php
 echo "['Time', '" . $name ."' ],"; 

 
$i=1;


$result = $mysqli->query($query);
if ($row = $result->fetch_assoc() ) {
   do {
        $i++;
	echo  "['" . $row['date'] . "',";
        if ( $acnt ==  $row['item_dt'] ) {
            $start_saldo1 = $start_saldo1 - $row['amount'];
        } else {
            $start_saldo1 = $start_saldo1 + $row['amount'];
        }
	if ($_POST['diff'] == "yes" ) { 
		echo abs($row['amount']) . "],";
	} else {
		echo $start_saldo1 . "],";
	}

    } while($row = $result->fetch_assoc());

} else { echo " <hr> no records found! <hr> ";}
?>
]);
 
var options = {
	curveType: 'function', 
	hAxis: {direction: -1},
	title: <?php echo "'" . $name . "'"; ?>  ,vAxis: { title: "" }

};
 
var chart = new google.visualization.LineChart(document.getElementById('chart_div'));
chart.draw(data, options);
}
</script>


<?php
if ($_POST['graph'] == "yes" ) { 
	echo '<div id="chart_div" style="width: 900px; height: 500px;"></div>';
}
?>

