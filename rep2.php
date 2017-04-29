<?php
require_once("menu.php");
require_once("conf.php");

$result = mysql_query ("select  id, name from items ORDER BY orderby");
$counter=1;
while ($row = mysql_fetch_array($result) ) {
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
   <b> From date</b>: 	<input type="text"   name="from" value= "<?= date("Y-m")."-01"; ?>" size=10 maxlength=10  style="background: #FFFFCC;" > &nbsp;&nbsp;&nbsp; 
   <b> To date</b>:     <input type="text"   name="to"   value= "<?= date("Y-m-d"); ?>" size=10 maxlength=10  style="background: #FFFFCC;" >
		        <input type="submit" name="send" value="Generate" autofocus>
<br><br>

</fieldset>
</form>


<?php







#$acnt=1;
#if (!isset($_GET['account'])){
  $acnt = $_POST['dt1'];
#} else {
#  $acnt = $_GET['account'];
#}


$query="select name from items WHERE id=". $acnt ;
$result = mysql_query ($query);
$row = mysql_fetch_array($result);
$name=$row['name'];
#echo $query;




#get start saldo for the account
$query="select sum(ammount) as ammount from ledger WHERE ledger.date<=\"". $_POST['to']."\" and ledger.item_dt=\"" . $acnt . "\" ";$result = mysql_query ($query);
$row = mysql_fetch_array($result);
$dt_turn=$row['ammount'];

$query="select sum(ammount) as ammount from ledger WHERE ledger.date<=\"". $_POST['to']."\" and  ledger.item_ct=\"" . $acnt . "\" ";$result = mysql_query ($query);
$row = mysql_fetch_array($result);
$ct_turn=$row['ammount'];


$start_saldo=$dt_turn-$ct_turn;







$query="select sum(ammount) as ammount from ledger WHERE ledger.date>=\"". $_POST['from']."\" and ledger.date<=\"". $_POST['to']."\" and ledger.item_dt=\"" . $acnt . "\" ";
$result = mysql_query ($query);
$row = mysql_fetch_array($result);
$dt_turn=$row['ammount'];

$query="select sum(ammount) as ammount from ledger WHERE ledger.date>=\"". $_POST['from']."\" and ledger.date<=\"". $_POST['to']."\" and  ledger.item_ct=\"" . $acnt . "\" ";
$result = mysql_query ($query);
$row = mysql_fetch_array($result);
$ct_turn=$row['ammount'];



$query ="SELECT ledger.id as id,  t1.name as name_dt, ledger.ammount, t2.name as name_ct, date, time, created, accounted, texts.text as text, ledger.item_dt as item_dt ".
	"FROM items t1, items t2, ledger ".
	"LEFT JOIN texts on ledger.id=texts.docnum where t1.id=ledger.item_dt and t2.id=ledger.item_ct and ledger.date>=\"". $_POST['from']."\" and ledger.date<=\"". $_POST['to']."\" and (ledger.item_dt=\"" . $acnt . "\" or ledger.item_ct=\"" . $acnt . "\") ".
	"ORDER BY ledger.date desc,ledger.id desc;";
$result = mysql_query ($query);

?>


<table class="table table-bordered tablesorter">  
<caption> STATEMENT OF ACCOUNT:  <? echo $name; ?>  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;from: <? echo $_POST['from']; ?>  to:  <? echo $_POST['to']; ?> </caption> 


<?php
$i=1;
if ($row = mysql_fetch_array($result)) {
   echo "<thead><tr align=\"center\"> <th> # </th> <th>Item DT</th>  <th> Ammount </th> <th>Item CT</th> <th> Date </th> <th>Text</th>  <th>Status</th> <th> Created</th>  <th>Last Modified</th> <th>Balance</th> </tr></thead>";

   do {
        if ($i%2 ==0 ) {
                 echo "<tr style=\"background: #eeeeee;\" >";
          } else {
                 echo "<tr style=\"background: #cccccc;\" >";
        }
        $i++;
	 echo "<td width=\"20\"> <a href=\"entry.php?order=" . $row['id'] .  "&curr=" .   $_GET['curr'] . "\">". $row['id'] ." </a></td>";
	 echo "<td width=\"120\"> " . $row['name_dt'] . " </td>";
	 echo "<td width=\"70\" align=\"right\"> " . number_format($row['ammount'],2) . " </td>";
	 echo "<td width=\"120\"> " . $row['name_ct'] . " </td>";
	 echo "<td width=\"100\" align=\"center\"> " . $row['date'] . " </td>";
	 echo "<td width=\"400\"> " . ($row['text']=="" ? ".": stripslashes($row['text'])) . " </td>";
	 echo "<td width=\"10\" align=\"center\"> "; 				
	 	if  ($row['accounted'] == "1") {
	 		echo '<img src="images/checkmark.png" width="23" height="23" alt="" />';
	 		} else {
			echo '<img src="images/red-x.png" width="20" height="20" alt="" />';
	 		}
	 echo 		" </td>";         
	 echo "<td align=\"center\"> <h6>" . $row['created'] . " </h6></td>";
	 echo "<td align=\"center\"> <h6>" . $row['time'] . " </h6></td>";

	 echo "<td align=\"right\"> <h6>" . number_format($start_saldo,2) .  " </h6></td>";
if ( $acnt ==  $row['item_dt'] ) {
    $start_saldo = $start_saldo - $row['ammount'];
      } else {
    $start_saldo = $start_saldo + $row['ammount'];
}




	 echo "</tr>";

	
} while($row = mysql_fetch_array($result));

} else { echo " <hr> no records found! <hr> ";}

echo "</table>";
echo "<pre>";
echo "Turnover DT: " .  number_format($dt_turn,2) ." <br>";
echo "Turnover CT: " .  number_format($ct_turn,2) ." <br>";
echo "    Ammount: " .  number_format($dt_turn - $ct_turn,2) . "<br>";
echo "</pre>";

mysql_close();
?>
