<?php require_once "../common/header.php";?>
<?php
if(!isset($_GET['pid']))
	header("Location: /");
$pid = mysql_real_escape_string($_GET['pid']);
$query = "SELECT * FROM `problem` INNER JOIN `problem_version` WHERE `problem`.`pid`='$pid' AND `problem_version`.`vid`=`problem`.`default_version` LIMIT 0, 1;";
$result = mysql_query($query, $con);
if(!mysql_num_rows($result))
	header("Location: /problem/list.php");
$row = mysql_fetch_array($result);
$database_pid = $row['id'];
?>

<div class="modal fade" id="status-modal" tabindex="-1" role="dialog" aria-labelledby="status-title" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="status-title">状态</h4>
			</div>
			<div class="modal-body">
				<div class="container-fluid" id="status-content">
					
				</div>
			</div>
			<div class="modal-footer">
				<a type="button" class="btn btn-default" data-dismiss="modal">关闭</a>
			</div>
		</div>
	</div>
</div>
<div class="container-fluid">
	<div class="col-md-8 col-md-offset-2">
		<div class="row">
			<h1 class="page-header"><?php echo $row['title']; ?> <small id="pid"><?php echo $pid; ?></small></h1>
		</div>
		<div class="row">
			<span>时间限制：<span class="label label-info"><?php echo $row['timelimit']; ?>&nbsp;ms</span></span>&nbsp;
			<span>内存限制：<span class="label label-info"><?php echo $row['memorylimit']; ?>&nbsp;kb</span></span>
		</div>
		<div class="row" style="margin-top: 10px;">
			<span>提交人次：<span class="label label-warning"><?php echo $row['cnt_submission']; ?></span></span>&nbsp;
			<span>解决人次：<span class="label label-success"><?php echo $row['cnt_accepted_submission']; ?></span></span>&nbsp;
		</div>
		<div class="row" style="margin-top: 10px;">
			<span>提交人数：<span class="label label-warning"><?php echo $row['cnt_submission_people']; ?></span></span>&nbsp;
			<span>解决人数：<span class="label label-success"><?php echo $row['cnt_accepted_people']; ?></span></span>&nbsp;
		</div>
		<div class="row" style="margin-top: 20px;">
			<ul class="nav nav-tabs" role="tablist" id="tab">
				<li role="presentation" class="active"><a href="#content" aria-controls="content" role="tab" data-toggle="tab">题目</a></li>
				<li role="presentation"><a href="#submit" aria-controls="submit" role="tab" data-toggle="tab">提交</a></li>
				<li role="presentation"><a href="#rank" aria-controls="rank" role="tab" data-toggle="tab">排名</a></li>
				<li role="presentation"><a href="#status" aria-controls="status" role="tab" data-toggle="tab">状态</a></li>
				<li role="presentation"><a href="#discuss" aria-controls="discuss" role="tab" data-toggle="tab">讨论</a></li>
				<?php if(isLogin() && isAdmin()):?>
				<li role="presentation" style="float: right;"><a href="/admin/problem/edit.php?mode=edit&pid=<?php echo $pid; ?>">编辑题目</a></li>
				<?php endif; ?>
			</ul>
		</div>
		<div class="row">
			<div class="tab-content">
				<div role="tabpanel" class="tab-pane fade in active" id="content">
					<div class="col-md-10 col-xs-9">
							<div class="description" style="word-break: break-all;">
								<h3 id="description" class="page-header">题目描述<small>&nbsp;Description</small></h3>
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
									<li role="presentation" id="content-nav-description"><a href="#description">题目描述</a></li>
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
				<div role="tabpanel" class="tab-pane fade" id="submit" style="margin-top: 10px;">
					<div class="col-md-8 col-xs-12">
						<div class="row">
							<div class="col-md-6">
								<div class="row">
									<div class="col-md-5">
										<div class="row">
											<div class="input-group">
												<span class="input-group-addon" id="submit-language-label">语言</span>
												<select class="selectpicker form-control" aria-describedby="submit-language-label" id="submit-language-select">
													<option value="<?php echo LANGUAGE_CPP; ?>">C++</option>
													<option value="<?php echo LANGUAGE_C; ?>">C</option>
												</select>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="row pull-right">
									<button class="btn btn-default" id="submit-upload">上传源文件</button>
									<button class="btn btn-primary" id="submit-button">提交</button>
								</div>
							</div>
						</div>
						<div class="row" style="margin-top: 10px;">
							<textarea id="program" style=""></textarea>
						</div>
					</div>
					<div class="col-md-4 hidden-xs">
						<table class="table table-striped table-hover">
							<thead>
								<tr>
									<td>最近提交记录</td>
								</tr>
							</thead>
							<tbody>
								<?php
								$uid = $_SESSION['uid'];
								$query = "SELECT * FROM `submission` WHERE `author`='$uid' AND `pid`='$database_pid' ORDER BY `submit_time` DESC LIMIT 0, 5;";
								$result_submission = mysql_query($query, $con);
								while($submission = mysql_fetch_array($result_submission)):
								?>
									<tr>
										<td>
											<a href="/status/info.php?sid=<?php echo $submission['sid']; ?>" style="text-decoration: none; color: black;">
											<?php
											$status_text = '未知';
											switch($submission['status']) {
												case STATUS_AC:
												$status_text = '正确';
												break;
												case STATUS_CI:
												$status_text = '编译中';
												break;
												case STATUS_RI:
												$status_text = '运行中';
												break;
												case STATUS_SEND_TO_JUDGE:
												$status_text = '发送中';
												break;
												case STATUS_PENDING:
												$status_text = '等待';
												break;
												case STATUS_SUBMITTED:
												$status_text = '已提交';
												break;
												case STATUS_WA:
												$status_text = '答案错误';
												break;
												case STATUS_TLE:
												$status_text = '时间超限';
												break;
												case STATUS_MLE:
												$status_text = '内存超限';
												break;
												case STATUS_RE:
												$status_text = '运行错误';
												break;
												case STATUS_PE:
												$status_text = '格式错误';
												break;
												case STATUS_UNPASS:
												$status_text = '未通过';
												break;
												case STATUS_SUBMIT_FAILED:
												$status_text = '提交失败';
												break;
												case STATUS_CE:
												$status_text = '编译错误';
												break;
											}
											?>
											<button class="btn btn-<?php
											switch($submission['status']) {
												case STATUS_AC:
													echo 'success';
													break;
												case STATUS_CI: case STATUS_SEND_TO_JUDGE: case STATUS_PENDING: case STATUS_SUBMITTED: case STATUS_SUBMIT_FAILED: case STATUS_RI:
													echo 'warning';
													break;
												default:
													echo 'danger';
													break;
											}?>"><?php echo $status_text; ?></button>
											<span><?php echo $submission['submit_time']; ?></span>
											</a>
										</td>
									</tr>
								<?php endwhile; ?>
							</tbody>
						</table>
					</div>
				</div>
				<div role="tabpanel" class="tab-pane fade" id="rank">
					<p>test2</p>
				</div>
				<div role="tabpanel" class="tab-pane fade" id="status">
					<p>test3</p>
				</div>
				<div role="tabpanel" class="tab-pane fade" id="discuss">
					<p>test4</p>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript" src="/common/css/codemirror/addon/edit/matchbrackets.js"></script>
