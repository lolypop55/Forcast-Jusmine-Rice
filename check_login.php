<?php
	session_start();
	include "db_connect.php";
	mysql_connect("$host","$user","$password");
	mysql_select_db("$mydatabase");
	$strSQL = "SELECT * FROM admin WHERE Username = '".mysql_real_escape_string($_POST['txtUsername'])."' 
	and Password = '".mysql_real_escape_string($_POST['txtPassword'])."'";
	$objQuery = mysql_query($strSQL);
	$objResult = mysql_fetch_array($objQuery);
	if(!$objResult)
	{
			echo "Username and Password Incorrect!";
			header("location:index.php");
	}
	else
	{
			$_SESSION["UserID"] = $objResult["UserID"];
			$_SESSION["Status"] = $objResult["Status"];

			session_write_close();
			
			/*if($objResult["Status"] == "ADMIN")
			{
				header("location:admin_page.php");
			}
			else
			{
				header("location:user_page.php");
			}
			*/
			header("location:admin_page.php");
	}
	mysql_close();
?>