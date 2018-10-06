<?php
session_start();
require_once "../common/database.php";
require_once "../common/function.php";
function getLastId() {
	global $con;
	$query = "SELECT * FROM settings WHERE name = 'lastProblemId';";
	$result = mysql_query($query, $con);
	if(mysql_num_rows($result) == 0) {
		$query = "INSERT INTO settings (`id`, `name`, `value`) VALUES (NULL, 'lastProblemId', '1000');";
		mysql_query($query, $con);
		return 1000;
	}
	$row = mysql_fetch_array($result);
	return $row['value'];
}
if(!isLogin() || !isAdmin()) {
	die('No permission');
}
if(!isset($_POST['id']) && !isset($_POST['pid']))
	die(1);
$id = $_POST['id'];
$pid = $_POST['pid'];
if(isset($_POST['title']))
	$title = $_POST['title'];
if(isset($_POST['tag']))
	$tag = $_POST['tag'];
if(isset($_POST['timelimit']))
	$timelimit = $_POST['timelimit'];
if(isset($_POST['memorylimit']))
	$memorylimit = $_POST['memorylimit'];
if(isset($_POST['description']))
	$description = $_POST['description'];
if(isset($_POST['input']))
	$input = $_POST['input'];
if(isset($_POST['output']))
	$output = $_POST['output'];
if(isset($_POST['sample']))
	$sample = $_POST['sample'];
if(isset($_POST['hint']))
	$hint = $_POST['hint'];
if($timelimit == '')
	$timelimit = 1000;
if($memorylimit == '')
	$memorylimit = 65536;
$sample = stripslashes($sample);
$description = str_replace("\n", "<br/>", $description);
$input = str_replace("\n", "<br/>", $input);
$output = str_replace("\n", "<br/>", $output);
$hint = str_replace("\n", "<br/>", $hint);
if($id != 'NEW') {
	if(!is_numeric($id))
		die(json_encode(array('status' => 'Invalid ID or PID')));
	$query = "SELECT * FROM `problem` WHERE `id`='$id' AND `pid`='$pid' LIMIT 0, 1;";
	$result = mysql_query($query, $con);
	if(mysql_num_rows($result) == 0)
		die(json_encode(array('status' => 'Invalid ID or PID')));
	$query = "INSERT INTO `problem_version` (`id`, `title`, `tag`, `timelimit`, `memorylimit`, `description`, `input`, `output`, `sample`, `hint`) VALUES ('$id', '$title', '$tag', '$timelimit', '$memorylimit', '$description', '$input', '$output', '$sample', '$hint');";
	$result = mysql_query($query, $con);
	if(!mysql_affected_rows())
		die(json_encode(array('status' => 'failed')));
	$vid = mysql_insert_id($con);
	$query = "UPDATE `problem` SET `default_version`='$vid' WHERE `id`='$id';";
	$result = mysql_query($query, $con);
	if(mysql_affected_rows())
		die(json_encode(array('status' => 'success', 'id' => $id, 'pid' => $pid)));
	else
		die(json_encode(array('status' => 'failed')));
}
else {
	$use_default = false;
	if($pid == '') {
		$pid = getLastId();
		$use_default = true;
	}
	else {
		$query = "SELECT * FROM `problem` WHERE `pid`='$pid' LIMIT 0, 1;";
		$result = mysql_query($query, $con);
		if(mysql_num_rows($result))
			die(json_encode(array('status' => 'Same PID')));
	}
	$query = "INSERT INTO `problem` (`pid`) VALUES ('$pid');";
	$result = mysql_query($query, $con);
	if(!mysql_affected_rows())
		die(json_encode(array('status' => 'failed')));
	$id = mysql_insert_id($con);
	$query = "INSERT INTO `problem_version` (`id`, `title`, `tag`, `timelimit`, `memorylimit`, `description`, `input`, `output`, `sample`, `hint`) VALUES ('$id', '$title', '$tag', '$timelimit', '$memorylimit', '$description', '$input', '$output', '$sample', '$hint');";
	$result = mysql_query($query, $con);
	if(!mysql_affected_rows())
		die(json_encode(array('status' => 'failed')));
	$vid = mysql_insert_id($con);
	$query = "UPDATE `problem` SET `default_version`='$vid' WHERE `id`='$id';";
	$result = mysql_query($query, $con);
	if(!mysql_affected_rows())
		die(json_encode(array('status' => 'failed')));
	if($use_default) {
		$lastId = $pid + 1;
		$query = "UPDATE `settings` SET `value`='$lastId' WHERE `name`='lastProblemId';";
		$result = mysql_query($query, $con);
		if(mysql_affected_rows())
			die(json_encode(array('status' => 'success', 'id' => $id, 'pid' => $pid)));
		else
			die(json_encode(array('status' => 'failed')));
	}
	die(json_encode(array('status' => 'success', 'id' => $id, 'pid' => $pid)));
}
