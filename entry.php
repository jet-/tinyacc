<?php
require_once("menu.php");
require_once("conf.php");

# fill in drop list
$result = $mysqli->query("select  id, name from items ORDER BY orderby");
$counter=1;
while ($row = $result->fetch_assoc() ) {
    $data[$counter][1]= $row['id'];
    $data[$counter][2]= $row['name'];
    $counter++;
}

if (!isset($_POST['send']) ) {
    if (isset($_GET['order']) ) {
	$query="SELECT date, item_dt, ammount, item_ct, accounted, text FROM ledger WHERE id=" . $_GET['order'];
	$result = $mysqli->query($query);
	$row = $result->fetch_assoc();
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
    <td>  <input type="text" name="date"   value= "<?php echo (isset($row['date'])) ? $row['date'] : date("Y-m-d"); ?>"size=10 maxlength=10  style="background: #FFFFCC;" > </td>
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
	<input type="text" name="amnt1" value= "<?php echo (isset($row['ammount'])) ? $row['ammount'] : "0.00"; ?>"  size=10 maxlength=10  style="background: #FFFFCC;"  autofocus> 
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
		name=note  rows=4 cols=70 wrap=physical style="background: #FFFFCC;" ><?php echo (isset($row['text'])) ? stripslashes($row['text']) : NULL; ?></textarea> 
    </td>

    <td> 
	<input type="checkbox" name="accounted1" value="yes" <?php echo ($row['accounted']) ? "checked" : NULL; ?> <?php if (!isset($_GET['order']) ) { echo "checked"; } ?> >
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

$query.=" ledger set item_dt=" . $_POST['dt1'] . ", ammount=\"" . $_POST['amnt1'] . "\", item_ct=" . $_POST['ct1'] . ", date=\"" . $_POST['date'] . "\" ,text=\"" . mysqli_real_escape_string($mysqli,$_POST['note']) . "\"  ";

if (!isset($_GET['order']) ) {
    $query .= " , created=\"". date('Y-m-d H:i:s') . "\" " ;
}

if ($_POST['accounted1'] == "yes" ) {
    $query .= " , accounted=true ";
} else {
    $query .= " , accounted=false ";
}

if (isset($_GET['order']) ) {
    $query .= " WHERE id=" . $_GET['order'];
}

$result = $mysqli->query($query);
echo "<br> Accounted! <br>";
echo "New record has id: " . mysqli_insert_id($mysqli); 

}

$mysqli->close();
?>

