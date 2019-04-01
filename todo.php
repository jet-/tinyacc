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
	              <input type="checkbox" name="full_ov" value="yes" > Not Done &nbsp;&nbsp;
	              <input type="checkbox" name="show_ledger" value="yes" checked > Show Ledger &nbsp;&nbsp;
	              <input type="checkbox" name="show_balance" value="yes" checked> Show Balance &nbsp;&nbsp;
		      <input type="submit"   name="send"    value="Generate" autofocus>
<br><br>
</fieldset>
</form>
<?php

if ($_POST['show_ledger'] == "yes" ) { 
	$result = mysql_query ("SELECT id,  todo, created, eta, progress, priority, modified, done, date".
				" FROM todo ".
				" ORDER BY done, priority desc, date, created, progress desc;");


	echo "<table class=ref>  ";
	echo "<caption> TODOs &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;from: {$_POST['from']} to: {$_POST['to']}";
	echo "</caption> ";
	$i=1;
	if ($row = mysql_fetch_array($result)) {

	echo "<tr> 
		<th> # </th> 
		<th>Date</th>  
		<th>Priority</th>  
		<th> Done </th> 
		<th> ETA </th> 
		<th>Progress</th>  
		<th>ToDo</th> 
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
		 echo "<td width=\"20\"> <a href=\"add_todo.php?order=" 	. $row['id'] . "\">". $row['id'] .		"</a></td>";
		 echo "<td width=\"70\" align=\"right\"> " 			. $row['date'] . 		" </td>";
		 echo "<td width=\"5\" align=\"center\"> "			. $row['priority'] . 								" </td>";
		 echo "<td width=\"10\" align=\"center\"> "; 				
		 	if  ($row['done'] == "1") {
		 		echo '<img src="images/checkmark.png" width="23" height="23" alt="" />';
		 		} else {
				echo '<img src="images/red-x.png" width="20" height="20" alt="" />';
		 		}
		 echo 		" </td>";         
		 echo "<td width=\"10\"> " 					. $row['eta'] .			" </td>";
		 echo "<td width=\"5\" align=\"center\"> " 			. $row['progress'] .		" </td>";
		 echo "<td width=\"400\"> " 			. ($row['todo']=="" ? ".": stripslashes($row['todo'])) .		" </td>";
		 echo "<td width=\"70\" align=\"right\"> " 			. $row['created'] . 		" </td>";
		 echo "<td width=\"70\" align=\"right\"> " 				. $row['modified'] . 		" </td>";
		 echo "</tr>";
	  } while($row = mysql_fetch_array($result));

	} else { echo " <hr> no records found! <hr> ";}

	echo "</table>";
	echo "<br><br>";
}



mysql_close();
?>

