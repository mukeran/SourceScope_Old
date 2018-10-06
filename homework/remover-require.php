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

$query = "DELETE FROM `homework_relative` WHERE `uid`='$uid' AND `teacher_id`='$teacher_id';";
echo $query;
mysql_query($query);