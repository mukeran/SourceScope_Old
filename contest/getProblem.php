<?php
header("Content-type: text/html; charset=utf-8");
require_once '../common/database.php';
require_once '../common/function.php';
function getProblemCharID($cnt_problem) {
	$t = ($cnt_problem - 1) / 26 + 1;
	$id = '';
	for($i = 1; $i <= $t; $i++)
		$id .= chr(($cnt_problem - 1) % 26 + ord('A'));
	return $id;
}
function getProblemNumID($str) {
	$l = strlen($str);
	$order = 26 * ($l - 1);
	$order += ord($str[0]) - ord('A') + 1;
	return $order;
}
function is_upper($c) {
	$ascii = ord($c);
	if($ascii >= ord('A') && $ascii <= ord('Z'))
		return true;
	return false;
}
if(!isset($_GET['cid']))
	die('No CID');
if(!isset($_GET['pid']))
	die('No PID');
$cid = $_GET['cid'];
$query = "SELECT * FROM `contest` WHERE `cid`='$cid' LIMIT 0, 1;";
$result = mysql_query($query, $con);
if(!mysql_num_rows($result))
	die('Invalid CID');
$row = mysql_fetch_array($result);
mysql_free_result($result);
$problems = $row['problem'];
$problems = json_decode($problems, true);
$cnt_problem = count($problems);
$pid = $_GET['pid'];
$isok = true;
$l = strlen($pid);
for($i = 0; $i < $l; $i++)
	if(!is_upper($pid[$i]) || ($i != 0 && $pid[$i] != $pid[$i - 1])) {
		$isok = false;
		break;
	}
if(!$isok)
	die('Invalid PID');
$order = getProblemNumID($pid);
if($order > $cnt_problem)
	die('Invalid PID');
$problem = $problems[$order - 1];
$pid = $problem['pid'];
$query = "SELECT * FROM `problem` WHERE `problem`.`pid`='$pid' LIMIT 0, 1;";
$result = mysql_query($query, $con);
if(!mysql_num_rows($result))
	die('No such problem');
$row = mysql_fetch_array($result);
mysql_free_result($result);
$default_version = $row['default_version'];
$query = "SELECT * FROM `problem_version` WHERE `problem_version`.`vid`='$default_version' LIMIT 0, 1;";
$result = mysql_query($query, $con);
if(!mysql_num_rows($result))
	die('Problem error');
$row = mysql_fetch_array($result);
mysql_free_result($result);
?>

<div class="row">
	<h1 class="page-header"><?php echo $problem['custom_title']; ?> <small id="contest-pid"><?php echo getProblemCharID($order); ?></small></h1>
</div>
<div class="row">
	<span>时间限制：<span class="label label-default"><?php echo $row['timelimit']; ?>&nbsp;ms</span></span>&nbsp;
	<span>内存限制：<span class="label label-default"><?php echo $row['memorylimit']; ?>&nbsp;kb</span></span>
