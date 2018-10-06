<?php
require_once "../common/database.php";
require_once "../common/function.php";
session_start();

$isLogin = isLogin();
if(!$isLogin)
	die("No user");
if(!isset($_GET['uid']))
	die("No user id");

$uid = $_GET['uid'];
$teacher_id = $_SESSION['uid'];

$query = "SELECT * FROM `homework_relative` WHERE `uid`='$uid' AND `teacher_id`='$teacher_id';";
$result = mysql_query($query);

if(mysql_num_rows($result) != 0)
	die("请勿重复添加");

$query = "INSERT INTO `homework_relative` (`uid`, `teacher_id`, `status`) VALUES ('$teacher_id', '$uid', '0')";
mysql_query($query);
die("成功添加");