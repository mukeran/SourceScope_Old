<?php
require_once '../common/function.php';
require_once '../common/database.php';
function sendSocketMsg($host, $port, $str, $back = false) {
	$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
	socket_set_option($socket, SOL_SOCKET, SO_RCVTIMEO, array("sec" => 5, "usec" => 0 ));
	socket_set_option($socket, SOL_SOCKET, SO_SNDTIMEO, array("sec" => 5, "usec" => 0 ));
	if ($socket < 0)
		return false;
	$result = @socket_connect($socket, $host, $port);
	if ($result == false)
		return false;
	socket_write($socket, $str, strlen($str));

	if ($back) {
		$input = socket_read($socket, 1024);
		socket_close($socket);
		return $input;
	}
	else {
		socket_close($socket);
		return true;
	}    
}
if($_SERVER['REMOTE_ADDR'] != '127.0.0.1')
	die('No permission');
if(!isset($_POST['sid']))
	die('No sid');
$sid = $_POST['sid'];
$query = "SELECT * FROM `submission` WHERE `sid`='$sid' LIMIT 0, 1;";
$result = mysql_query($query, $con);
if(!mysql_num_rows($result))
	die('No such submission');
$row = mysql_fetch_array($result);
$pid = $row['pid'];
$query = "SELECT * FROM `problem`, `problem_version` WHERE `problem`.`id`='$pid' AND `problem_version`.`vid`=`problem`.`default_version` LIMIT 0, 1;";
$result = mysql_query($query, $con);
$problem = mysql_fetch_array($result);
$pid = $problem['pid'];
$query = "UPDATE `submission` SET `submission`.`status`=".STATUS_SEND_TO_JUDGE." WHERE `sid`='$sid';";
$result = mysql_query($query, $con);
if(!mysql_affected_rows())
	echo 'Edit status error';
$data = array('sid' => $row['sid'], 'pid' => $pid, 'time_lmt' => $problem['timelimit'], 'mem_lmt' => $problem['memorylimit'], 'code' => $row['code'], 'language' => $row['language']);
$result = sendSocketMsg('localhost', '1234', json_encode($data), true);
if($result == false) {
	$query = "UPDATE `submission` SET `submission`.`status`=".STATUS_SUBMIT_FAILED." WHERE `sid`='$sid';";
	$result = mysql_query($query, $con);
	if(!mysql_affected_rows())
		echo 'Edit status error';
}
else if($result == 'OK') {
	$query = "UPDATE `submission` SET `submission`.`status`=".STATUS_SUBMITTED." WHERE `sid`='$sid';";
	$result = mysql_query($query, $con);
	if(!mysql_affected_rows())
		echo 'Edit status error';
}