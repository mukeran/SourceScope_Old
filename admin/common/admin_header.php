<?php
if(!isset($this_page))
	$this_page = 'unknown';
session_start();
require_once "database.php";
require_once "function.php";
if(!isLogin() || !isAdmin()) {
	header("Location: /");
}
?>
<html>
<head>
	<meta charset="utf-8">
	<title>Admin | JudgeOnline</title>
	<link rel="stylesheet" type="text/css" href="/common/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="/common/css/bootstrap-select.min.css">
	<link rel="stylesheet" type="text/css" href="/common/css/icheck_skins/all.css">
	<link href="//cdn.bootcss.com/bootstrap-datetimepicker/4.17.37/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="/common/css/fileinput.min.css">
	<script type="text/javascript" src="/common/js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="/common/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="/common/js/icheck.min.js"></script>
	<script type="text/javascript" src="/common/js/bootstrap-select.min.js"></script>
	<script type="text/javascript" src="/common/js/fileinput.min.js"></script>
	<script type="text/javascript" src="/common/js/fileinput_locale_zh.js"></script>
	<script type="text/javascript">
		!function(a,b){"function"==typeof define&&define.amd?define(["jquery"],function(a){return b(a)}):"object"==typeof exports?module.exports=b(require("jquery")):b(jQuery)}(this,function(){!function(a){a.fn.selectpicker.defaults={noneSelectedText:"未选择",noneResultsText:"没有找到匹配项",countSelectedText:"选中{1}中的{0}项",maxOptionsText:["超出限制 (最多选择{n}项)","组选择超出限制(最多选择{n}组)"],multipleSeparator:", "}}(jQuery)});
	</script>
	<script type="text/javascript" src="/common/js/icheck.min.js"></script>
	<script src="//cdn.bootcss.com/moment.js/2.11.2/moment-with-locales.min.js"></script>
	<script src="//cdn.bootcss.com/bootstrap-datetimepicker/4.17.37/js/bootstrap-datetimepicker.min.js"></script>
</head>
<style type="text/css">
	body {
		font-family: "Microsoft Yahei";
	}
	a:hover, a:link {
		text-decoration: none;
	}
</style>
<body>
	<nav class="navbar navbar-default">
		<div class="container-fluid">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="/admin/">Admin | OnlineJudge</a>
			</div>

			<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
				<ul class="nav navbar-nav">
					<li class="<?php if($this_page == 'index') echo 'active';?>"><a href="/admin/">控制面板</a></li>
					<li class="<?php if($this_page == 'problem') echo 'active';?>"><a href="/admin/problem/list.php">题目</a></li>
					<li class="<?php if($this_page == 'contest') echo 'active';?>"><a href="/admin/contest/list.php">比赛</a></li>
					<li class="<?php if($this_page == 'homework') echo 'active';?>"><a href="/admin/homework/list.php">作业</a></li>
					<li class="<?php if($this_page == 'settings') echo 'active';?>"><a href="/admin/settings/">设置</a></li>
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">快速菜单<span class="caret"></span></a>
						<ul class="dropdown-menu">
							<li><a href="/admin/problem/edit.php?mode=add">添加题目</a></li>
							<li><a href="/admin/contest/edit.php?mode=add">添加比赛</a></li>
							<li><a href="#">添加作业</a></li>
							<li role="separator" class="divider"></li>
							<li><a href="/admin/submission/rejudge.php">重判题目</a></li>
						</ul>
					</li>
				</ul>
				<ul class="nav navbar-nav navbar-right">
					<li><a href="/">&lt;返回前台</a></li>
					<li><a href="javascript:void(0);"><?php echo $_SESSION['username']; ?></a></li>
					<li><a href="/account/logout.php">登出</a></li>
					</ul>
				</div><!-- /.navbar-collapse -->
			</div><!-- /.container-fluid -->
		</nav>