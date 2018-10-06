<?php
define('STATUS_CI', 0);
define('STATUS_CE', 1);
define('STATUS_AC', 2);
define('STATUS_UNPASS', 3);
define('STATUS_PENDING', 4);
define('STATUS_WA', 5);
define('STATUS_TLE', 6);
define('STATUS_MLE', 7);
define('STATUS_RE', 8);
define('STATUS_PE', 9);
define('STATUS_SINGLE_RIGHT', 10);
define('STATUS_SINGLE_WRONG', 11);
define('STATUS_SINGLE_TLE', 12);
define('STATUS_SINGLE_MLE', 13);
define('STATUS_SINGLE_RE', 14);
define('STATUS_SINGLE_PE', 15);
define('STATUS_SEND_TO_JUDGE', 16);
define('STATUS_SUBMIT_FAILED', 17);
define('STATUS_SUBMITTED', 18);
define('STATUS_RI', 19);
define('STATUS_PENDINGREJUDGE', 20);


define('LANGUAGE_C', 0);
define('LANGUAGE_CPP', 1);

require_once('database.php');
function isLogin(){
	global $con;

	if(!isset($_SESSION['logined']) || !isset($_SESSION['uid']) || !isset($_SESSION['ukey'])) {
		$_SESSION['logined'] = null;
		$_SESSION['uid'] = null;
		$_SESSION['ukey'] = null;
		return false;
	}
	$command = "SELECT * FROM `user` WHERE `username`='".$_SESSION['username']."' AND `ukey`='".$_SESSION['ukey']."'";
	if(!mysql_fetch_row(mysql_query($command, $con))){
		@session_destroy();
		return false;
	}
	$newkey = Rand(1, 1000000);
	$command = "UPDATE `user` SET `ukey`='".$newkey."' WHERE `username`='".$_SESSION['username']."' AND `ukey`='".$_SESSION['ukey']."'";
	if(!mysql_query($command, $con)){
		@session_destroy();
		return false;
	}
	$_SESSION['ukey'] = $newkey;
	return true;
}
function isAdmin() {
	global $con;

	$username = $_SESSION['username'];
	$ukey = $_SESSION['ukey'];
	$query = "SELECT * FROM `user` WHERE `username`='$username' AND `ukey`='$ukey';";
	$result = mysql_query($query, $con);
	$row = mysql_fetch_array($result);
	$permissions = explode('|', $row['permission']);
	for($i = 0; $i < count($permissions); $i++)
		if($permissions[$i] == "admin")
			return true;
	return false;
}