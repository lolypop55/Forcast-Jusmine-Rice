<?php
$id=$_GET['id'];
include "db_connect.php";
$objConnect = mysql_connect("$host","$user","$password") or die("Error Connect to Database");
$objDB = mysql_select_db("$mydatabase");
$strSQL = "DELETE From vol_m WHERE VOLID=$id ";
$objQuery = mysql_query($strSQL);
if($objQuery)
{
	echo "Save Done.";
	echo"<body onload=\"window.alert('Del OK!');\">";
	echo '<meta http-equiv="refresh" content="0; url=show_vol.php" >';
	//header("location:add_volm.php");
}
else
{
	echo "Error Save [".$strSQL."]";?>
	<a href="add_volm.php">Back</a><?php
}



?>