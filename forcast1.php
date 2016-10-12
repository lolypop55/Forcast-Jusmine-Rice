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
	$num=1;
	$temp=0;
	mysql_connect("$host","$user","$password");
	mysql_select_db("$mydatabase");
	$strSQL = "SELECT * FROM  data";
	$objQuery = mysql_query($strSQL) or die ("Error Query [".$strSQL."]");
	while($objResult = mysql_fetch_array($objQuery))
	{
		$t[]=$num++;
		$z[]=$objResult["data"];
	}
	$countz = count($z);
	/*	
	for($x = 0; $x < $countz; $x++) {
    echo $z[$x];
    echo "<br>";
	}
	$countt = count($t);
	//echo "Count Array:$countt<br>";
	
	for($x = 0; $x < $countz; $x++) {
    $t[$x];
	}
	*/
	function average($arr)
	{
	if (!is_array($arr)) return false;

	return array_sum($arr)/count($arr);
	}
	//m1t colum
	for($x = 0; $x < $countz; $x++) {
	if($x<3)
		$m1t[$x]=0;
	else{
		$m1t[$x]=($z[$x-3]+$z[$x-2]+$z[$x-1])/3;
		}
	}
	//m2t colum
	for($x = 0; $x < $countz; $x++) {
	if($x<6)
		$m2t[$x]=0;
	else{
		$m2t[$x]=($m1t[$x-3]+$m1t[$x-2]+$m1t[$x-1])/3;
		}
	}
	//b0t colum
	for($x = 0; $x < $countz; $x++) {
	if($x<6)
		$b0t[$x]=0;
	else{
		$b0t[$x]=2*$m1t[$x]-$m2t[$x];
		}
	}
	//b1t colum
	for($x = 0; $x < $countz; $x++) {
	if($x<6)
		$b1t[$x]=0;
	else{
		$b1t[$x]=$m1t[$x]-$m2t[$x];
		}
	}
	//Zt colum
	for($x = 0; $x < $countz; $x++) {
	if($x<7)
		$zt[$x]=0;
	else{
		$zt[$x]=$b0t[$x-1]+$b1t[$x-1];
		}
	}
	//Zt colum
	for($x = 0; $x < $countz; $x++) {
	if($x<7)
		$z1t[$x]=0;
	else{
		$z1t[$x]=$b0t[$x-1]+$b1t[$x-1];
		}
	}
	//et colum
	for($x = 0; $x < $countz; $x++) {
	if($x<7)
		$et[$x]=0;
	else{
		$et[$x]=$z[$x]-$z1t[$x];
		}
	}
	//forcast
	for($x = 0; $x < $countz+12; $x++) {
	if($x < $countz)
		;
	else{
		$temp++;
		$z1t[$x]=$b0t[$countz-1]+$temp*$b1t[$countz-1];
		}
	}
	//mse colum
	for($x = 0; $x < $countz; $x++) {
	if($x < 7)
		;
	else{
		$mse[$x]=abs($et[$x]*$et[$x]);
		}
	}
	//mad colum
	for($x = 0; $x < $countz; $x++) {
	if($x < 7)
		;
	else{
		$mad[$x]=abs($et[$x]);
		}
	}
	//mape colum
	for($x = 0; $x < $countz; $x++) {
	if($x < 7)
		;
	else{
		 $mape[$x]=abs(($et[$x]/$z[$x])*100);
		 
		}
	}

?>
<html>
<head>
<title>Joke</title>
</head>
<body>

	<table width="1000" border="1">
	  <tr>
		<th width="100"> <div align="center">T </div></th>
		<th width="100"> <div align="center">Zt </div></th>
		<th width="100"> <div align="center">M1t </div></th>
		<th width="100"> <div align="center">M2t </div></th>
		<th width="100"> <div align="center">b0t </div></th>
		<th width="100"> <div align="center">b1t </div></th>
		<th width="100"> <div align="center">Z1t </div></th>
		<th width="100"> <div align="center">et </div></th>
	  </tr>
	<?php
	for($x = 0; $x < $countz; $x++) 
	{
	?>
	  <tr>
		<td><div align="center"><?php echo $t[$x];?></div></td>
		<td><div align="center"><?php echo $z[$x];?></div></td>
		<td><div align="center"><?php echo sprintf("%01.2f",$m1t[$x]);?></div></td>
		<td><div align="center"><?php echo sprintf("%01.2f",$m2t[$x]);?></div></td>
		<td><div align="center"><?php echo sprintf("%01.2f",$b0t[$x]);?></div></td>
		<td><div align="center"><?php echo sprintf("%01.2f",$b1t[$x]);?></div></td>
		<td><div align="center"><?php echo sprintf("%01.2f",$z1t[$x]);?></div></td>
		<td><div align="center"><?php echo sprintf("%01.2f",$et[$x]);?></div></td>
	  </tr>
	<?php
	}
	for($x = 0; $x < 12; $x++) 
	{
	?>
	  <tr>
		<td><div align="center"></div></td>
		<td><div align="center"></div></td>
		<td><div align="center"></div></td>
		<td><div align="center"></div></td>
		<td><div align="center"></div></td>
		<td><div align="center"></div></td>
		<td><div align="center"><?php echo sprintf("%01.2f",$z1t[$x+$countz]);?></div></td>
		<td><div align="center"></div></td>

	  </tr>
	<?php
	}
	?>
	  <tr>
		<td><div align="center"></div></td>
		<td><div align="center">MSE</div></td>
		<td><div align="center"><?php echo sprintf("%01.2f",average($mse)); ?></div></td>
		<td><div align="center"></div></td>
		<td><div align="center"></div></td>
		<td><div align="center"></div></td>
		<td><div align="center"></div></td>
		<td><div align="center"></div></td>
	  </tr>
	  <tr>
		<td><div align="center"></div></td>
		<td><div align="center">RMSE</div></td>
		<td><div align="center"><?php echo sprintf("%01.2f",sqrt(average($mse))); ?></div></td>
		<td><div align="center"></div></td>
		<td><div align="center"></div></td>
		<td><div align="center"></div></td>
		<td><div align="center"></div></td>
		<td><div align="center"></div></td>
	  </tr>
	  <tr>
		<td><div align="center"></div></td>
		<td><div align="center">MAD</div></td>
		<td><div align="center"><?php echo sprintf("%01.2f",average($mad)); ?></div></td>
		<td><div align="center"></div></td>
		<td><div align="center"></div></td>
		<td><div align="center"></div></td>
		<td><div align="center"></div></td>
		<td><div align="center"></div></td>
	  </tr>
	  <tr>
		<td><div align="center"></div></td>
		<td><div align="center">MAPE</div></td>
		<td><div align="center"><?php echo sprintf("%01.2f",average($mape)); ?></div></td>
		<td><div align="center"></div></td>
		<td><div align="center"></div></td>
		<td><div align="center"></div></td>
		<td><div align="center"></div></td>
		<td><div align="center"></div></td>
	  </tr>
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