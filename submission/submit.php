<?php
@session_start();
require_once "../common/function.php";
require_once "../common/database.php";
function getProblemNumID($str) {
	$l = strlen($str);
	$order = 26 * ($l - 1);
	$order += ord($str[0]) - ord('A') + 1;
	return $order;
}
if(!isLogin())
	die('No login');
if(!isset($_SESSION['uid']))
	die('Login error');
if(!isset($_POST['pid']))
	die('No PID');
if(!isset($_POST['code']))
	die('No code');
if(!isset($_POST['language']))
	die('No language');
if(isset($_POST['cid']))
	$mode = 'contest';
else
	$mode = 'problem';
$pid = $_POST['pid'];
$language = $_POST['language'];
$code = $_POST['code'];
$uid = $_SESSION['uid'];
if($mode == 'contest') {
	$cid = $_POST['cid'];
	$query = "SELECT * FROM `contest` WHERE `cid`='$cid' LIMIT 0, 1;";
	$result = mysql_query($query, $con);
	if(!mysql_num_rows($result))
		die('No such contest');
	$row = mysql_fetch_array($result);
	mysql_free_result($result);
	$problems = $row['problem'];
	$problems = json_decode($problems, true);
	$cnt_problem = count($problems);
	$num_pid = getProblemNumID($pid);
	if($num_pid > $cnt_problem)
		die('No such problem');
	$pid = $problems[$num_pid - 1]['pid'];
}
$query = "SELECT * FROM `problem` WHERE `pid`='$pid' LIMIT 0, 1;";
$result = mysql_query($query, $con);
if(!mysql_num_rows($result))
	die('No such problem');
$row = mysql_fetch_array($result);
$id = $row['id'];
mysql_free_result($result);
$codelength = strlen($code);
if($mode == 'problem') {
	$query = "INSERT INTO `submission` (`author`, `pid`, `language`, `code`, `status`, `length`) VALUES ('$uid', '$id', '$language', '$code', ". STATUS_PENDING. ", $codelength);";
}
else {
	$query = "INSERT INTO `submission` (`author`, `pid`, `cid`, `language`, `code`, `status`, `length`) VALUES ('$uid', '$id', '$cid', '$language', '$code', ". STATUS_PENDING. ", $codelength);";
}
$result = mysql_query($query, $con);
if(!mysql_affected_rows())
	die('Submit error');
$sid = mysql_insert_id();
$query = "UPDATE `user` SET `cnt_submission`=(SELECT count(*) FROM `submission` WHERE `author`='$uid') WHERE `uid`='$uid';";
$result = mysql_query($query, $con);
$query = "UPDATE `problem` SET `cnt_submission`=(SELECT count(*) FROM `submission` WHERE `pid`='$id') WHERE `id`='$id';";
$result = mysql_query($query, $con);
$query = "UPDATE `problem` SET `cnt_submission_people`=(SELECT count(DISTINCT `author`) FROM `submission` WHERE `pid`='$id') WHERE `id`='$id';";
$result = mysql_query($query, $con);
//if(!mysql_affected_rows())
//	echo 'Edit user submission error';
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "http://localhost/judge/send.php");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, "sid=".$sid);
curl_setopt($ch, CURLOPT_TIMEOUT, 1);
$output = curl_exec($ch);
curl_close($ch);
die('success');