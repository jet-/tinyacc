<?php
require_once("menu.php");
require_once("conf.php");

$acnt = "33";    #mortgage interest account ID
$year = 1;

#get start balance for the account
$query="select sum(amount) as amount from ledger WHERE ledger.item_dt=\"" . $acnt . "\" ";$result = $mysqli->query($query);
$row = $result->fetch_assoc();
$dt_turn=$row['amount'];

$query="select sum(amount) as amount from ledger WHERE ledger.item_ct=\"" . $acnt . "\" ";$result = $mysqli->query($query);
$row = $result->fetch_assoc();
$ct_turn=$row['amount'];


$interest_paid=$dt_turn-$ct_turn;

#---------------
$acnt = "29";    #mortgage principal account ID
$year = 1;

$query="select name from items WHERE id=0". $acnt ;
$result = $mysqli->query($query);
$row = $result->fetch_assoc();
$name=$row['name'];

#get start balance for the account
$query="select sum(amount) as amount from ledger WHERE ledger.item_dt=\"" . $acnt . "\" and accounted";$result = $mysqli->query($query);
$row = $result->fetch_assoc();
$dt_turn=$row['amount'];

$query="select sum(amount) as amount from ledger WHERE ledger.item_ct=\"" . $acnt . "\" and accounted";$result = $mysqli->query($query);
$row = $result->fetch_assoc();
$ct_turn=$row['amount'];


$start_saldo=$dt_turn-$ct_turn;

$current_owed = -1 *  $start_saldo;
$annual_interest = 0.03;
$yearly_payment =  26 * 352.12; //byweekly in my case


?>
<br><br><br>
<form name="form" action="<?php echo $PHP_SELF;?>" method="post" enctype="multipart/form-data">
<fieldset>
<legend>Projected Mortgage Report</legend>
    &nbsp;&nbsp;&nbsp;&nbsp; <br>
   <b> Current amount</b>: 	<input type="text" name="current_owed"    value= "<?php echo $current_owed; ?>" size=10 maxlength=10  style="background: #FFFFCC;" > &nbsp;&nbsp;&nbsp; 
   <b> Yearly payment </b>: 	<input type="text" name="yearly_payment"  value= "<?php echo $yearly_payment; ?>" size=10 maxlength=10  style="background: #FFFFCC;" > &nbsp;&nbsp;&nbsp; 
   <b> Annual Interest</b>:     <input type="text" name="annual_interest" value= "<?php echo $annual_interest; ?>" size=10 maxlength=10  style="background: #FFFFCC;" > &nbsp;&nbsp;&nbsp;
   <b> Interest Paid </b>:      <input type="text" name="interest_paid"    value= "<?php echo $interest_paid; ?>" size=10 maxlength=10  style="background: #FFFFCC;" >
                                <input type="submit" name="send" value="Generate" autofocus>
<br><br>

</fieldset>
</form>


<?php

$current_owed 	= $_POST['current_owed'];
$yearly_payment = $_POST['yearly_payment'];
$annual_interest = $_POST['annual_interest'];
$interest_paid 	= $_POST['interest_paid'];
$total_paid	= $current_owed;


print "<p><br><b>Current amount: </b>" . number_format($current_owed,2);
print "<br>&nbsp;&nbsp;&nbsp;&nbsp;";
print "<p><b>Yearly Payment: </b>" . number_format($yearly_payment,2);
print "<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
print "<p><b>Annual Interest: </b>$annual_interest";
print "<br>";

?>


<table class="table table-bordered">
<caption>    </caption> 


<?php
$i=0;
   echo "<tr align=\"center\"> 
	<th> Year #</th> 
	<th> Year </th> 
	<th>Owed amount</th>  
	<th>  Yearly Principal </th> 
	<th> Yearly Interest</th>   
	<th> &nbsp;&nbsp; Total Paid Interest</th> 
	</tr>";

   while ( $current_owed > 0 ) {
        if ($i%2 ==0 ) {
                 echo "<tr style=\"background: #eeeeee;\" >";
          } else {
                 echo "<tr style=\"background: #cccccc;\" >";
        }
        $i++;
        echo "<td > " . $year . " </td>";
        echo "<td > " , date("Y") + $year, " </td>";
        echo "<td align=\"right\"> " . number_format($current_owed,2) . " </td>";
        echo "<td align=\"right\"> " . number_format($yearly_payment-$current_owed * $annual_interest,2) . "</td>";
        echo "<td align=\"right\"> " . number_format($current_owed * $annual_interest,2) . "</td>";
        echo "<td align=\"right\"> " . number_format($interest_paid,2) . "</td>";

        $current_owed = (($current_owed * $annual_interest) + $current_owed) - $yearly_payment;
        $interest_paid = $interest_paid + ($current_owed * $annual_interest);
        $year++;


	 echo "</tr>";

} 

print  "<tr> <td align = right>  Total </td> <td> principal +</td> <td>interest: " . number_format($total_paid + $interest_paid,2) . " </td> </tr>";
echo "</table>";

$mysqli->close();
?>
