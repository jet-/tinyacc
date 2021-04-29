<head>

<style>
.collapsible {
  background-color: #F6F6F6;
  color: black;
  cursor: pointer;
  padding: 1px;
  width: 100%;
  border: none;
  text-align: left;
  outline: none;
  font-size: 15px;
}

.active, .collapsible:hover {
  background-color: #F6F6F6;
}

.content {
  padding: 0 18px;
  display: none;
  overflow: hidden;
  background-color: #F6F6F6;
}
</style>
</head>
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
	              <input type="checkbox" name="show_ledger" value="yes" unchecked > Show Ledger &nbsp;&nbsp;
	              <input type="checkbox" name="show_balance" value="yes" checked> Show Balance &nbsp;&nbsp;
		      <input type="submit"   name="send"    value="Generate" autofocus >
<br><br>
</fieldset>
</form>
<?php
$query ="
	  SELECT ledger.id AS id,  t1.name AS name_dt, ledger.amount, t2.name AS name_ct, date, time, created, accounted, text
	  FROM items t1, items t2, ledger
	  WHERE t1.id=ledger.item_dt AND t2.id=ledger.item_ct AND ledger.date>=\"". $_POST['from']."\" AND ledger.date<=\"". $_POST['to']."\" 
	  ORDER BY ledger.date desc,ledger.id desc;";

$result = $mysqli->query($query);
$rowCount = mysqli_num_rows($result);


if ($_POST['show_ledger'] == "yes" and $rowCount > 0 ) { 

	echo '<table class="table table-bordered tablesorter">  ';
	echo "<caption> GENERAL LEDGER &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;from: {$_POST['from']} to: {$_POST['to']}";
	echo "&nbsp;&nbsp;&nbsp;&nbsp; $rowCount rows </caption> ";
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

	  while($row = $result->fetch_assoc() ) {
		echo "<tr>";
		if ($i%2 ==0 ) {
			 echo '<tr style="background: #eeeeee;" >';
		  } else {
			 echo '<tr style="background: #cccccc;" >';
		}
		$i++;
		echo "<td width=\"20\"> <a href=\"entry.php?order=" . $row['id'] . "&curr=" . $_GET['curr'] ."\">". $row['id'] . "</a></td>
			<td width=\"140\"> " . $row['name_dt'] . " </td>
		 	<td width=\"70\" align=\"right\"> " . number_format($row['amount'],2) . "</td>
		 	<td width=\"140\"> " . $row['name_ct'] .	"</td>
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
		 	<td align=\"center\"> <h6>" . $row['time']    . "</h6></td>
		 	</tr>";
	  } 

	} else { echo " <hr> no records found! <hr> ";}

	echo "</table>";
	echo "<br><br>";
}


#to test
#select item_ct, amount, items.name, items.acc_group, sum(amount)  from ledger left join items on ledger.item_ct=items.id  group by acc_group, item_ct with rollup;
#select item_dt, amount, items.name, items.acc_group, sum(amount)  from ledger left join items on ledger.item_dt=items.id  group by acc_group, item_dt with rollup;

