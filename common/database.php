<?php
$str = "无法建立到数据库的连接，请联系管理员";
function reportErrorandExit($str) {
	print "
	<html>
	<head>
		<meta charset='utf-8'>
		<title>错误</title>
	</head>
	<body>
		<h1>".$str."</h1>
	</body>
	</html>";
	exit();
}
require_once "database_settings.php";
$con = @mysql_connect($db_address, $db_username, $db_password);
if(!$con) {
	reportErrorandExit($str);
}
$db_selected = mysql_select_db($db_name, $con);
if(!$db_selected) {
	reportErrorandExit($str);
}
mysql_query("SET NAMES UTF8", $con);
$query = "CREATE TABLE if not exists `problem` (
id int(10) primary key auto_increment not null, 
pid varchar(20) not null unique,
default_version int(10) not null,
cnt_submission int(10) default 0,
cnt_submission_people int(10) default 0,
cnt_accepted_submission int(10) default 0,
cnt_accepted_people int(10) default 0
);";
$result = mysql_query($query, $con);
$query = "CREATE TABLE if not exists `problem_version` (
vid int(10) primary key auto_increment not null,
id int(10) not null,
version_order int(10) not null,
title text,
tag text,
timelimit int(10) not null default 1000,
memorylimit int(10) not null default 65536,
description longtext,
input longtext,
output longtext,
sample longtext,
hint longtext,
update_time timestamp default CURRENT_TIMESTAMP,
creator int(10) default 0
);";
$result = mysql_query($query, $con);
$query = "CREATE TABLE if not exists `submission` (
sid int(10) primary key auto_increment not null,
author int(10) not null,
pid int(10) not null,
cid int(10) default -1,
language int(10) not null,
code longtext not null,
length int(10) not null,
runinfo longtext,
running_time int(10) default -1,
memory int(10) default -1,
status int(10) not null,
submit_time timestamp default CURRENT_TIMESTAMP,
datainfo longtext
);";
$result = mysql_query($query, $con);
$query = "CREATE TABLE if not exists `contest` (
cid int(10) primary key auto_increment not null,
name text not null,
introduce text,
problem text not null,
contestform text not null,
start_time timestamp,
end_time timestamp,
person text not null,
creator int(10) not null,
openness int(10) not null
);";
$result = mysql_query($query, $con);
/*$query = "CREATE TABLE if not exists homework(
	id int(10) primary key auto_increment not null,
	name text not null,

);";
*/
$query = "CREATE TABLE if not exists `settings` (
id int(10) primary key auto_increment not null,
name text not null,
value text not null
);";
$result = mysql_query($query, $con);
$query = "CREATE TABLE if not exists `user` (
uid int(10) primary key auto_increment not null,
username text not null,
password_hash text not null,
ukey text not null,
register_time timestamp not null,
register_ip text not null,
last_login_ip text not null,
last_login_time date not null,
permission text not null,
permission_group int(10) default 1,
nickname text not null,
email text not null,
information longtext not null,
cnt_submission int(10) default 0,
cnt_accepted_submission int(10) default 0,
cnt_wrong_submission int(10) default 0,
homework longtext not null
);";
$result = mysql_query($query, $con);
$query = "CREATE TABLE if not exists `homework_relative` (
uid int(10) primary key auto_increment not null,
teacher_id int(10) default 0,
status int(10) default 0
);";
$result = mysql_query($query, $con);
$query = "CREATE TABLE if not exists `permission_group` (
id int(10) primary key auto_increment not null,
name text not null,
permission text not null
);";
$result = mysql_query($query, $con);
$query = "CREATE TABLE if not exists `tmp` (
id int(10) primary key auto_increment,
session_id text not null,
type text not null,
content longtext
);";
$result = mysql_query($query, $con);