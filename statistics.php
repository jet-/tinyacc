<?php

require_once("conf.php");
require_once("menu.php");


$result = $mysqli->query("select  id, name from items WHERE type REGEXP 'L|A' ORDER BY orderby");

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
   
		        <input type="submit" name="send" value="Generate" autofocus>
<br><br>

</fieldset>
</form>

<?php

$acnt = isset($_POST['dt1']) ? $_POST['dt1'] : 35;

$query="select name,type from items WHERE id=". $acnt ;
$result = $mysqli->query($query);
$row = $result->fetch_assoc();
$name=$row['name'];


if ($row['type']== "L" ) {
	$result = $mysqli->query("SELECT DATE_FORMAT(ledger.date,'%Y') as date, items.name AS name, sum(ledger.amount) AS amnt FROM items
    LEFT OUTER JOIN  ledger ON ledger.item_dt=items.id and accounted
    WHERE accounted and items.type REGEXP 'L|A' and items.id = $acnt  
    group by DATE_FORMAT(ledger.date,'%Y %m'), items.id
    order by ledger.date, items.id");

} else {

$result = $mysqli->query("SELECT DATE_FORMAT(ledger.date,'%Y') as date, items.name AS name, sum(ledger.amount) AS amnt                               
    FROM items
    LEFT OUTER JOIN  ledger ON ledger.item_ct=items.id and accounted
    WHERE accounted and items.type REGEXP 'L|A' and items.id = $acnt  
    group by DATE_FORMAT(ledger.date,'%Y %m'), items.id
    order by ledger.date, items.id");
}

echo "<table class=\"ref\"  bgcolor=\"#DDD4FF\">
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


echo "<caption> $name &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </caption>"; 

	$d ="";
	$sum=0;
	if ($row = $result->fetch_assoc() ) {

	while($row = $result->fetch_assoc() ) {
            if ($d <> $row['date']) 
                {
                    $d = $row['date'];
                    echo "<td align=\"right\"><b>" . number_format($sum,2) . "</b></td></tr> <tr> <td> <b>"  . $row['date'] . "</b></td>"; $sum=0;
                }    
                echo "<td align='right'>" . number_format($row['amnt'],0) . "</td>" ; $sum = $sum + $row['amnt'];
               

	       }  
	} else { echo " <hr> no records found! <hr> ";}

echo "</table>";


$mysqli->close();
?>