if ($_POST['show_balance'] == "yes" ) { 

	if ($_POST['full_ov'] <> "yes" ) { 
	  $_POST['from']="2000-01-01";
	}

	echo "<table  class=\"ref\"  bgcolor=\"#DDD4FF\">";
	echo "<caption> BALANCE SHEET &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; from: ". $_POST['from'] . "&nbsp;&nbsp; to: ". $_POST['to'];
	echo "</caption>";

	echo '<tr>
		<th width="219"> Item </th>
		<th width="80">Amount DT</th>
		<th width="81">Amount CT</th>
		<th width="81">Amount DT<br> annual </th>
		<th width="81">Amount CT <br>annual</th>
		<th style="background: #eeeeee;"  width="81">Amount </th> </tr>
		</table>';


	$result = $mysqli->query("SELECT  name,acc_group  FROM  acc_groups ");
	while ($groups = $result->fetch_assoc() ) {
	    $group_name = $groups['name'];
	    $group_id   = $groups['acc_group'];

	    $result1 = $mysqli->query("
		    SELECT items.name, items.acc_group AS name, sum(ledger.amount) AS amnt
		    FROM items
		    LEFT JOIN  ledger ON ledger.item_dt=items.id 
		    WHERE  ledger.date<=\"" . $_POST['to'] . "\" 
			   and ledger.date>=\"" . $_POST['from'] . "\" 
			   and accounted 
			   and acc_group = $group_id
		    GROUP BY items.acc_group
		    ORDER BY acc_group ");

	    $row = $result1->fetch_assoc()  ;
	    $group_dt_turn = $row['amnt'];
	    
	    $result1 = $mysqli->query("
		    SELECT items.name, items.acc_group AS name, sum(ledger.amount) AS amnt
		    FROM items
		    LEFT JOIN  ledger ON ledger.item_ct=items.id 
		    WHERE  ledger.date<=\"" . $_POST['to'] . "\" 
			   and ledger.date>=\"" . $_POST['from'] . "\" 
			   and accounted 
			   and acc_group = $group_id
		    GROUP BY items.acc_group
		    ORDER BY acc_group ");

	    $row = $result1->fetch_assoc()  ;
	    $group_ct_turn = $row['amnt'];


	    $result1 = $mysqli->query("
		    SELECT items.name, items.acc_group AS name, sum(ledger.amount) AS amnt
		    FROM items
		    LEFT JOIN  ledger ON ledger.item_dt=items.id 
		    WHERE  ledger.date<=\"" . $_POST['to'] . "\" 
			   and YEAR(ledger.date) =\"" . date("Y",strtotime($_POST['to'])) . "\" 
			   and accounted 
			   and acc_group = $group_id
		    GROUP BY items.acc_group
		    ORDER BY acc_group ");

	    $row = $result1->fetch_assoc()  ;
	    $group_dt_turn_y = $row['amnt'];

	    $result1 = $mysqli->query("
		    SELECT items.name, items.acc_group AS name, sum(ledger.amount) AS amnt
		    FROM items
		    LEFT JOIN  ledger ON ledger.item_ct=items.id 
		    WHERE  ledger.date<=\"" . $_POST['to'] . "\" 
			   and YEAR(ledger.date) =\"" . date("Y",strtotime($_POST['to'])) . "\" 
			   and accounted 
			   and acc_group = $group_id
		    GROUP BY items.acc_group
		    ORDER BY acc_group ");

	    $row = $result1->fetch_assoc()  ;
	    $group_ct_turn_y = $row['amnt'];

	    if ( $group_dt_turn <> 0 or $group_ct_turn <>0 ) {		#hide rows with zeros
	    echo "<button class=\"collapsible\">
		<table   class=\"ref\"  bgcolor=\"#cae6ff\">
		<tr>
		<td  width=\"215\"> 🢒 $group_name </td>
		<td align=\"right\" width=\"80\">" . number_format($group_dt_turn,2) . "</td>
		<td align=\"right\" width=\"80\">" . number_format($group_ct_turn,2) . "</td>
		<td align=\"right\" width=\"80\">" . number_format($group_dt_turn_y,2) . "</td>
		<td align=\"right\" width=\"80\">" . number_format($group_ct_turn_y,2) . "</td>
		<td align=\"right\" width=\"80\">" . number_format($group_ct_turn - $group_dt_turn,2) . "</td> 		</tr>
		</table>";
	    }
		echo "</button>
			<div class=\"content\">
			  <table   class=\"ref\"  bgcolor=\"#DDD4FF\">";

		$result1 = $mysqli->query("SELECT  id, name, acc_group  FROM  items WHERE acc_group = $group_id");
		while ($groups = $result1->fetch_assoc() ) {
		    $acc_name = $groups['name'];
		    $acc_id   = $groups['id'];



		    $result2 = $mysqli->query("
			    SELECT items.name, items.acc_group AS name, sum(ledger.amount) AS amnt
			    FROM items
			    LEFT JOIN  ledger ON ledger.item_dt=items.id 
			    WHERE  ledger.date<=\"" . $_POST['to'] . "\" 
				   and ledger.date>=\"" . $_POST['from'] . "\" 
				   and accounted 
				   and item_dt = $acc_id
			    GROUP BY items.acc_group
			    ORDER BY acc_group ");

		    $row = $result2->fetch_assoc()  ;
		    $acc_dt_turn = $row['amnt'];

		    $result2 = $mysqli->query("
			    SELECT items.name, items.acc_group AS name, sum(ledger.amount) AS amnt
			    FROM items
			    LEFT JOIN  ledger ON ledger.item_ct=items.id 
			    WHERE  ledger.date<=\"" . $_POST['to'] . "\" 
				   and ledger.date>=\"" . $_POST['from'] . "\" 
				   and accounted 
				   and item_ct = $acc_id
			    GROUP BY items.acc_group
			    ORDER BY acc_group ");

		    $row = $result2->fetch_assoc()  ;
		    $acc_ct_turn = $row['amnt'];


		    $result2 = $mysqli->query("
			    SELECT items.name, items.acc_group AS name, sum(ledger.amount) AS amnt
			    FROM items
			    LEFT JOIN  ledger ON ledger.item_dt=items.id 
			    WHERE  ledger.date<=\"" . $_POST['to'] . "\" 
				   and YEAR(ledger.date)=\"" . date("Y",strtotime($_POST['to'])) . "\" 
				   and accounted 
				   and item_dt = $acc_id
			    GROUP BY items.acc_group
			    ORDER BY acc_group ");

		    $row = $result2->fetch_assoc()  ;
		    $acc_dt_turn_y = $row['amnt'];

		    $result2 = $mysqli->query("
			    SELECT items.name, items.acc_group AS name, sum(ledger.amount) AS amnt
			    FROM items
			    LEFT JOIN  ledger ON ledger.item_ct=items.id 
			    WHERE  ledger.date<=\"" . $_POST['to'] . "\" 
				   and YEAR(ledger.date)=\"" . date("Y",strtotime($_POST['to'])) . "\" 
				   and accounted 
				   and item_ct = $acc_id
			    GROUP BY items.acc_group
			    ORDER BY acc_group ");

		    $row = $result2->fetch_assoc()  ;
		    $acc_ct_turn_y = $row['amnt'];

		    if ( $acc_dt_turn <> 0 or $acc_ct_turn <> 0 ) {	#hide rows with zeros
			  echo "<tr><td width=\"200\"> $acc_name </td>
				<td align=\"right\" width=\"80\">" . number_format($acc_dt_turn,2) . "</td>
				<td align=\"right\" width=\"80\">" . number_format($acc_ct_turn,2) . "</td>
				<td align=\"right\" width=\"80\">" . number_format($acc_dt_turn_y,2) . "</td>
				<td align=\"right\" width=\"80\">" . number_format($acc_ct_turn_y,2) . "</td>
				<td align=\"right\" style=\"background: #eeeeee;\"  width=\"80\">" . number_format($acc_ct_turn - $acc_dt_turn,2) . "</td></tr>";
		    }		    
		}

		echo "</table>
			</div>";

	}
}

$mysqli->close();
?>
<script>
var coll = document.getElementsByClassName("collapsible");
var i;

for (i = 0; i < coll.length; i++) {
  coll[i].addEventListener("click", function() {
    this.classList.toggle("active");
    var content = this.nextElementSibling;
    if (content.style.display === "block") {
      content.style.display = "none";
    } else {
      content.style.display = "block";
    }
  });
}
</script>


