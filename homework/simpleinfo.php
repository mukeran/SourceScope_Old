<?php
require_once "../common/database.php";
define("STATUS_CI", 0);
define("STATUS_CE", 1);
define("STATUS_AC", 2);
define("STATUS_UNPASS", 3);
define("STATUS_PENDING", 4);
define("STATUS_WA", 5);
define("STATUS_TLE", 6);
define("STATUS_MLE", 7);
define("STATUS_RE", 8);
define("STATUS_PE", 9);
define("STATUS_SINGLE_RIGHT", 10);
define("STATUS_SINGLE_WRONG", 11);
define("STATUS_SINGLE_TLE", 12);
define("STATUS_SINGLE_MLE", 13);
define("STATUS_SINGLE_RE", 14);
define("STATUS_SINGLE_PE", 15);
define("STATUS_SEND_TO_JUDGE", 16);
define("STATUS_SUBMIT_FAILED", 17);
define("STATUS_SUBMITTED", 18);
define("STATUS_RI", 19);

define("LANGUAGE_C", 0);
define("LANGUAGE_CPP", 1);
$constant = 'constant';
if(!isset($_GET['uid']) || !isset($_GET['rank'])){
	if(!isset($_GET['username']))
		die("No such user");
}else if(isset($_GET['uid']) && !is_numeric($_GET['uid']))
	die('Invalid UID');

$uid = 0;
$rank = 0;
if(isset($_GET['uid']) && isset($_GET['rank'])){
	$uid = $_GET['uid'];
	$rank = $_GET['rank'];
}else{
	$username = $_GET['username'];
	$query = "SELECT * FROM `user` WHERE `username`='$username' LIMIT 0, 1;";
	$res = mysql_query($query);
	if(!mysql_num_rows($res))
		die("No such user.");
	$row = mysql_fetch_array($res);
	$uid = $row['uid'];
	$query = "SELECT count(DISTINCT `pid`) as total FROM `submission` WHERE `author`='$uid' AND `status`='2';";
	$res = mysql_query($query);
	$row = mysql_fetch_array($res);
	$account = $row['total'];
	$query = "SELECT count(*) as total FROM `user` o WHERE (SELECT count(DISTINCT `pid`) FROM `submission` WHERE `author`=o.`uid` AND `status`='2')>'$account';";
	$res = mysql_query($query);
	$row = mysql_fetch_array($res);
	$rank = $row['total'] + 1;
}

$query = "SELECT * FROM `user` WHERE `uid`='$uid' LIMIT 0, 1;";
$result = mysql_query($query, $con);
if(!mysql_num_rows($result))
	die('No such user');
$user = mysql_fetch_array($result);
$information = $user['information'];
$information = json_decode($information, true);
$query = "SELECT DISTINCT `problem`.`pid` FROM `submission` INNER JOIN `problem` WHERE `submission`.`author`='$uid' AND `submission`.`status`='{$constant('STATUS_AC')}' AND `problem`.`id`=`submission`.`pid` ORDER BY `problem`.`pid` ASC;";
$accepted_problem_rs = mysql_query($query, $con);
$cnt_accepted_problem = mysql_num_rows($accepted_problem_rs);
$query = "SELECT DISTINCT `problem`.`pid` FROM `submission` AS o INNER JOIN `problem` WHERE o.`author`='$uid' AND NOT EXISTS(SELECT * FROM `submission` AS i WHERE i.`status`='{$constant('STATUS_AC')}' AND i.`pid`=o.`pid` AND i.`author`=o.`author`) AND (`status`='{$constant('STATUS_UNPASS')}' OR `status`='{$constant('STATUS_WA')}' OR `status`='{$constant('STATUS_PE')}' OR `status`='{$constant('STATUS_MLE')}' OR `status`='{$constant('STATUS_TLE')}' OR `status`='{$constant('STATUS_RE')}' OR `status`='{$constant('STATUS_CE')}') AND `problem`.`id`=o.`pid` ORDER BY `problem`.`pid` ASC;";
$wrong_problem_rs = mysql_query($query, $con);
$cnt_wrong_problem = mysql_num_rows($wrong_problem_rs);
?>
	<div class="col-md-12">
		<div class="row">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title">个人信息<a href="javascript:void(0);" class="pull-right"><span class="glyphicon glyphicon-pencil"></span>编辑</a></h3>
					</div>
					<div class="panel-body">
						<div class="col-md-6">
							<div class="row">
								<div class="container-fluid">
									<p>用户名：<span id="username"><?php echo $user['username']; ?></span></p>
									<p>昵称：<span id="nickname"><?php echo $user['nickname']; ?></span></p>
									<p>邮箱：<span id="email"><?php echo $user['email']; ?></span></p>
									<p>学校：<span id="school"><?php echo $information['school']; ?></span></p>
									<p>个性签名：<span id="sign"><?php echo str_replace("\n", "<br>", $information['sign']); ?></span></p>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<img src="https://www.baidu.com/img/bd_logo1.png" width="100%">
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title">作业完成情况</h3>
					</div>
					<div class="panel-body">
						<div class="progress">
						<?php
							$querym = "SELECT * FROM `user` WHERE `uid`='$uid' LIMIT 0, 1;";
							$resultm = mysql_query($querym, $con);
							$userm = mysql_fetch_array($resultm);
							$vism = array();
							$passratem = "0";
							$homeworksm = array();
							
							if(trim($userm['homework']) == ''){
								$passratem='100%';
								$szm = 0;
							}
							else{
								$homeworksm = json_decode($userm['homework'], true);
								
								$szm = count($homeworksm);
								for($im=0;$im<$szm;$im++)
									$vism[$homeworksm[$im]['pid']] = true;

								$szm = count($vism);

								$querym = "SELECT `pid` FROM `problem` o WHERE `id`=(SELECT DISTINCT `pid` FROM `submission` WHERE `author`='$uid' AND `status`='2' AND `pid`=o.`id`)";
								$resultm = mysql_query($querym);
	
								$countm = 0;
								while($rowm = mysql_fetch_array($resultm)){
									if(isset($vism[$rowm['pid']])){
										$countm++;
										$vism[$rowm['pid']] = false;
									}
								}
								$passratem = floor(($countm / $szm) * 100) . "%";
							}
						?>
							<div class="progress-bar progress-bar-info progress-bar-striped active" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="<?php echo 'width:'.$passratem; ?>">
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title">尚未完成的作业</h3>
					</div>
					<div class="panel-body">
						<?php for($i=0;$i<$szm;$i++): ?>
						<?php if(!$vism[$homeworksm[$i]['pid']]) continue?>
						<a href="/problem/view.php?pid=<?php echo $homeworksm[$i]['pid'];?>"><?php echo $homeworksm[$i]['pid'];?></a>
						&nbsp;
						<?php endfor; ?>
					</div>
				</div>
			</div>
		</div>
	</div>