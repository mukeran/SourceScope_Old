<?php
// if($_SERVER['REMOTE_ADDR'] != '127.0.0.1')
// 	die('No permission');
$timezone = "PRC";
if(function_exists('date_default_timezone_set')){
	date_default_timezone_set($timezone);
}

session_start();
require_once "function.php";
require_once "database.php";
if(!isset($this_page))
	$this_page = 'unknown';
$isLogin = isLogin();
?>
<html>
<head>
	<meta charset="utf-8">
	<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0;" name="viewport"/>
	<title>SourceScope OJ</title>
	<link rel="stylesheet" href="/common/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="/common/css/bootstrap-select.min.css">
	<link rel="stylesheet" type="text/css" href="/common/css/codemirror.css">
	<script type="text/javascript" src="/common/js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="/common/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="/common/js/icheck.min.js"></script>
	<script type="text/javascript" src="/common/js/bootstrap-select.min.js"></script>
	<script type="text/javascript">
		!function(a,b){"function"==typeof define&&define.amd?define(["jquery"],function(a){return b(a)}):"object"==typeof exports?module.exports=b(require("jquery")):b(jQuery)}(this,function(){!function(a){a.fn.selectpicker.defaults={noneSelectedText:"未选择",noneResultsText:"没有找到匹配项",countSelectedText:"选中{1}中的{0}项",maxOptionsText:["超出限制 (最多选择{n}项)","组选择超出限制(最多选择{n}组)"],multipleSeparator:", "}}(jQuery)});
	</script>
	<script type="text/javascript" src="/common/js/codemirror.js"></script>
	<link rel="stylesheet" type="text/css" href="/common/css/icheck_skins/all.css">
</head>
<style type="text/css">
	body {
		font-family: "Microsoft Yahei";
	}
	label {
		font-weight: lighter;
	}
	span[class="loading"] {
		display: inline-block;
		width: 32px;
		height: 32px;
		background-image: url(/common/img/loading.gif);
		background-repeat: no-repeat;
	}
</style>
<body>
	<nav class="navbar navbar-default">
		<div class="container-fluid">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="/">SourceScope OJ</a>
			</div>

			<div class="collapse navbar-collapse" id="navbar">
				<ul class="nav navbar-nav">
					<li class="<?php if($this_page == 'index') echo 'active'; ?>"><a href="/">主页<span class="sr-only"><?php if($this_page == 'index') echo '(current)'; ?></span></a></li>
					<li class="<?php if($this_page == 'problem') echo 'active'; ?>"><a href="/problem/list.php">问题</a><span class="sr-only"><?php if($this_page == 'index') echo '(current)'; ?></span></li>
					<li class="<?php if($this_page == 'status') echo 'active'; ?>"><a href="/status/list.php">状态</a><span class="sr-only"><?php if($this_page == 'status') echo '(current)'; ?></span></li>
					<li class="<?php if($this_page == 'rank') echo 'active'; ?>"><a href="/rank/list.php">排名</a><span class="sr-only"><?php if($this_page == 'rank') echo '(current)'; ?></span></li>
					<li class="<?php if($this_page == 'contest') echo 'active'; ?>"><a href="/contest/list.php">比赛</a><span class="sr-only"><?php if($this_page == 'contest') echo '(current)'; ?></span></li>
					<li class="<?php if($this_page == 'homework') echo 'active'; ?>"><a href="/homework/list.php">作业</a><span class="sr-only"><?php if($this_page == 'homework') echo '(current)'; ?></span></li>
					<li class="<?php if($this_page == 'help') echo 'active'; ?>"><a href="/help/">帮助</a><span class="sr-only"><?php if($this_page == 'help') echo '(current)'; ?></span></li>
				</ul>
				<ul class="nav navbar-nav navbar-right">
					<?php if(isLogin()): ?>
						<?php if(isAdmin()): ?>
						<li><a href="/admin/">前往后台&gt;</a></li>
						<?php endif; ?>
						<li><a href="/account/space.php?uid=<?php echo $_SESSION['uid']; ?>"><?php echo $_SESSION['username']; ?></a></li>
						<li><a href="/account/logout.php">登出</a></li>
					<?php else: ?>
						<li><a href="/account/register_page.php">注册</a></li>
						<li><a href="/account/login_page.php">登录</a></li>
					<?php endif; ?>
				</ul>
			</div>
		</div>
	</nav>