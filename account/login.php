<?php
session_start();
require_once("../common/database.php");
function _Hash($pass){
	$ret = 0;
	$sz = strlen($pass);
	for($i=0;$i<$sz;$i++){
		$ret = $ret*13131+$pass[$i];
		$ret = $ret % 131313131;
	}
	return $ret;
}
function Login($username, $password){
	if(isset($_SESSION['logined'])) return 3;
	global $con;
	$hash_val=_Hash($password);
	$command = "SELECT * FROM `user` WHERE `username`='".$username."' AND `password_hash`='".$hash_val."'";
	//echo $command;
	$result = mysql_query($command, $con);
	if(!mysql_num_rows($result)) return 1;
	$row = mysql_fetch_array($result);
	$uid = $row['uid'];
	$newukey = Rand(1, 100000000);
	$command = "UPDATE `user` SET `ukey`='".$newukey."' WHERE `username`='".$username."' AND `password_hash`='".$hash_val."'";
	if(!mysql_query($command, $con)) return 2;
	$_SESSION['logined'] = '1';
	$_SESSION['username'] = $username;
	$_SESSION['ukey'] = $newukey;
	$_SESSION['uid'] = $uid;
	return 0;
}
if(!isset($_POST["vcode"]))
	die('No vcode');
// 检验验证码
$vcode = strtolower($_POST["vcode"]);
if(!isset($_SESSION['vcode']) || $vcode != @$_SESSION['vcode']) {
	unset($_SESSION['vcode']);
	die('Vcode wrong');
}
unset($_SESSION['vcode']);

if(!isset($_POST["username"]) && !isset($_POST["password"]))
	die("No username and password");
else if(!isset($_POST["password"]))
	die("No password");
else if(!isset($_POST["username"]))
	die("No username");
else if(!Login($_POST["username"], $_POST["password"]))
	die("success");
die("Username or password not match");