<?php
require_once("database.php");
function isLogin(){
	global $con;

	if(!isset($_SESSION['logined'])){
		@session_destroy();
		return false;
	}
	if(!isset($_SESSION['username'])){
		@session_destroy();
		return false;
	}
	if(!isset($_SESSION['ukey'])){
		@session_destroy();
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