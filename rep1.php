<?php

require_once("conf.php");
require_once("menu.php");


?>
<br><br><br>
<form name="form" action="<?php echo $PHP_SELF;?>" method="post" enctype="multipart/form-data">
<fieldset>
<legend>Report</legend>

    <br> <b> From date</b>: <input class="mydate1" type="text" name="from" value= "<?php echo date("Y-m")."-01"; ?>" size=10 maxlength=10 style="background: #FFFFCC;"> &nbsp;&nbsp;&nbsp;
    <b> To date</b>:  <input class="mydate2" type="text"     name="to"      value= "<?php echo date("Y-m-d"); ?>"size=10 maxlength=10  style="background: #FFFFCC;" >
	              <input type="checkbox" name="full_ov" value="yes" > Balance for the period &nbsp;&nbsp;
	              <input type="checkbox" name="show_ledger" value="yes" checked > Show Ledger &nbsp;&nbsp;
	              <input type="checkbox" name="show_balance" value="yes" checked> Show Balance &nbsp;&nbsp;
		      <input type="submit"   name="send"    value="Generate" autofocus >
<br><br>
</fieldset>
</form>
<?php
	$query ="
	  SELECT 
		ledger.id AS id,  t1.name AS name_dt, ledger.amount, t2.name AS name_ct, date, time, created, accounted, text
	  FROM 
		items t1, items t2, ledger
	  WHERE 
		t1.id=ledger.item_dt AND t2.id=ledger.item_ct AND ledger.date>=\"". $_POST['from']."\" AND ledger.date<=\"". $_POST['to']."\" 
	  ORDER BY 
		ledger.date desc,ledger.id desc;";

	$result = $mysqli->query($query);
	$rowCount = mysqli_num_rows($result);


       if ($_POST['show_ledger'] == "yes" and $rowCount > 0 ) { 
	  echo "<table class=\"table table-bordered tablesorter\">
		<caption> GENERAL LEDGER &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;from: {$_POST['from']} to: {$_POST['to']}
		&nbsp;&nbsp;&nbsp;&nbsp; $rowCount rows </caption> ";
	  $i=1;
	  if ($result = $mysqli->query($query) ) {

	  echo "<thead><tr> 
		<th> # </th> 
		<th>Item DT</th>  
		<th> Amount </th> 
		<th>Item CT</th> 
		<th> Date </th> 
		<th width=500>Text</th>  
		<th>Stat</th> 
		<th width=25 > Created</th>  
		<th width=25 >Last Modified</th> 
	      </tr></thead>";


	 $output = fopen('data.csv', 'w');

	 // output the column headings
	 fputcsv($output, array('#', 'Item DT', 'Amount', 'Item CT', 'Date', 'Last Modified', 'Created', 'Stat', 'Text'));


	  while($row = $result->fetch_assoc() ) {
		fputcsv($output, $row);
		echo "<tr>";
		if ($i%2 ==0 ) {
			 echo '<tr style="background: #eeeeee;" >';
		  } else {
			 echo '<tr style="background: #cccccc;" >';
		}
		$i++;
		echo "<td width=\"20\"> <a href=\"entry.php?order=" . $row['id'] . "&curr=" . $_GET['curr'] ."\">". $row['id'] .		"</a></td>
		 	<td width=\"140\">" . $row['name_dt'] . "</td>
		 	<td width=\"70\" align=\"right\">" . number_format($row['amount'],2) . "</td>
		 	<td width=\"140\">" . $row['name_ct'] . "</td>
		 	<td width=\"100\" align=\"center\">" . $row['date'] . "</td>
		 	<td width=\"400\"> " . ($row['text']=="" ? ".": stripslashes($row['text'])) . "</td>
		 	<td width=\"10\" align=\"center\">"; 				
		 	if  ($row['accounted'] == "1") {
		 		echo '<img src="images/checkmark.png" width="23" height="23" alt="" />';
		 		} else {
				echo '<img src="images/red-x.png" width="20" height="20" alt="" />';
		 		}
		echo 		" </td>
		  <td align=\"center\"> <h6>" . $row['created'] . " </h6></td>
		  <td align=\"center\"> <h6>" . $row['time']    . " </h6></td>
		  </tr>";
	  } 

	} else { echo " <hr> no records found! <hr> ";}
	fclose($output);
	echo "</table>
		<br><br>";
?>
<a href="data.csv" target="_blank">
<input type="button" class="button" value="Export CSV" />
</a>
<?
}



