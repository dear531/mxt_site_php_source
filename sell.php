<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"></head>
<body>
<?php
//error_reporting(0);
session_start();
if($_SESSION['temp'][2] != 1)
{
	echo "<script language=javascript>alert('请登陆再操作，不登陆只能以游客身份查询发布信息!')</script>";
	echo "<script language=javascript>window.parent.show.location.href='login2.html'</script>";
	exit;
}
/**
for($i=0;$i<3;$i++)
{
	echo $_SESSION['temp'][$i].'<br />';
}
**/
echo "<strong>用户添加</strong>";
$conn = mysql_connect("db4.cwsurf.de", "mxd", "aying7333");
mysql_query("set names 'utf8' ");
mysql_query("set character_set_client=utf8");
mysql_query("set character_set_results=utf8");
if(!$conn)
{
	echo "<p>connect databases error:$php_errormsg</p>";
	exit;
}
echo "<p>connect databases succes</p>";

$ret = mysql_select_db("mxd"); 
if(!$ret)
{
	echo "<p>connect manage error:$php_errormsg</p>";
	exit;
}
echo "<p>connect manage succes</p>";

$fields = mysql_list_fields("mxd", $_SESSION['temp'][0], $conn);
$columns = mysql_num_fields($fields);
for ($i = 0; $i < $columns; $i++) {
	$table_text[$i] = mysql_field_name($fields, $i);
//	echo $table_text[$i]. "</br>";
}
$sql = "select * from ".$_SESSION['temp'][0];
$ret = mysql_query($sql, $conn);
if (!$ret)
{
	echo "<p>".$sql." error:".$php_errormsg."</p>";
	exit;
}
echo "<p>".$sql." succes</p>";
echo "<table border='1' cellpadding='0' cellspacing='0'
bordercolordark=#0066ff bordercolorlight=#ffffff
bgcolor='#ffffff'
>";
for ($i = 0; $i < $columns; $i++)
{
	if ($i == 0)
		echo "<tr>";
	echo "<th>".$table_text[$i]."</th>";
	if ($i == $columns - 1)
		echo "</tr>";
}
while ($row = mysql_fetch_array($ret)) 
{
	for ($i = 0; $i < $columns; $i++)
	{
		if ($i == 0)
			echo "<tr>";
		echo "<td>".$row[$table_text[$i]]."</td>";
		if ($i == $columns - 1)
			echo "</tr>";
	}  
}
echo "</table>";
if (isset($_POST['action']) && $_POST['action'] == 'submitted') {
	echo '<pre>';
	//print_r($_POST);
	$insert = "INSERT INTO ".$_SESSION['temp'][0];
	for ($i = 0; $i < $columns; $i++)
	{
		if($i == 0)
			$tmp = " ( ".$table_text[$i]." ,";
		else if ($i == $columns - 1)
			$tmp = $table_text[$i]." ) ";
		else
			$tmp = $table_text[$i]." , ";
			$insert = $insert.$tmp;
	}
	$insert = $insert." VALUES ";
	for ($i = 0; $i < $columns; $i++)
	{
		if($i == 0)
			$tmp = " ( ".$_POST[personal][$table_text[$i]]." ,";
		else if ($i == $columns - 1)
			$tmp = $_POST[personal][$table_text[$i]]." ) ";
		else
			$tmp = $_POST[personal][$table_text[$i]]." , ";
			$insert = $insert.$tmp;
	}  
	echo $insert;
	$ret = mysql_query($insert, $conn);
if (!$ret)
{
	echo "<p>".$insert." error:".$php_errormsg."</p>";
	exit;
}
echo "<p>".$insert." succes</p>";
	echo '<a href="'. $_SERVER['PHP_SELF'] .'">返回添加</a>';
	echo '</pre>';
} else {
echo "<form action=".$_SERVER['PHP_SELF']." method=\"post\">";
	for ($i = 0; $i < $columns; $i++)
	{
		echo $table_text[$i].":<input type=\"text\" name=\"personal[".$table_text[$i]."]\"><br/>";
	}  
echo "<input type=\"hidden\" name=\"action\" value=\"submitted\" />";
echo "<input type=\"submit\" name=\"submit\" value=\"增加物品\" />";
echo "</form>";
}
mysql_close($conn);
?>
</body>
</html>
