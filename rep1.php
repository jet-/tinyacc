<?php

require_once("conf.php");
require_once("menu.php");


?>
<br><br><br>
<form name="form" action="<?php echo $PHP_SELF;?>" method="post" enctype="multipart/form-data">
<fieldset>
<legend>Report</legend>

    <br> <b> From date</b>: <input type="text" name="from" value= "<?= date("Y-m")."-01"; ?>" size=10 maxlength=10 style="background: #FFFFCC;"> &nbsp;&nbsp;&nbsp;
    <b> To date</b>:  <input type="text"     name="to"      value= "<?= date("Y-m-d"); ?>"size=10 maxlength=10  style="background: #FFFFCC;" >
	              <input type="checkbox" name="full_ov" value="yes" > Balance for the period &nbsp;&nbsp;
	              <input type="checkbox" name="show_ledger" value="yes" checked > Show Ledger &nbsp;&nbsp;
	              <input type="checkbox" name="show_balance" value="yes" checked> Show Balance &nbsp;&nbsp;
		      <input type="submit"   name="send"    value="Generate" autofocus >
<br><br>
</fieldset>
</form>
<?php

if ($_POST['show_ledger'] == "yes" ) { 
	$result = mysql_query ("SELECT ledger.id AS id,  t1.name AS name_dt, ledger.ammount, t2.name AS name_ct, date, time, created, accounted, texts.text AS text".
				" FROM items t1, items t2, ledger ".
				" LEFT JOIN texts ON ledger.id=texts.docnum ".
				" WHERE t1.id=ledger.item_dt AND t2.id=ledger.item_ct AND ledger.date>=\"". $_POST['from']."\" AND ledger.date<=\"". $_POST['to']."\" ".
				" ORDER BY ledger.date desc,ledger.id desc;");


	echo "<table class=\"ref\">  ";
	echo "<caption> GENERAL LEDGER &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;from: {$_POST['from']} to: {$_POST['to']}";
	echo "</caption> ";
	$i=1;
	if ($row = mysql_fetch_array($result)) {

	echo "<tr> 
		<th> # </th> 
		<th>Item DT</th>  
		<th> Ammount </th> 
		<th>Item CT</th> 
		<th> Date </th> 
		<th width=500>Text</th>  
		<th>Stat</th> 
		<th width=25 > Created</th>  
		<th width=25 >Last Modified</th> 
	      </tr>";

	  do {
		echo "<tr>";
		if ($i%2 ==0 ) {
			 echo '<tr style="background: #eeeeee;" >';
		  } else {
			 echo '<tr style="background: #cccccc;" >';
		}
		$i++;
		 echo "<td width=\"20\"> <a href=\"entry.php?order=" 	. $row['id'] . "&curr=" .   $_GET['curr'] ."\">". $row['id'] .		"</a></td>";
		 echo "<td width=\"120\"> " 									. $row['name_dt'] . 								" </td>";
		 echo "<td width=\"70\" align=\"right\"> " 				. number_format($row['ammount'],2) . 		" </td>";
		 echo "<td width=\"120\"> " 									. $row['name_ct'] .								" </td>";
		 echo "<td width=\"100\" align=\"center\"> " 			. $row['date'] . 									" </td>";
		 echo "<td width=\"400\"> " 			. ($row['text']=="" ? ".": stripslashes($row['text'])) .		" </td>";
		 echo "<td width=\"10\" align=\"center\"> "; 				
		 	if  ($row['accounted'] == "1") {
		 		echo '<img src="images/checkmark.png" width="23" height="23" alt="" />';
		 		} else {
				echo '<img src="images/red-x.png" width="20" height="20" alt="" />';
		 		}
		 echo 		" </td>";         
		 echo "<td align=\"center\"> <h6>" 							. $row['created'] .						" </h6></td>";
		 echo "<td align=\"center\"> <h6>" 							. $row['time'] . 							" </h6></td>";
		 echo "</tr>";
	  } while($row = mysql_fetch_array($result));

	} else { echo " <hr> no records found! <hr> ";}

	echo "</table>";
	echo "<br><br>";
}