if ($_POST['show_balance'] == "yes" ) { 
	#ov
	#type_: L - razhod; A - prihod
	$result = $mysqli->query("SELECT  id, name, type, liquidity, orderby FROM items ORDER by orderby ");
	$counter=1;
	while ($row0 = $result->fetch_assoc() ) {
	    $data[$counter][1]= $row0['name'];
	    $data[$counter][4]= $row0['type'];
	    $data[$counter][7]= $row0['liquidity'];
	    $data[$counter][8]= $row0['orderby'];
	    $data[$counter][11]= $row0['id'];
	    $counter++;
	}

	if ($_POST['full_ov'] <> "yes" ) { 
	  $_POST['from']="2000-01-01";
	}


	#filling $data[$i][2] credit turnover
	$result = $mysqli->query("
	    SELECT items.name AS name, sum(ledger.amount) AS amnt
	    FROM items
	    LEFT JOIN  ledger ON ledger.item_dt=items.id and ledger.date<=\"" . $_POST['to'] . "\" 
		      and ledger.date>=\"" . $_POST['from'] . "\" and accounted 
	    GROUP BY items.id
	    ORDER BY orderby ");

	$counter=1;
	while ($row1 = $result->fetch_assoc() ) {
	    $data[$counter][2]= $row1['amnt'];
	    $counter++;
	}

	#filling $data[$i][3] debit turnover
	$result = $mysqli->query("
	    SELECT items.name AS name, sum(ledger.amount) AS amnt
	    FROM items
	    LEFT JOIN  ledger ON ledger.item_ct=items.id and ledger.date<=\"" . $_POST['to'] . "\" 
		      and ledger.date>=\"" . $_POST['from'] . "\"  and  accounted 
	    GROUP BY items.id
	    ORDER BY orderby ");

	$counter=1;
	while ($row2 = $result->fetch_assoc() ) {
	    $data[$counter][3]= $row2['amnt'];
	    $counter++;
	}

	#filling $data[$i][5] yearly debit turnover
	$result = $mysqli->query("
	    SELECT  sum(ledger.amount) AS amnt
	    FROM items
	    LEFT JOIN ledger on ledger.item_dt=items.id and YEAR(ledger.date)=\"" . date("Y",strtotime($_POST['to'])) . "\"  and accounted 
	    GROUP BY items.id
	    ORDER BY orderby ");

	$counter=1;
	while ($row1 = $result->fetch_assoc() ) {
	    $data[$counter][5]= $row1['amnt'];
	    $counter++;
	}


	#filling $data[$i][6] yearly credit turnover
	$result = $mysqli->query("
	      SELECT  sum(ledger.amount) as amnt
	      FROM items 
	      LEFT JOIN  ledger on ledger.item_ct=items.id and YEAR(ledger.date)=\"" . date("Y",strtotime($_POST['to'])) . "\"  and accounted
	      GROUP BY items.id
	      ORDER BY orderby ");


	$counter=1;
	while ($row1 = $result->fetch_assoc() ) {
	    $data[$counter][6]= $row1['amnt'];
	    $counter++;
	}

	# $data[][1] - item name
	#	 [2] - total turnover DT 
	#	 [3] - total turnover CT
	#	 [4] - item type Asset / Liability
	#	 [5] - yearly turnover DT
	#	 [6] - yearly turnover CT
	#	 [7] - liquidity +/-
	#	 [8] - orderby
	#	[11] - account id

	echo "<table  class=\"ref\"  bgcolor=\"#DDD4FF\">
		<caption> 
			BALANCE SHEET &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; from: ". $_POST['from'] . "&nbsp;&nbsp; to: ". $_POST['to'] . "
		</caption>
			<tr>   
				<th> Item </th>  
				<th> Debit Turnover </th>  
				<th> Credit Turnover </th> 
				<th> Debit Turnover <br> annual </th>
				<th> Credit Turnover <br>annual </th>
				<th> Amount </th>   
				<th>liab.</th>  
				<th>asset</th>   
				<th></th> 
				<th></th> 
			</tr>";

	$prihod=0;
	$razhod=0;
	for ($i=0;$i<$counter;$i++) {
		if ($data[$i][4] == "L" ) { 
		    $razhod += ($data[$i][3] - $data[$i][2]);
		}

		if ($data[$i][4] == "A" ) { 
		    $prihod += ($data[$i][3] - $data[$i][2]);
		}
	}

	$liq=0;

	for ($i=0;$i<$counter;$i++) {
	    if ($data[$i][2] <> 0 or $data[$i][3] <> 0) {
		echo "<tr>
			<td> <a href=\"rep2.php?account=" . number_format($data[$i][11],0) . "&curr=" . $_GET['curr'] . "\">". $data[$i][1] . " </a></td>
		  	<td align=\"right\">" . number_format($data[$i][2],2) . "</td>
		  	<td align=\"right\">" . number_format($data[$i][3],2) .	"</td>
		  	<td align=\"right\">" . number_format($data[$i][5],2) .	"</td>
		  	<td align=\"right\">" . number_format($data[$i][6],2) .	"</td>
		  	<td align=\"right\" style=\"background: #eeeeee;\" >" . number_format($data[$i][3] - $data[$i][2],2) . "</td> ";

		if ($data[$i][4] == "L" ) { 
		  echo "<td align=\"right\">" .  number_format(-100 * $data[$i][2]/$razhod,0) . "%</td>
		  	<td align=\"center\"> - </td>";
		}

		if ($data[$i][4] == "A" ) { 
		  echo "<td align=\"center\"> - </td>
		  	<td align=\"right\">" .  number_format(100 * $data[$i][3]/$prihod,0 ) . "%</td>";
		}
		if ($data[$i][4] == "" ) { 
		  echo "<td align=\"center\"> - </td>
		  	<td align=\"center\"> - </td>";
		}
	 	  echo "<td align=\"center\" style=\"border:0px solid black\">" .  ($data[$i][7] ) . "</td>
		 	<td align=\"center\" style=\"border:0px solid black\">" .  ($data[$i][4] ) . "</td>";
	       echo "</tr>";
	    }
		if ($data[$i][7] == "-" ) { 
		    $liq -= ($data[$i][3] - $data[$i][2]);
		}

		if ($data[$i][7] == "+" ) { 
		    $liq += (-1)*($data[$i][3] - $data[$i][2]);
		}
	}

	echo "<b><tr>
	<td align=\"center\">TOTAL</td>
	<td align=\"right\">" . number_format( array_sum(array_column($data,2)),2) . "</td>
	<td align=\"right\">" . number_format( array_sum(array_column($data,3)),2) . "</td>
	<td align=\"right\">" . number_format( array_sum(array_column($data,5)),2) . "</td>
	<td align=\"right\">" . number_format( array_sum(array_column($data,6)),2) . "</td>
	<td></td> <td></td> <td></td>
	</tr></b>";

	echo "</table>
		<br><pre>
	 Revenue:  "   	. number_format($prihod,2) . "
	Expenses: "  	. number_format($razhod,2) . "
	  Equity:  "  	. number_format($prihod + $razhod,2) . "

	  Profit:   " 	. number_format($liq,2) . "</pre>";
}

$mysqli->close();
?>



