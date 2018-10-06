<?php
session_start();
require_once("../common/database.php");
function _Hash($pass){
	$ret = 0;
	$sz = strlen($pass);
	for($i = 0; $i < $sz; $i++){
		$ret = $ret * 13131 + $pass[$i];
		$ret = $ret % 131313131;
	}
	return $ret;
}
// 检查传入的必要参数是否存在
if(!isset($_POST["username"]))
	die('No username');
if(!isset($_POST["password"]))
	die('No password');
if(!isset($_POST["vcode"]))
	die('No vcode');
// 检验验证码
$vcode = strtolower($_POST["vcode"]);
if(!isset($_SESSION['vcode']) || $vcode != @$_SESSION['vcode']) {
	unset($_SESSION['vcode']);
	die('Vcode wrong');
}
unset($_SESSION['vcode']);
// 读取内容并检验是否合法
$username = stripcslashes($_POST['username']);
$password = htmlspecialchars(stripcslashes($_POST['password']));
$username_len = strlen($username);
$password_len = strlen($password);
$email = htmlspecialchars(stripcslashes($_POST['email']));
if($username_len < 6)
	die('Username too short');
if($password_len < 6)
	die('Password too short');
if(preg_match("/[^a-zA-Z0-9_]/us", $username))
	die('Special char consists in username');
if(!preg_match("/([a-z0-9]*[-_.]?[a-z0-9]+)*@([a-z0-9]*[-_]?[a-z0-9]+)+[.][a-z]{2,3}([.][a-z]{2})?/i", $email))
	die('Invalid email');
$username = htmlspecialchars($username);
$nickname = htmlspecialchars(stripcslashes($_POST['nickname']));
if($nickname == '')
	$nickname = $username;
$information = array();
$information['school'] = mysql_real_escape_string(htmlspecialchars(stripcslashes($_POST['school'])));
$information['self_page'] = mysql_real_escape_string(htmlspecialchars(stripcslashes($_POST['self_page'])));
$information['sign'] = mysql_real_escape_string(htmlspecialchars(stripcslashes($_POST['sign'])));
$information = mysql_real_escape_string(json_encode($information));
$register_ip = $_SERVER['REMOTE_ADDR'];
// 检验 username 是否重复
$query = "SELECT * FROM `user` WHERE `username`='$username' LIMIT 0, 1;";
$result = mysql_query($query, $con);
if(mysql_num_rows($result))
	die('Repeat username');
$hash_val = _Hash($password);
// 插入
$query = "INSERT INTO `user` (`username`, `password_hash`, `register_ip`, `nickname`, `email`, `information`) VALUES ('$username', '$hash_val', '$register_ip', '$nickname', '$email', '$information');";
$result = mysql_query($query, $con);
if(!mysql_affected_rows())
	die('Database error');
die("success");
