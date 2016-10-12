<?php
	session_start();
	include "db_connect.php";
	/*
	if($_SESSION['UserID'] == "")
	{
		echo "Please Login!";
		exit();
	}

	if($_SESSION['Status'] != "USER")
	{
		echo "This page for User only!";
		exit();
	}	
	*/
	mysql_connect("$host","$user","$password");
	mysql_select_db("$mydatabase");
	$strSQL = "SELECT * FROM  vol_m";
	$objQuery = mysql_query($strSQL) or die ("Error Query [".$strSQL."]");
?>
<html>
<head>
<title>Joke</title>
</head>
<body>

	<table width="600" border="1">
	  <tr>
		<th width="100"> <div align="center">VolID </div></th>
		<th width="100"> <div align="center">VolM </div></th>
		<th width="100"> <div align="center">VolY </div></th>
		<th width="100"> <div align="center">Vol </div></th>
		<th width="100"> <div align="center">Edit </div></th>
		<th width="100"> <div align="center">Delete </div></th>

	  </tr>
	<?php
	$num=0;
	while($objResult = mysql_fetch_array($objQuery))
	{
	?>
	  <tr>
		<td><div align="center"><?php echo $objResult["VolID"];?></div></td>
		<td><div align="center"><?php echo $objResult["VolM"];?></div></td>
		<td><div align="center"><?php echo $objResult["VolY"];?></div></td>
		<td><div align="center"><?php echo $objResult["Vol"];?></div></td>
		<td><div align="center"><a href="edit_volm.php?id=<?php echo $objResult["VolID"];?>">Edit</a></div></td>
		<td><div align="center"><a href="add_volm_del.php?id=<?php echo $objResult["VolID"];?>">Delete</a></div></td>

	  </tr>
	<?php
	}
	?>
	</table>
	<?php
	mysql_close();
?>
	<br>
  <a href="add_volm.php">Add Data</a><br>
  <br>
  <a href="admin_page.php">Back</a><br>
  <br>
  <a href="logout.php">Logout</a>
</body>
</html>