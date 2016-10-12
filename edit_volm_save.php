<html>
<head>
<title>Joke</title>
</head>
<body>
<?php
include "db_connect.php";
mysql_connect("$host","$user","$password");
mysql_select_db("$mydatabase");
$strSQL = "UPDATE vol_m SET ";
$strSQL .="VolM = '".$_POST["volm"]."' ";
$strSQL .=",VolY = '".$_POST["voly"]."' ";
$strSQL .=",Vol = '".$_POST["vol"]."' ";
$strSQL .="WHERE VolID = '".$_GET["id"]."' ";
$objQuery = mysql_query($strSQL);
if($objQuery)
{
	echo "Save Done.";
	echo"<body onload=\"window.alert('Save OK!');\">";
	echo '<meta http-equiv="refresh" content="0; url=show_vol.php" >';
}
else
{
	echo "Error Save [".$strSQL."]";
	?><a href="show_vol.php">Back</a><?php
}
mysql_close();
?>
</body>
</html>