<script type="text/javascript" src="/common/css/codemirror/mode/clike/clike.js"></script>
<script type="text/javascript">
	language = "C++";
	var wid;
	$('#language-selector ul li a').click(function() {
		language = $(this).html();
		$('#language-selector').find('.dropdown-toggle').html(language + ' <span class="caret"></span>');
	});
	function setNormalContentNav() {
		$('#content-nav').css("position", "static");
		$('#content-nav').css("margin-top", "40px");
	}
	function setScrollingContentNav() {
		$('#content-nav').css("position", "fixed");
		$('#content-nav').css("top", "20px");
		$('#content-nav').css("margin-top", "0px");
		$('#content-nav').css("width", wid+"px");
	}
	var isSetEditor = false;
	var editor;
	function setEditor() {
		isSetEditor = true;
		editor = CodeMirror.fromTextArea(document.getElementById("program"), {
			width: '100%',
			autofocus: true,
			lineNumbers: true,
			lineWrapping: true,
			matchBrackets: true,
			mode: "text/x-c++src",
			indentUnit: 4
		});
		var mac = CodeMirror.keyMap.default == CodeMirror.keyMap.macDefault;
		CodeMirror.keyMap.default[(mac ? "Cmd" : "Ctrl") + "-Space"] = "autocomplete";
	}
	$(document).ready(function() {
		setNormalContentNav();
		changeContentNavActive('#content-nav-description');
		wid = $("#content-nav").width();
	});
	$('a[data-toggle="tab"]').on('show.bs.tab', function (e) {
		$(document).scrollTop(0);
	});
	$('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
		setNormalContentNav();
		changeContentNavActive('#content-nav-description');
	});
	$('[aria-controls="submit"]').on('shown.bs.tab', function (e) {
		if(!isSetEditor)
			setEditor();
	});
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
	$('#submit-button').click(function() {
		$('#status-modal .modal-footer').hide();
		$('#status-content').html('提交中...');
		$('#status-modal').modal('show');
		var pid = $('#pid').html();
		var code = editor.getValue();
		var language = $('#submit-language-select').selectpicker('val');
		$.ajax({
			type: 'POST',
			url: '/submission/submit.php',
			async: true,
			data: {
				pid: pid,
				code: code,
				language: language
			},
			success: function(data) {
				if(data == 'Submit error') {
					$('#status-content').html('提交失败！');
					$('#status-modal .modal-footer').show();
				}
				else if(data == 'No such problem') {
					$('#status-content').html('没有此问题！');
					$('#status-modal .modal-footer').show();
				}
				else if(data == 'success') {
					$('#status-content').html('提交成功！');
					window.location.href = "/status/list.php";
				}
			}
		});
	});
	$('#content-nav-submit').click(function() {
		$('#tab a[href="#submit"]').tab('show');
	});
</script>
<?php require_once "../common/footer.php";?>