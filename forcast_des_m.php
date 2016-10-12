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



for($alp=0.1 ; $alp<1 ;$alp+=0.01)
{


	//s1t colum
	for($x = 0; $x < $countz; $x++) {
	if($x<1)
		$s1t[$x]=$z[$x];
	else{
		$s1t[$x]=$alp*$z[$x]+(1-$alp)*$s1t[$x-1];
		}
	}

	//s2t colum
	for($x = 0; $x < $countz; $x++) {
	if($x<1)
		$s2t[$x]=$z[$x];
	else{
		$s2t[$x]=$alp*$s1t[$x]+(1-$alp)*$s2t[$x-1];
		}
	}

	//b0t colum
	for($x = 0; $x < $countz; $x++) {
	if($x<1)
		$b0t[$x]=$z[$x];
	else{
		$b0t[$x]=2*$s1t[$x]-$s2t[$x];
		}
	}

	//b1t colum
	for($x = 0; $x < $countz; $x++) {
	if($x<1)
		$b1t[$x]=0;
	else{
		$b1t[$x]=$alp/(1-$alp)*($s1t[$x]-$s2t[$x]);
		}
	}
	//Zt colum
	for($x = 0; $x < $countz; $x++) {
		
		$zt[$x]=$b0t[$x]+$b1t[$x];
	}
	
	//et colum

	for($x = 0; $x < $countz; $x++) {
	
		$et[$x]=$z[$x]-$zt[$x];

	}

	//forcast
	for($x = 0; $x < $countz+12; $x++) {
	if($x < $countz)
		;
	else{
		$temp++;
		$zt[$x]=$b0t[$countz-1]+$temp*$b1t[$countz-1];
		}
	}

		//mse colum
	for($x = 0; $x < $countz; $x++) {

		$mse[$x]=abs($et[$x]*$et[$x]);

	}
	//mad colum
	for($x = 0; $x < $countz; $x++) {

		$mad[$x]=abs($et[$x]);

	}
	//mape colum
	for($x = 0; $x < $countz; $x++) {

		 $mape[$x]=abs(($et[$x]/$z[$x])*100);

	}

	$msef=average($mse);

	$rmsef=sqrt($msef); 

	$madf=average($mad);

	$mapef=average($mape);



?>


	<table width="1000" border="1">
	<p> Double exponential smoothing, DES </p>
	<p> Alpha = <?php echo $alp; ?> </p>
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
		<td><div align="center"><?php echo sprintf("%01.2f",$s1t[$x]);?></div></td>
		<td><div align="center"><?php echo sprintf("%01.2f",$s2t[$x]);?></div></td>
		<td><div align="center"><?php echo sprintf("%01.2f",$b0t[$x]);?></div></td>
		<td><div align="center"><?php echo sprintf("%01.3f",$b1t[$x]);?></div></td>
		<td><div align="center"><?php echo sprintf("%01.2f",$zt[$x]);?></div></td>
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
		<td><div align="center"><?php echo sprintf("%01.2f",$zt[$x+$countz]); ?></div></td>
		<td><div align="center"></div></td>

	  </tr>
	<?php
	}
	?>
	  <tr>
		<td><div align="center"></div></td>
		<td><div align="center">MSE</div></td>
		<td><div align="center"><?php echo sprintf("%01.2f",$msef); ?></div></td>
		<td><div align="center"></div></td>
		<td><div align="center"></div></td>
		<td><div align="center"></div></td>
		<td><div align="center"></div></td>
		<td><div align="center"></div></td>
	  </tr>
	  <tr>
		<td><div align="center"></div></td>
		<td><div align="center">RMSE</div></td>
		<td><div align="center"><?php echo sprintf("%01.2f",$rmsef); ?></div></td>
		<td><div align="center"></div></td>
		<td><div align="center"></div></td>
		<td><div align="center"></div></td>
		<td><div align="center"></div></td>
		<td><div align="center"></div></td>
	  </tr>
	  <tr>
		<td><div align="center"></div></td>
		<td><div align="center">MAD</div></td>
		<td><div align="center"><?php echo sprintf("%01.2f",$madf); ?></div></td>
		<td><div align="center"></div></td>
		<td><div align="center"></div></td>
		<td><div align="center"></div></td>
		<td><div align="center"></div></td>
		<td><div align="center"></div></td>
	  </tr>
	  <tr>
		<td><div align="center"></div></td>
		<td><div align="center">MAPE</div></td>
		<td><div align="center"><?php echo sprintf("%01.2f",$mapef); ?></div></td>
		<td><div align="center"></div></td>
		<td><div align="center"></div></td>
		<td><div align="center"></div></td>
		<td><div align="center"></div></td>
		<td><div align="center"></div></td>
	  </tr>
	</table>


<?php

}

?>




	<?php
	for($x = 0; $x < count($zt); $x++) {

		if ($x < $countz){
			$strSQL = "INSERT INTO vol_des_m ";
			$strSQL .="(t,zt,s1t,s2t,b0t,b1t,z1t,et) ";
			$strSQL .="VALUES ";
			$strSQL .="('".$t[$x]."','".$z[$x]."','".$s1t[$x]."' ";
			$strSQL .=",'".$s2t[$x]."','".$b0t[$x]."','".$b1t[$x]."','".$zt[$x]."','".$et[$x]."') ";
			$objQuery = mysql_query($strSQL) or die ("Error Query [".$strSQL."]");
			
		}
		else{
			$time=$x+1;
			$strSQL = "INSERT INTO vol_des_m ";
			$strSQL .="(t,zt,s1t,s2t,b0t,b1t,z1t,et) ";
			$strSQL .="VALUES ";
			$strSQL .="('".$time."','0','0' ";
			$strSQL .=",'0','0','0','".$zt[$x]."','0') ";
			$objQuery = mysql_query($strSQL) or die ("Error Query [".$strSQL."]");
		}

	}
	$strSQL = "INSERT INTO vol_error_des_m ";
	$strSQL .="(mse,rmse,mad,mape) ";
	$strSQL .="VALUES ";
	$strSQL .="('".$msef."','".$rmsef."','".$madf."','".$mapef."') ";
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