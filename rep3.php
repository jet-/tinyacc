<?php

require_once("conf.php");
require_once("menu.php");

    if (isset($_GET['from']) ) {
	$_POST['from'] = substr($_GET['from'],0,4) . "-" . substr($_GET['from'],4,2) . "-01"; 
	$_POST['to']   = substr($_GET['from'],0,4) . "-" . substr($_GET['from'],4,2) . "-31";
    } else {


?>
<br><br><br>
<form name="form" action="<?php echo $PHP_SELF;?>" method="post" enctype="multipart/form-data">
<fieldset>
<legend>Report</legend>

    <br> <b> From date</b>: 
	<input class="mydate1" type="text" name="from" value= "<?php echo date("Y-m")."-01"; ?>" size=10 maxlength=10 style="background: #FFFFCC;"> &nbsp;&nbsp;&nbsp;
    <b> To date</b>: 
	<input class="mydate2" type="text"     name="to"      value= "<?php echo date("Y-m-d"); ?>"size=10 maxlength=10  style="background: #FFFFCC;" >
	<input type="submit"   name="send"    value="Generate" autofocus >
<br><br>
</fieldset>
</form>
<?php
}
	#ov
	#type_: L - razhod; A - prihod
	$result = $mysqli->query("select  id, name, type, liquidity, orderby from items ORDER by orderby ");
	$counter=1;
	while ($row0 = $result->fetch_assoc() ) {
	    $data[$counter][1]= $row0['name'];
	    $data[$counter][4]= $row0['type'];
	    $data[$counter][7]= $row0['liquidity'];
	    $data[$counter][8]= $row0['orderby'];
	    $data[$counter][11]= $row0['id'];
	    $counter++;
	}


	$result = $mysqli->query("
	    SELECT items.name AS name, sum(ledger.amount) AS amnt
	    FROM items
	    LEFT JOIN  ledger ON ledger.item_dt=items.id and ledger.date<=\"" . $_POST['to'] . "\" 
		      and ledger.date>=\"" . $_POST['from'] . "\"  and accounted
	    GROUP BY items.id
	    ORDER BY orderby ");


	$counter=1;
	while ($row1 = $result->fetch_assoc() ) {
	    $data[$counter][2]= $row1['amnt'];
	    $counter++;
	}

	$result = $mysqli->query("
	    SELECT items.name AS name, sum(ledger.amount) AS amnt
	    FROM items
	    LEFT JOIN  ledger ON ledger.item_ct=items.id and ledger.date<=\"" . $_POST['to'] . "\" 
		      and ledger.date>=\"" . $_POST['from'] . "\" and accounted
	    GROUP BY items.id
	    ORDER BY orderby ");

	$counter=1;
	while ($row2 = $result->fetch_assoc() ) {
	    $data[$counter][3]= $row2['amnt'];
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

	echo "<caption> from: <b>". $_POST['from'] . "&nbsp;&nbsp; </b> to: <b>". $_POST['to'] . "</b><p>";
    echo "</caption> ";
  
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


echo '
   <script type="text/javascript" src="js/loader.js"></script>
    <script type="text/javascript">
      google.charts.load("current", {packages:["corechart"]});
      google.charts.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          [\'\', \' \'],
';


	$liq=0;

	for ($i=1;$i<$counter;$i++) {
            if ($data[$i][4] == "L" and $data[$i][2] - $data[$i][3] > 0){
                echo "[' ". $data[$i][1] . "', ";
                echo  $data[$i][2] - $data[$i][3] . "],";
            }
        }
?>
        ]);

        var options = {
          title: 'Expenses',
          pieHole: 0.4,
        };

        var chart = new google.visualization.PieChart(document.getElementById('donutchart1'));
        chart.draw(data, options);
      }

</script>


<?php
echo '
    <script type="text/javascript">
      google.charts.load("current", {packages:["corechart"]});
      google.charts.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          [\'\', \'\'],
';

	$liq=0;

	for ($i=1;$i<$counter;$i++) {
           if ($data[$i][4] == "A" and $data[$i][3] - $data[$i][2]>0 ) {
                echo "[' ". $data[$i][1] . "', ";
                echo  $data[$i][3] - $data[$i][2] . "],";
            }
        }
?>
        ]);

        var options = {
          title: 'Revenue',
          pieHole: 0.4,
        };

        var chart = new google.visualization.PieChart(document.getElementById('donutchart2'));
        chart.draw(data, options);
      }

</script>

<table> <tr> 
<td> <div id="donutchart1" style="width: 800px; height: 600px;"></div> </td>
<td> <div id="donutchart2" style="width: 800px; height: 600px;"></div> </td>
</tr> </table>
<br>
Expenses: <b><?php  echo number_format((-1) * $razhod,2); ?> </b><br>
<p>
&nbsp;Revenue: <b><?php  echo number_format($prihod,2); ?> </b>

&emsp;&emsp;&emsp;&emsp; Diff: <b><?php  echo number_format($prihod+$razhod,2); ?> </b><br>




