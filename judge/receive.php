<?php
require_once '../common/function.php';
require_once '../common/database.php';
$constant = 'constant';
if(!isset($_POST['content']))
	die('error');
$content = stripslashes($_POST['content']);
$content = json_decode($content, true);
$sid = mysql_real_escape_string($content['sid']);
if($content['status'] != STATUS_UNPASS) {
	$query = "UPDATE `submission` SET `submission`.`status`=".$content['status']." WHERE `sid`='$sid';";
	$result = mysql_query($query, $con);
	if(!mysql_affected_rows())
		echo 'Edit status error';
	if($content['status'] == STATUS_AC) {
		$usedtime = 0;
		$usedmemory = 0;
		$cnt_data = count($content['data']);
		$data = $content['data'];
		for($i = 0; $i < $cnt_data; $i++) {
			$usedtime = max($usedtime, $data[$i]['usedtime']);
			$usedmemory = max($usedmemory, $data[$i]['usedmem']);
		}
		$datainfo = json_encode($content['data']);
		$query = "UPDATE `submission` SET `submission`.`running_time`='$usedtime', `submission`.`memory`='$usedmemory', `submission`.`datainfo`='$datainfo' WHERE `sid`='$sid';";
		$result = mysql_query($query, $con);
		if(!mysql_affected_rows())
			echo 'Edit status error';
	}
	$runinfo = mysql_real_escape_string($content['runinfo']);
	$query = "UPDATE `submission` SET `submission`.`runinfo`='$runinfo' WHERE `sid`='$sid';";
	$result = mysql_query($query, $con);
	if(!mysql_affected_rows())
		echo 'Edit status error';
}
else {
	$cnt_data = count($content['data']);
	$usedtime = 0;
	$usedmemory = 0;
	$cnt = array(STATUS_SINGLE_RIGHT => 0, STATUS_SINGLE_WRONG => 0, STATUS_SINGLE_TLE => 0, STATUS_SINGLE_MLE => 0, STATUS_SINGLE_RE => 0, STATUS_SINGLE_PE => 0);
	$data = $content['data'];
	for($i = 0; $i < $cnt_data; $i++) {
		$cnt[$data[$i]['status']]++;
		$usedtime = max($usedtime, $data[$i]['usedtime']);
		$usedmemory = max($usedmemory, $data[$i]['usedmem']);
	}
	$status = STATUS_UNPASS;
	arsort($cnt);
	$first = current($cnt);
	$isSingleStatus = true;
	while(true) {
		$next = next($cnt);
		if($next == false)
			break;
		if($next != 0) {
			$isSingleStatus = false;
			break;
		}
	}
	if($isSingleStatus) {
		reset($cnt);
		$firstkey = key($cnt);
		switch ($firstkey) {
			case STATUS_SINGLE_WRONG:
				$status = STATUS_WA;
				break;
			case STATUS_SINGLE_TLE:
				$status = STATUS_TLE;
				break;
			case STATUS_SINGLE_MLE:
				$status = STATUS_MLE;
				break;
			case STATUS_SINGLE_RE:
				$status = STATUS_RE;
				break;
			case STATUS_SINGLE_PE:
				$status = STATUS_PE;
				break;
		}
	}
	$datainfo = mysql_real_escape_string(json_encode($content['data']));
	$runinfo = mysql_real_escape_string($content['runinfo']);
	$query = "UPDATE `submission` SET `submission`.`status`= '$status', `submission`.`running_time`='$usedtime', `submission`.`memory`='$usedmemory', `submission`.`datainfo`='$datainfo' WHERE `sid`='$sid';";
	$result = mysql_query($query, $con);
	if(!mysql_affected_rows())
		echo 'Edit status error';
	
}
$query = "SELECT * FROM `submission` INNER JOIN `user` WHERE `submission`.`sid`='$sid' AND `user`.`uid`=`submission`.`author` LIMIT 0, 1;";
$result = mysql_query($query, $con);
$submission = mysql_fetch_array($result);
$uid = $submission['uid'];
$query = "UPDATE `user` SET `cnt_wrong_submission`=(SELECT count(*) FROM `submission` WHERE `author`='$uid' AND (`status`='{$constant('STATUS_UNPASS')}' OR `status`='{$constant('STATUS_WA')}' OR `status`='{$constant('STATUS_TLE')}' OR `status`='{$constant('STATUS_MLE')}' OR `status`='{$constant('STATUS_RE')}' OR `status`='{$constant('STATUS_PE')}' OR `status`='{$constant('STATUS_CE')}')) WHERE `uid`='$uid';";
$result = mysql_query($query, $con);
if(!mysql_affected_rows())
	echo 'Edit user wrong error';
$query = "UPDATE `user` SET `cnt_accepted_submission`=(SELECT count(*) FROM `submission` WHERE `author`='$uid' AND `status`='{$constant('STATUS_AC')}') WHERE `uid`='$uid';";
$result = mysql_query($query, $con);
if(!mysql_affected_rows())
	echo 'Edit user accepted error';
$pid = $submission['pid'];
$query = "UPDATE `problem` SET `cnt_accepted_submission`=(SELECT count(*) FROM `submission` WHERE `pid`='$pid' AND `status`='{$constant('STATUS_AC')}') WHERE `id`='$pid';";
$fp = fopen("log", "w");
fwrite($fp, $query);
fclose($fp);
$result = mysql_query($query, $con);
if(!mysql_affected_rows())
	echo 'Edit problem accepted error';
$query = "UPDATE `problem` SET `cnt_accepted_people`=(SELECT count(DISTINCT `author`) FROM `submission` WHERE `pid`='$pid' AND `status`='{$constant('STATUS_AC')}') WHERE `id`='$pid';";
$result = mysql_query($query, $con);
if(!mysql_affected_rows())
	echo 'Edit problem accepted people error';