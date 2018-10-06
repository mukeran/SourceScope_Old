<?php require_once "../common/header.php"; ?>
<?php
$constant = 'constant';
if(!isset($_GET['uid']))
	die('No UID');
if(!is_numeric($_GET['uid']))
	die('Invalid UID');
$uid = $_GET['uid'];
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
<div class="container-fluid">
	<div class="col-md-10 col-md-offset-1">
		<div class="row">
			<div class="col-md-6 col-sm-12 col-xs-12">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title">个人信息<a href="/account/edit_profile.php" class="pull-right"><span class="glyphicon glyphicon-pencil"></span>编辑</a></h3>
					</div>
					<div class="panel-body">
						<div class="col-md-6">
							<div class="row">
								<div class="container-fluid">
									<p>用户名：<span id="username"><?php echo $user['username']; ?></span></p>
									<p>昵称：<span id="nickname"><?php echo $user['nickname']; ?></span></p>
									<p>注册时间：<span id="register-time"><?php echo $user['register_time']; ?></span></p>
									<p>邮箱：<span id="email"><?php echo $user['email']; ?></span></p>
									<p>学校：<span id="school"><?php echo $information['school']; ?></span></p>
									<p>个人主页：<span id="self-page"><?php echo $information['self_page']; ?></span></p>
									<p>个性签名：<span id="sign"><?php echo str_replace("\n", "<br>", $information['sign']); ?></span></p>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<img src="https://www.baidu.com/img/bd_logo1.png" width="100%">
						</div>
					</div>
				</div>
				<div class="panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title">Rating变化</h3>
					</div>
					<div class="panel-body">
						<div class="container-fluid">
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-6 col-sm-12 col-xs-12">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title">题目统计</h3>
					</div>
					<div class="panel-body">
						<div class="row">
							<div class="col-md-6">
								<div class="container-fluid">
									<p>做题排名：<span id="rating-problem"><?php echo 1; ?></span></p>
									<p>提交：<span id="count-submission"><?php echo $user['cnt_submission']; ?></span></p>
									<p>正确题目：<span id="count-problem-accepted"><?php echo $cnt_accepted_problem; ?></span></p>
									<p>正确提交：<span id="count-submission-accepted"><?php echo $user['cnt_accepted_submission']; ?></span></p>
									<p>错误提交：<span id="count-submission-wrong"><?php echo $user['cnt_wrong_submission']; ?></span></p>
									<!-- <p>正确率：<span id="ratio-right"><?php ?></span></p> -->
								</div>
							</div>
							<div class="col-md-6">
								
							</div>
						</div>
					</div>
				</div>
				<div class="panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title">AC的题目(<span><?php echo $cnt_accepted_problem; ?></span>)</h3>
					</div>
					<div class="panel-body">
						<div class="row">
							<div class="container-fluid">
								<?php while($problem = mysql_fetch_array($accepted_problem_rs)): ?>
									<a href="/problem/view.php?pid=<?php echo $problem['pid']; ?>"><?php echo $problem['pid']; ?></a>
								<?php endwhile; ?>
							</div>
						</div>
					</div>
				</div>
				<div class="panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title">提交了但没有AC的题目(<span><?php echo $cnt_wrong_problem; ?></span>)</h3>
					</div>
					<div class="panel-body">
						<div class="row">
							<div class="container-fluid">
								<?php while($problem = mysql_fetch_array($wrong_problem_rs)): ?>
									<a href="/problem/view.php?pid=<?php echo $problem['pid']; ?>"><?php echo $problem['pid']; ?></a>
								<?php endwhile; ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php require_once "../common/footer.php"; ?>