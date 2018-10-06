<?php
require_once "../common/database.php";
$pid = 1000;
$query = "SELECT * FROM `problem`, `problem_version` WHERE `problem`.`pid`='$pid' AND `problem_version`.`vid`=`problem`.`default_version` LIMIT 0, 1;";
$result = mysql_query($query, $con);
$problem = mysql_fetch_array($result);
var_dump($problem);