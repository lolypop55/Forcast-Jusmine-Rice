<html>
<body>
<form action="edit_volm_save.php?id=<?php echo $_GET["id"];?>" name="frmEdit" method="post">
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
  $strSQL = "SELECT * FROM  vol_m WHERE VolID = '".$_GET["id"]."'";
  $objQuery = mysql_query($strSQL) or die ("Error Query [".$strSQL."]");
  $objQuery = mysql_query($strSQL);
  $objResult = mysql_fetch_array($objQuery);
if(!$objResult)
{
  echo "Not found CustomerID=".$_GET["id"];
}
else
{
?>
<table width="600" border="1">
  <tr>
    <th width="91"> <div align="center">VolID </div></th>
    <th width="160"> <div align="center">VolM </div></th>
    <th width="198"> <div align="center">VolY </div></th>
    <th width="97"> <div align="center">Vol </div></th>
  </tr>
  <tr>
    <td><div align="center"><?php echo $objResult["VolID"];?></div></td>
    <td><input type="text" name="volm" size="20" value="<?php echo $objResult["VolM"];?>"></td>
    <td><input type="text" name="voly" size="20" value="<?php echo $objResult["VolY"];?>"></td>
    <td><div align="center"><input type="text" name="vol" size="2" value="<?php echo $objResult["Vol"];?>"></div></td>
  </tr>
  </table>
  <input type="submit" name="submit" value="submit">
  <?php
  }
  mysql_close();
  ?>
  </form>
  <br>
  <a href="show_vol.php">Back</a>
</body>
</html>