<?php
require_once '../common/function.php';
require_once '../common/database.php';

$constant = "constant";
if($_SERVER['REMOTE_ADDR'] != '127.0.0.1')
	die('No permission');
if(!isset($_POST['pid']))
	die('No PID');
$pid = $_POST['pid'];
$query = "UPDATE `submission` SET `status`='{$constant('STATUS_PENDINGREJUDGE')}' WHERE `pid`='$pid';";
$result = mysql_query($query, $con);
if(!mysql_affected_rows())
	die('Edit status error');
$query = "SELECT `sid` FROM `submission` WHERE `pid`='$pid';";
$result_submission_all = mysql_query($query, $con);
while($submission = mysql_fetch_array($result_submission_all)) {
	$sid = $submission['sid'];
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "http://localhost/judge/send.php");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, "sid=".$sid);
	curl_setopt($ch, CURLOPT_TIMEOUT, 1);
	$output = curl_exec($ch);
	curl_close($ch);
//	sleep(2);
}