if ($_POST['show_balance'] == "yes" ) { 

	#ov
	#type_: L - razhod; A - prihod
	$result = mysql_query ("select  id, name, type, liquidity, orderby from items ORDER by orderby ");
	$counter=1;
	while ($row0 = mysql_fetch_array($result) ) {
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

	$result = mysql_query ("SELECT items.name AS name, sum(ledger.ammount) AS amnt".
				" FROM items ".
				" LEFT JOIN  ledger ON ledger.item_dt=items.id and ledger.date<=\"" . $_POST['to'] . "\"  ".
									      "and ledger.date>=\"" . $_POST['from'] . "\" and accounted group by items.id ".
				" ORDER BY orderby ");


	$counter=1;
	while ($row1 = mysql_fetch_array($result) ) {
	    $data[$counter][2]= $row1['amnt'];
	    $counter++;
	}

	$result = mysql_query ("SELECT items.name AS name, sum(ledger.ammount) AS amnt ".
				"FROM items ".
				"LEFT JOIN  ledger ON ledger.item_ct=items.id and ledger.date<=\"" . $_POST['to'] . "\" and  accounted ".
									      "and ledger.date>=\"" . $_POST['from'] . "\"  ".
				"GROUP BY items.id".
				" ORDER BY orderby ");

	$counter=1;
	while ($row2 = mysql_fetch_array($result) ) {
	    $data[$counter][3]= $row2['amnt'];
	    $counter++;
	}



	$result = mysql_query ("SELECT  sum(ledger.ammount) AS amnt ".
				"FROM items ".
				"LEFT JOIN ledger on ledger.item_dt=items.id and YEAR(ledger.date)=\"" . date("Y",strtotime($_POST['to'])) . "\"  and accounted ".
				"GROUP BY items.id".
				" ORDER BY orderby ");

	$counter=1;
	while ($row1 = mysql_fetch_array($result) ) {
	    $data[$counter][5]= $row1['amnt'];
	    $counter++;
	}

	$result = mysql_query ("SELECT  sum(ledger.ammount) as amnt ".
				"FROM items ".
				"LEFT JOIN  ledger on ledger.item_ct=items.id and YEAR(ledger.date)=\"" . date("Y",strtotime($_POST['to'])) . "\"  and accounted ".
				"GROUP BY items.id".
				" ORDER BY orderby ");



	$counter=1;
	while ($row1 = mysql_fetch_array($result) ) {
	    $data[$counter][6]= $row1['amnt'];
	    $counter++;
	}

	# $data[][1] - item name
	#	 [2] - oborot DT za cialata baza
	#	 [3] - oborot CT za cialata baza
	#	 [4] - item type Asset / Liability
	#	 [5] - oborot DT za godinata
	#	 [6] - oborot CT za godinata
	#	 [7] - likvidnost +/-
	#	 [8] - orderby
	#	 [9] - % prihod
	#	[10] - % razhod
	#	[11] - account id

	echo "<table  class=\"ref\"  bgcolor=\"#DDD4FF\">";
	echo "<caption> BALANCE SHEET &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; from: ". $_POST['from'] . "&nbsp;&nbsp; to: ". $_POST['to'];
	echo "</caption>   
			<tr>   
					<th> Item </th>  
					<th> Ammount DT </th>  
					<th> Ammount CT </th> 
					<th> Ammount DT<br> annual </th>
					<th> Ammount CT <br>annual </th>
					<th> Ammount </th>   
					<th>liab.</th>  
					<th>asset</th>   
					<th></th> 
					<th></th> 
			</tr>";

	$prihod=0;
	$razhod=0;
	for ($i=1;$i<$counter;$i++) {
		if ($data[$i][4] == "L" ) { 
		    $razhod += ($data[$i][3] - $data[$i][2]);
		}

		if ($data[$i][4] == "A" ) { 
		    $prihod += ($data[$i][3] - $data[$i][2]);
		}
	}

	$liq=0;

	for ($i=1;$i<$counter;$i++) {
	    if ($data[$i][2] <> 0 or $data[$i][3] <> 0) {
		echo "<tr>";
		  echo "<td> <a href=\"rep2.php?account=" 	. number_format($data[$i][11],0) . "&curr=" .   $_GET['curr'] . "\">". $data[$i][1] . " </a></td>";
		  echo "<td align=\"right\">" 					. number_format($data[$i][2],2). 									"</td>";
		  echo "<td align=\"right\">" 					. number_format($data[$i][3],2) . 									"</td>";
		  echo "<td align=\"right\">" 					. number_format($data[$i][5],2) . 									"</td>";
		  echo "<td align=\"right\">" 					. number_format($data[$i][6],2) . 									"</td>";
		  echo "<td align=\"right\"  style=\"background: #eeeeee;\" >" . number_format($data[$i][3] - $data[$i][2],2) . " </td> ";

		if ($data[$i][4] == "L" ) { 
		  echo "<td align=\"right\">" 					.  number_format(-100 * $data[$i][2]/$razhod,0) . 			"%</td>";
		  echo "<td align=\"center\"> - </td>";
		}

		if ($data[$i][4] == "A" ) { 
		  echo "<td align=\"center\"> - </td>";
		  echo "<td align=\"right\">" 					.  number_format(100 * $data[$i][3]/$prihod,0 ) . 			"%</td>";
		}
		if ($data[$i][4] == "" ) { 
		  echo "<td align=\"center\"> - </td>";
		  echo "<td align=\"center\"> - </td>";
		}
	 	  echo "<td align=\"center\"  style=\"border:0px solid black\"> " .  ($data[$i][7] ) . " </td>";
		  echo "<td align=\"center\"  style=\"border:0px solid black\"> " .  ($data[$i][4] ) . " </td>";
	       echo "</tr>";
	    }
		if ($data[$i][7] == "-" ) { 
		    $liq -= ($data[$i][3] - $data[$i][2]);
		}

		if ($data[$i][7] == "+" ) { 
		    $liq += (-1)*($data[$i][3] - $data[$i][2]);
		}
	}

	echo "</table>";

	echo "<br> <pre>";
	echo " Revenue:  " 	. number_format($prihod,2) 				. "<br>";
	echo "Expenses: " 	. number_format($razhod,2) 				. "<br>";
	echo "  Equity: " 	. number_format($prihod + $razhod,2) 			. "<br>";
	echo "<hr>";
	echo "  Profit: " 	. number_format($liq,2) 				. "<br></pre>";
}

mysql_close();
?>




