<?php
session_start();
require_once '../common/function.php';
require_once '../common/database.php';
$ret  = array();
if(!isset($_POST['id']))
	die('No ID');
$id = $_POST['id'];
$ret['id'] = $id;
if(!isLogin() || !isAdmin()) {
	$ret['title'] = 'No permission';
	die(json_encode($ret));
}
if(!isset($_POST['pid'])) {
	$ret['title'] = 'N/A';
	die(json_encode($ret));
}

$pid = $_POST['pid'];
$query = "SELECT * FROM `problem` WHERE `problem`.`pid`='$pid' LIMIT 0, 1;";
$result = mysql_query($query, $con);
if(!mysql_num_rows($result)) {
	$ret['title'] = 'N/A';
	die(json_encode($ret));
}
$row = mysql_fetch_array($result);
mysql_free_result($result);
$default_version = $row['default_version'];
$query = "SELECT * FROM `problem_version` WHERE `problem_version`.`vid`='$default_version' LIMIT 0, 1";
$result = mysql_query($query, $con);
if(!mysql_num_rows($result)) {
	$ret['title'] = 'Problem Error';
	die(json_encode($ret));
}
$row = mysql_fetch_array($result);
mysql_free_result($result);
$ret['title'] = $row['title'];
die(json_encode($ret));