</div>
<div class="row">
	<div class="col-md-10 col-xs-9">
		<div class="description" style="word-break: break-all;">
			<h3 id="description" class="page-header">描述<small>&nbsp;Description</small></h3>
			<?php echo $row['description']; ?>
		</div>
		<div class="input" style="word-break: break-all;">
			<h3 id="input" class="page-header">输入<small>&nbsp;Input</small></h3>
			<?php echo $row['input']; ?>
		</div>
		<div class="output" style="word-break: break-all;">
			<h3 id="output" class="page-header">输出<small>&nbsp;Output</small></h3>
			<?php echo $row['output']; ?>
		</div>
		<div class="sample">
			<h3 id="sample" class="page-header">样例<small>&nbsp;Sample</small></h3>
			<?php
			$samples = json_decode($row['sample'], true);
			$size = count($samples);
			for($i = 0; $i < $size; $i++):?>
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title">样例 <?php echo $i + 1;?></h3>
				</div>
				<div class="panel-body">
					<div class="container-fluid">
						<div class="row">
							<div class="col-md-6">
								<span class="label label-default" style="display: inline-block; font-size: 12px;">输入</span>
								<textarea class="form-control" wrap="off" style="resize: none; overflow: auto; margin-top: 10px;" rows="5" disabled><?php echo str_replace("<br/>", "\n", $samples[$i]['sample-input']); ?></textarea>
							</div>
							<div class="col-md-6">
								<span class="label label-default" style="display: inline-block; font-size: 12px;">输出</span>
								<textarea class="form-control" wrap="off" style="resize: none; overflow: auto; margin-top: 10px;" rows="5" disabled><?php echo str_replace("<br/>", "\n", $samples[$i]['sample-output']); ?></textarea>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php endfor; ?>
		</div>
		<div class="hint" style="word-break: break-all;">
			<h3 id="hint" class="page-header">提示<small>&nbsp;Hint</small></h3>
			<?php echo $row['hint']; ?>
		</div>
	</div>
	<div class="col-md-2 col-xs-3">
		<div id="content-nav">
			<ul class="nav nav-pills nav-stacked">
				<li role="presentation" id="content-nav-description"><a href="#description">描述</a></li>
				<li role="presentation" id="content-nav-input"><a href="#input">输入</a></li>
				<li role="presentation" id="content-nav-output"><a href="#output">输出</a></li>
				<li role="presentation" id="content-nav-sample"><a href="#sample">样例</a></li>
				<li role="presentation" id="content-nav-hint"><a href="#hint">提示</a></li>
				<li role="presentation" id="content-nav-totop"><a href="javascript:void(0);">返回顶部</a></li>
				<li role="presentation" id="content-nav-submit" style="margin-top: 10px;"><a href="javascript:void(0);">提交</a></li>
			</ul>
		</div>
	</div>
</div>
<script type="text/javascript">
	var wid;
	function setNormalContentNav() {
		$('#content-nav').css("position", "static");
		$('#content-nav').css("margin-top", "40px");
	}
	function setScrollingContentNav() {
		$('#content-nav').css("position", "fixed");
		$('#content-nav').css("top", "20px");
		$('#content-nav').css("margin-top", "0px");
		$('#content-nav').css("width", wid + "px");
	}
	function changeContentNavActive(id) {
		$('#content-nav').find('li').removeClass('active');
		$(id).addClass('active');
	}
	function doScroll() {
		var offset = 5;
		var height = $(document).scrollTop();
		var description = $('#description').offset().top - offset;
		var input = $('#input').offset().top - offset;
		var output = $('#output').offset().top - offset;
		var sample = $('#sample').offset().top - offset;
		var hint = $('#hint').offset().top - offset;
		if(height >= description)
			setScrollingContentNav();
		else
			setNormalContentNav();
		if(height < input)
			changeContentNavActive('#content-nav-description');
		else if(height >= input && height < output)
			changeContentNavActive('#content-nav-input');
		else if(height >= output && height < sample)
			changeContentNavActive('#content-nav-output');
		else if(height >= sample && height < hint)
			changeContentNavActive('#content-nav-sample');
		else if(height >= hint)
			changeContentNavActive('#content-nav-hint');
	};
	$(document).scroll(doScroll);
	$('#content-nav-totop a').click(function() {
		$(document).scrollTop(0);
		doScroll();
		$(this).blur();
	});
	$('a[aria-controls="problem"]').on('shown.bs.tab', function (e) {
		setNormalContentNav();
		changeContentNavActive('#content-nav-description');
	});
	$(document).ready(function() {
		setNormalContentNav();
		changeContentNavActive('#content-nav-description');
		wid = $("#content-nav").width();
	});
	$('#content-nav-submit').click(function() {
		$('#submit-pid').val("<?php echo getProblemCharID($order); ?>")
		$('#tab a[href="#submit"]').tab('show');
	});
</script>

