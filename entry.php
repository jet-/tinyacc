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

if (!isset($_POST['send']) ) {

    if (isset($_GET['order']) ) {
	$query="SELECT date, item_dt, ammount, item_ct, accounted FROM ledger WHERE id=" . $_GET['order'];
	$result = mysql_query ($query);
	$row = mysql_fetch_array($result);

	$query="SELECT text FROM texts WHERE docnum=" . $_GET['order'];
	$result = mysql_query ($query);
	$row1 = mysql_fetch_array($result);
    }

?>
<br><br><br>
<form name="form" action="<?php echo $PHP_SELF;?>" method="post" enctype="multipart/form-data">

<fieldset>
<legend>Add New Document</legend>



<table> 
<br><br><br><br><br>

<tr style="background: #C1FF69;" align="center"> 
  <th> Date </th>  
  <th> DT </th> 
  <th> Ammount </th> 
  <th> CT </th> 
  <th> TEXT </th>  
  <th> Accounted </th> 
</tr>

    <tr align="center" valign="top" > 
    <td>  <input type="text" name="date"   value= "<?= (isset($row['date'])) ? $row['date'] : date("Y-m-d"); ?>"size=10 maxlength=10  style="background: #FFFFCC;" > </td>
    <td>  <select name="dt1">
<?php
    for ($i=1;$i<$counter;$i++) {
        echo "<option value=\"".$data[$i][1]."\"";
        if ($data[$i][1] == $row['item_dt'] ) { 	
  	  echo "selected=\"selected\" "; 
        }
        echo ">" . $data[$i][2] . "</option>";
    }
?>
	</select> </td>

    <td> 
	<input type="text" name="amnt1" value= "<?= (isset($row['ammount'])) ? $row['ammount'] : "0.00"; ?>"  size=10 maxlength=10  style="background: #FFFFCC;"  autofocus> 
    </td>

    <td> 
	<select name="ct1">
<?php
        for ($i=1;$i<$counter;$i++) {
            echo "<option value=\"".$data[$i][1]."\"";
            if ($data[$i][1] == $row['item_ct'] ) { 
        	echo "selected=\"selected\" "; 
            }
            echo ">" . $data[$i][2] . "</option>";
        }
?>
    </select> 

    </td>

    <td> 
	<textarea 
		name=note  rows=4 cols=70 wrap=physical style="background: #FFFFCC;" ><?=(isset($row1['text'])) ? stripslashes($row1['text']) : NULL; ?></textarea> 
    </td>

    <td> 
	<input type="checkbox" name="accounted1" value="yes" <?=($row['accounted']) ? "checked" : NULL; ?> <? if (!isset($_GET['order']) ) { echo "checked"; } ?> >
    <td>
</tr>

<tr> </tr>
<tr>	<td>    	<input type="submit" name="send" value="Proceed"> </td> </tr>

</fieldset> 
</form>

</table>
<?php

}else{

if (isset($_GET['order']) ) {
    $query= "UPDATE ";
} else {
    $query= "INSERT INTO ";
}

$query.=" ledger set item_dt=" . $_POST['dt1'] . ", ammount=\"" . $_POST['amnt1'] . "\", item_ct=" . $_POST['ct1'] . ", date=\"" . $_POST['date'] . "\"  ";

if (!isset($_GET['order']) ) {
    $query .= " , created=\"". date('c') . "\" " ;
}

if ($_POST['accounted1'] == "yes" ) {
    $query .= " , accounted=true ";
} else {
    $query .= " , accounted=false ";
}

if (isset($_GET['order']) ) {
    $query .= " WHERE id=" . $_GET['order'];
}

#echo $query;
$result = mysql_query ($query);
$last_ledger=mysql_insert_id();

if (isset($_GET['order']) ) {
    $query= "UPDATE texts set text=\"" . mysql_real_escape_string($_POST['note']) . "\" WHERE docnum=" . $_GET['order'];
} else {
    $query= "INSERT INTO texts set docnum=\"" . $last_ledger . "\" , text=\"" . mysql_real_escape_string($_POST['note']) . "\"";
}

$result = mysql_query ($query );
#echo "***". $query . "***";

echo "<br> Accounted! <br>";
#echo "<a href=\"k.php\"</a>Main Menu";

}


mysql_close();
?>
