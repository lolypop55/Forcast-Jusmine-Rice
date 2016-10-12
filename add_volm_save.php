<html>
<head>
<title>ThaiCreate.Com PHP & MySQL Tutorial</title>
</head>
<body>
<?php
include "db_connect.php";
$objConnect = mysql_connect("$host","$user","$password") or die("Error Connect to Database");
$objDB = mysql_select_db("$mydatabase");
$strSQL = "INSERT INTO vol_m ";
$strSQL .="(VolM,VolY,Vol) ";
$strSQL .="VALUES ";
$strSQL .="('".$_POST["txtM"]."','".$_POST["txtY"]."','".$_POST["txtVol"]."') ";
$objQuery = mysql_query($strSQL);
if($objQuery)
{
	echo "Save Done.";
	echo"<body onload=\"window.alert('Save OK!');\">";
	echo '<meta http-equiv="refresh" content="0; url=add_volm.php" >';
	//header("location:add_volm.php");
}
else
{
	echo "Error Save [".$strSQL."]";?>
	<a href="add_volm.php">Back</a><?php
}
mysql_close($objConnect);
?>
</body>
</html>