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

$query = "UPDATE `homework_relative` SET `status`='1' WHERE `uid`='$uid' AND `teacher_id`='$teacher_id';";
mysql_query($query);