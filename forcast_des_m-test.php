<html>
<head>
<title>Joke</title>
</head>
<body>
<?php
	//session_start();
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

	//$alp=0.2;

	mysql_connect("$host","$user","$password");
	mysql_select_db("$mydatabase");
    
    //  Del Table dma-m
	$strDEL = "DELETE FROM vol_des_m ";
	$objQuery = mysql_query($strDEL);

	// Del Table error_dma
	$strDEL = "DELETE FROM vol_error_des_m ";
	$objQuery = mysql_query($strDEL);


	$strSQL = "SELECT * FROM  vol_m";
	$objQuery = mysql_query($strSQL) or die ("Error Query [".$strSQL."]");
	while($objResult = mysql_fetch_array($objQuery))
	{
		$t[]=$num++;
		$z[]=$objResult["Vol"];
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

	$countmpe=0;

for($alp=0.1 ; $alp<1 ;$alp+=0.01)
{


	//s1t colum
	for($x = 0; $x < $countz; $x++) {
	if($x<1)
		$s1t[$countmpe][$x]=$z[$x];
	else{
		$s1t[$countmpe][$x]=$alp*$z[$x]+(1-$alp)*$s1t[$countmpe][$x-1];
		}
	}

	//s2t colum
	for($x = 0; $x < $countz; $x++) {
	if($x<1)
		$s2t[$countmpe][$x]=$z[$x];
	else{
		$s2t[$countmpe][$x]=$alp*$s1t[$countmpe][$x]+(1-$alp)*$s2t[$countmpe][$x-1];
		}
	}

	//b0t colum
	for($x = 0; $x < $countz; $x++) {
	if($x<1)
		$b0t[$countmpe][$x]=$z[$x];
	else{
		$b0t[$countmpe][$x]=2*$s1t[$countmpe][$x]-$s2t[$countmpe][$x];
		}
	}

	//b1t colum
	for($x = 0; $x < $countz; $x++) {
	if($x<1)
		$b1t[$countmpe][$x]=0;
	else{
		$b1t[$countmpe][$x]=$alp/(1-$alp)*($s1t[$countmpe][$x]-$s2t[$countmpe][$x]);
		}
	}
	//Zt colum
	for($x = 0; $x < $countz; $x++) {
		
		$zt[$countmpe][$x]=$b0t[$countmpe][$x]+$b1t[$countmpe][$x];
	}
	
	//et colum

	for($x = 0; $x < $countz; $x++) {
	
		$et[$countmpe][$x]=$z[$x]-$zt[$countmpe][$x];

	}

	//forcast
	for($x = 0; $x < $countz+12; $x++) {
	if($x < $countz)
		;
	else{
		$temp++;
		$zt[$countmpe][$x]=$b0t[$countmpe][$countz-1]+$temp*$b1t[$countmpe][$countz-1];
		}
	}

		//mse colum
	for($x = 0; $x < $countz; $x++) {

		$mse[$countmpe]=abs($et[$countmpe][$x]*$et[$countmpe][$x]);

	}
	//mad colum
	for($x = 0; $x < $countz; $x++) {

		$mad[$countmpe]=abs($et[$countmpe][$x]);

	}
	//mape colum
	for($x = 0; $x < $countz; $x++) {

		$mape[$countmpe]=abs(($et[$countmpe][$x]/$z[$x])*100);

	}

	$msef[$countmpe]=average($mse);
	$rmsef[$countmpe]=sqrt($msef[$countmpe]); 
	$madf[$countmpe]=average($mad);
	$mapef[$countmpe]=average($mape);
	$alphaf[$countmpe]=$alp;
 	$countmpe++;
 	$temp=0;

}


$minmape=array_search(min($mapef), $mapef);



?>


	<table width="1000" border="1">
	<p> Double exponential smoothing, DES </p>
	<p> Alpha = <?php echo $alphaf[$minmape]; ?> </p>
	  <tr>
		<th width="100"> <div align="center">T </div></th>
		<th width="100"> <div align="center">Zt </div></th>
		<th width="100"> <div align="center">S1t </div></th>
		<th width="100"> <div align="center">S2t </div></th>
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
		<td><div align="center"><?php echo sprintf("%01.2f",$s1t[$minmape][$x]);?></div></td>
		<td><div align="center"><?php echo sprintf("%01.2f",$s2t[$minmape][$x]);?></div></td>
		<td><div align="center"><?php echo sprintf("%01.2f",$b0t[$minmape][$x]);?></div></td>
		<td><div align="center"><?php echo sprintf("%01.3f",$b1t[$minmape][$x]);?></div></td>
		<td><div align="center"><?php echo sprintf("%01.2f",$zt[$minmape][$x]);?></div></td>
		<td><div align="center"><?php echo sprintf("%01.2f",$et[$minmape][$x]);?></div></td>
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
		<td><div align="center"><?php echo sprintf("%01.2f",$zt[$minmape][$x+$countz]); ?></div></td>
		<td><div align="center"></div></td>

	  </tr>
	<?php
	}
	?>
	  <tr>
		<td><div align="center"></div></td>
		<td><div align="center">MSE</div></td>
		<td><div align="center"><?php echo sprintf("%01.2f",$msef[$minmape]); ?></div></td>
		<td><div align="center"></div></td>
		<td><div align="center"></div></td>
		<td><div align="center"></div></td>
		<td><div align="center"></div></td>
		<td><div align="center"></div></td>
	  </tr>
	  <tr>
		<td><div align="center"></div></td>
		<td><div align="center">RMSE</div></td>
		<td><div align="center"><?php echo sprintf("%01.2f",$rmsef[$minmape]); ?></div></td>
		<td><div align="center"></div></td>
		<td><div align="center"></div></td>
		<td><div align="center"></div></td>
		<td><div align="center"></div></td>
		<td><div align="center"></div></td>
	  </tr>
	  <tr>
		<td><div align="center"></div></td>
		<td><div align="center">MAD</div></td>
		<td><div align="center"><?php echo sprintf("%01.2f",$madf[$minmape]); ?></div></td>
		<td><div align="center"></div></td>
		<td><div align="center"></div></td>
		<td><div align="center"></div></td>
		<td><div align="center"></div></td>
		<td><div align="center"></div></td>
	  </tr>
	  <tr>
		<td><div align="center"></div></td>
		<td><div align="center">MAPE</div></td>
		<td><div align="center"><?php echo sprintf("%01.2f",$mapef[$minmape]); ?></div></td>
		<td><div align="center"></div></td>
		<td><div align="center"></div></td>
		<td><div align="center"></div></td>
		<td><div align="center"></div></td>
		<td><div align="center"></div></td>
	  </tr>
	</table>


	<?php
	for($x = 0; $x < count($zt[$minmape]); $x++) {

		if ($x < $countz){
			$strSQL = "INSERT INTO vol_des_m ";
			$strSQL .="(t,zt,s1t,s2t,b0t,b1t,z1t,et) ";
			$strSQL .="VALUES ";
			$strSQL .="('".$t[$x]."','".$z[$x]."','".$s1t[$minmape][$x]."' ";
			$strSQL .=",'".$s2t[$minmape][$x]."','".$b0t[$minmape][$x]."','".$b1t[$minmape][$x]."','".$zt[$minmape][$x]."','".$et[$minmape][$x]."') ";
			$objQuery = mysql_query($strSQL) or die ("Error Query [".$strSQL."]");
			
		}
		else{
			$time=$x+1;
			$strSQL = "INSERT INTO vol_des_m ";
			$strSQL .="(t,zt,s1t,s2t,b0t,b1t,z1t,et) ";
			$strSQL .="VALUES ";
			$strSQL .="('".$time."','0','0' ";
			$strSQL .=",'0','0','0','".$zt[$minmape][$x]."','0') ";
			$objQuery = mysql_query($strSQL) or die ("Error Query [".$strSQL."]");
		}

	}
	$strSQL = "INSERT INTO vol_error_des_m ";
	$strSQL .="(mse,rmse,mad,mape) ";
	$strSQL .="VALUES ";
	$strSQL .="('".$msef[$minmape]."','".$rmsef[$minmape]."','".$madf[$minmape]."','".$mapef[$minmape]."') ";
	$objQuery = mysql_query($strSQL) or die ("Error Query [".$strSQL."]");










	mysql_close();
?>

	<br>
	<a href="graph_des_m.php">See Graph</a><br>
  <br>
  <a href="admin_page.php">Back</a><br>
  <br>
  <a href="logout.php">Logout</a>

</body>
</html>