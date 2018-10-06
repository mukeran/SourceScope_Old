<?php
session_start();
require_once '../common/function.php';
require_once '../common/database.php';

$constant = "constant";
if(!isLogin() || !isAdmin())
	die('No permission');
if(!isset($_POST['sid']) && !isset($_POST['pid']))
	die('No SID or PID');
if(isset($_POST['sid'])) {
	$sid = htmlspecialchars($_POST['sid']);
	$sid = mysql_real_escape_string($sid);
	$query = "SELECT count(*) AS `count` FROM `submission` WHERE `sid`='$sid' LIMIT 0, 1;";
	$result_submission = mysql_query($query, $con);
	$submission_cnt = mysql_fetch_array($result_submission);
	if(!$submission_cnt['count'])
		die('No such submission');
	$query = "UPDATE `submission` SET `status`='{$constant('STATUS_PENDINGREJUDGE')}' WHERE `sid`='$sid';";
	$result_submission = mysql_query($query, $con);
	if(!mysql_affected_rows())
		die('Edit status error');
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "http://localhost/judge/send.php");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, "sid=".$sid);
	curl_setopt($ch, CURLOPT_TIMEOUT, 1);
	$output = curl_exec($ch);
	curl_close($ch);
}
else if(isset($_POST['pid'])) {
	$pid = htmlspecialchars($_POST['pid']);
	$pid = mysql_real_escape_string($pid);
	$query = "SELECT * FROM `problem` WHERE `pid`='$pid' LIMIT 0, 1;";
	$result_problem = mysql_query($query, $con);
	if(!mysql_num_rows($result_problem))
		die('No such problem');
	$problem = mysql_fetch_array($result_problem);
	$pid = $problem['id'];
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "http://localhost/submission/rejudge_problem.php");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, "pid=".$pid);
	curl_setopt($ch, CURLOPT_TIMEOUT, 1);
	$output = curl_exec($ch);
	curl_close($ch);
}
die('success');