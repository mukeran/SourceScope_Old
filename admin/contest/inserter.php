<?php
session_start();
require_once '../common/database.php';
require_once '../common/function.php';
if(!isLogin() || !isAdmin())
	die('No Permission!');
if(!isset($_POST['cid']))
	die(1);
if(!isset($_POST['name']))
	die(1);
if(!isset($_POST['starttime']))
	die(1);
if(!isset($_POST['endtime']))
	die(1);
if(!isset($_POST['contestform']))
	die(1);
if(!isset($_POST['openness']))
	die(1);
if(!isset($_POST['problem']))
	die(1);
if(!isset($_POST['introduce']))
	die(1);
if(!isset($_POST['person']))
	die(1);
$cid = stripslashes($_POST['cid']);
$name = stripslashes($_POST['name']);
$starttime = stripslashes($_POST['starttime']);
$endtime = stripslashes($_POST['endtime']);
$contestform = stripslashes($_POST['contestform']);
$openness = stripslashes($_POST['openness']);
$problem = stripslashes($_POST['problem']);
$introduce = stripslashes($_POST['introduce']);
$person = stripslashes($_POST['person']);
$problem_array = json_decode($problem, true);
$person_array = json_decode($person, true);

if($name == '')
	die(json_encode(array('status' => 'Invalid name')));
$introduce = str_replace("\n", "<br/>", $introduce);

$starttime_timestamp = strtotime($starttime);
$endtime_timestamp = strtotime($endtime);

if(date('Y-m-d H:i:s', $starttime_timestamp) != $starttime)
	die(json_encode(array('status' => 'Invaild start time')));
if(date('Y-m-d H:i:s', $endtime_timestamp) != $endtime)
	die(json_encode(array('status' => 'Invaild end time')));
if($starttime_timestamp > $endtime_timestamp)
	die(json_encode(array('status' => 'End time is before start time')));


if(count($problem_array) == 0)
	die(json_encode(array('status' => 'No problem')));
$cnt_problem = count($problem_array);
for($i = 0; $i < $cnt_problem; $i++) {
	if(count($problem_array[$i]) != 2 || !isset($problem_array[$i]['pid']) || !isset($problem_array[$i]['custom_title']))
		die(json_encode(array('status' => "Invalid problem format", 'order' => $i)));
	$pid = $problem_array[$i]['pid'];
	$query = "SELECT * FROM `problem` WHERE `problem`.`pid`='$pid' LIMIT 0, 1;";
	$result = mysql_query($query, $con);
	if(!mysql_num_rows($result))
		die(json_encode(array('status' => "Invalid PID", 'order' => $i)));
	$row = mysql_fetch_array($result);
	mysql_free_result($result);
	$default_version = $row['default_version'];
	$query = "SELECT * FROM `problem_version` WHERE `problem_version`.`vid`='$default_version' LIMIT 0, 1;";
	$result = mysql_query($query, $con);
	if(!mysql_num_rows($result))
		die(json_encode(array('status' => "Problem Error", 'order' => $i)));
	$row = mysql_fetch_array($result);
	$title = $row['title'];
	if($problem_array[$i]['custom_title'] == '')
		$problem_array[$i]['custom_title'] = $title;
}
$problem = json_encode($problem_array);
$problem = mysql_real_escape_string($problem);
$cnt_person = count($person_array);
$vis = array();
for($i = 0; $i < $cnt_person; $i++) {
	$username = $person_array[$i]["username"];
	$query = "SELECT * FROM `user` WHERE `username`='$username' LIMIT 0, 1;";
	$result = mysql_query($query, $con);
	if(!mysql_num_rows($result))
		die(json_encode(array('status' => "Invalid user", 'order' => $i)));
	$row = mysql_fetch_array($result);
	$uid = $row['uid'];
	if(isset($vis[$uid]))
		die(json_encode(array('status' => "Double user", 'order' => $i)));
	$person_array[$i]['uid'] = $uid;
	unset($person_array[$i]['username']);
	$vis[$uid] = true;
}
$person = json_encode($person_array);
$person = mysql_real_escape_string($person);
if($cid == 'NEW') {
	$query = "INSERT INTO `contest` (`name`, `introduce`, `contestform`, `problem`, `person`, `start_time`, `end_time`, `openness`) VALUES ('$name', '$introduce', '$contestform', '$problem', '$person', '$starttime', '$endtime', '$openness');";
	$result = mysql_query($query, $con);
	if(!mysql_affected_rows())
		die(json_encode(array('status' => 'failed')));
	$cid = mysql_insert_id();
	die(json_encode(array('status' => 'success', 'cid' => $cid)));
}
else {
	$query = "SELECT * FROM `contest` WHERE `cid`='$cid' LIMIT 0, 1;";
	$result = mysql_query($query, $con);
	if(!mysql_num_rows($result))
		die(json_encode(array('status' => 'Invaild CID')));
	$query = "UPDATE `contest` SET `name`='$name', `introduce`='$introduce', `contestform`='$contestform', `problem`='$problem', `person`='$person', `start_time`='$starttime', `end_time`='$endtime', `openness`='$openness' WHERE `cid`='$cid';";
	$result = mysql_query($query, $con);
	if(!mysql_affected_rows())
		die(json_encode(array('status' => 'failed')));
	die(json_encode(array('status' => 'success', 'cid' => $cid)));
}