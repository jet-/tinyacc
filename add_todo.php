<?php

require_once("menu.php");
require_once("conf.php");

    $data[1][1]= '1';    $data[1][2]= '1';
    $data[2][1]= '2';    $data[2][2]= '2';
    $data[3][1]= '3';    $data[3][2]= '3';
    $data[4][1]= '4';    $data[4][2]= '4';
    $data[5][1]= '5';    $data[5][2]= '5';
 $counter=5;


if (!isset($_POST['send']) ) {

    if (isset($_GET['order']) ) {
	$query="SELECT todo, date, progress, eta, priority, done FROM todo WHERE id=" . $_GET['order'];
	$result = mysql_query ($query);
	$row = mysql_fetch_array($result);
    }

?>
<br><br><br>
<form name="form" action="<?php echo $PHP_SELF;?>" method="post" enctype="multipart/form-data">

<fieldset>
<legend>Add New ToDo</legend>



<table> 
<br><br><br><br><br>

<tr style="background: #C1FF69;" align="center"> 
  <th> Date </th>  
  <th> Priority </th> 
  <th> ETA </th> 
  <th> Progress </th> 
  <th> ToDo </th>  
  <th> Done </th> 
</tr>

    <tr align="center" valign="top" > 
    <td>  <input type="text" name="date"   value= "<?= (isset($row['date'])) ? $row['date'] : date("Y-m-d"); ?>"size=10 maxlength=10  style="background: #FFFFCC;" > </td>
    <td>  <select name="priority">
<?php
    for ($i=1;$i<$counter;$i++) {
        echo "<option value=\"".$data[$i][1]."\"";
        if ($data[$i][1] == $row['priority'] ) { 	
  	  echo "selected=\"selected\" "; 
        }
        echo ">" . $data[$i][2] . "</option>";
    }
?>
	</select> </td>

    <td> 
	<input type="text" name="eta" value= "<?= (isset($row['eta'])) ? $row['eta'] : ""; ?>"  size=10 maxlength=10  style="background: #FFFFCC;" > 
    </td>

    <td> 
	<select name="progress">
<?php
        for ($i=1;$i<$counter;$i++) {
            echo "<option value=\"".$data[$i][1]."\"";
            if ($data[$i][1] == $row['progress'] ) { 
        	echo "selected=\"selected\" "; 
            }
            echo ">" . $data[$i][2] . "</option>";
        }
?>
    </select> 

    </td>

    <td> 
	<textarea 
		name=note  rows=4 cols=70 wrap=physical style="background: #FFFFCC;" ><?=(isset($row['todo'])) ? stripslashes($row['todo']) : NULL; ?></textarea> 
    </td>

    <td> 
	<input type="checkbox" name="accounted1" value="yes" <?=($row['done']) ? "checked" : NULL; ?> <? #if (!isset($_GET['order']) ) { echo "checked"; } ?> >
    <td>
</tr>

<tr> </tr>
<tr>	<td>    	<input type="submit" name="send" value="Proceed" autofocus> </td> </tr>

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

$query.=" todo set todo=\"" . mysql_real_escape_string($_POST['note']) . "\" , progress=" . $_POST['progress'] . ", eta=\"" . $_POST['eta'] . "\", priority=\"" . $_POST['priority'] . "\", date=\"" . $_POST['date'] . "\"  ";

if (!isset($_GET['order']) ) {
    $query .= " , created=\"". date('c') . "\" " ;
}

if ($_POST['accounted1'] == "yes" ) {
    $query .= " , done=true ";
} else {
    $query .= " , done=false ";
}

if (isset($_GET['order']) ) {
    $query .= " WHERE id=" . $_GET['order'];
}

#echo $query;
$result = mysql_query ($query);
$last_ledger=mysql_insert_id();


echo "<br> Accounted! <br>";


}


mysql_close();
?>
