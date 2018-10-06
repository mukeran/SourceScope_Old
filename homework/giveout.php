<?php
session_start();
require_once "../common/database.php";
require_once "../common/function.php";
if(!isset($_POST['user_id']))
	die("Invaild user");
if(!isset($_POST['problem_id']))
	die("Invaild Problem");
if(!isset($_POST['end_time']))
	die("Invaild time");
$users = json_decode(stripslashes($_POST['user_id']), true);
if(count($users) == 0)
	die("Invaild user");
$problems = json_decode(stripslashes($_POST['problem_id']), true);
if(count($problems) == 0)
	die("Invaild Problem");
$etime = $_POST['end_time'];
if(!isLogin())
	die("Please Login First");
$uid = $_SESSION['uid'];
function checkDatetime($str, $format="Y-m-d H:i:s"){  
	$unixTime=strtotime($str);  
	$checkDate= date($format, $unixTime);  
	if($checkDate==$str)  
		return 1;   
	return 0;  
}  
if(!checkDatetime($etime))
	die("Invaild time");
$parr = array();
foreach($problems as $problem){
	$query = "SELECT * FROM `problem` WHERE `pid`='$problem' LIMIT 0, 1;";
	$result = mysql_query($query);
	if(mysql_num_rows($result) == 0) continue;
	mysql_free_result($result);

	array_push($parr, array('end_time'=>$etime, 'pid'=>$problem, 'teacher_id'=>$uid));
}
if(count($parr) == 0)
	die("Invaild Problem");
foreach($users as $user){
	$query = "SELECT * FROM `homework_relative` WHERE `uid`='$user' AND `teacher_id`='$uid' AND `status`='1' LIMIT 0, 1;";
	$result = mysql_query($query);
	if($user!=$uid&&mysql_num_rows($result) == 0)
		continue;
	mysql_free_result($result);

	$query = "SELECT `homework` FROM `user` WHERE `uid`='$user' LIMIT 0, 1;";
	$result = mysql_query($query);

	if(mysql_num_rows($result) == 0)
		continue;

	$row = mysql_fetch_array($result);
	$arr = json_decode($row['homework'], true);

	$arr = array_merge($arr, $parr);
	$arr = array_unique($arr);

	$ajson = json_encode($arr);

	$query = "UPDATE `user` SET `homework`='$ajson' WHERE `uid`='$user';";
	mysql_query($query);
}
die("0");