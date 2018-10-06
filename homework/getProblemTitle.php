<?php
require_once '../common/function.php';
require_once '../common/database.php';
if(!isset($_GET['pid']))
	die('N/A');

$pid = $_GET['pid'];
$query = "SELECT * FROM `problem` WHERE `problem`.`pid`='$pid' LIMIT 0, 1;";
$result = mysql_query($query, $con);
if(!mysql_num_rows($result))
	die('N/A');
$row = mysql_fetch_array($result);
mysql_free_result($result);
$default_version = $row['default_version'];
$query = "SELECT * FROM `problem_version` WHERE `problem_version`.`vid`='$default_version' LIMIT 0, 1";
$result = mysql_query($query, $con);
if(!mysql_num_rows($result))
	die('Problem Error');
$row = mysql_fetch_array($result);
mysql_free_result($result);
die($row['title']);