<?php
require_once "../common/database.php";

if(!isset($_GET['username']))
	die("0");

$username = $_GET['username'];
$query = "SELECT `uid` FROM `user` WHERE `username`='$username' LIMIT 0, 1;";
$result = mysql_query($query);
$row = mysql_fetch_array($result);

echo $row['uid'];