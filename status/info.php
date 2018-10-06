<?php require_once "../common/header.php"; ?>
<?php
$isError = false;
$errorMsg = '';
if(!isset($_GET['sid']))
	header('Location: /status/list.php');
$sid = $_GET['sid'];
$query = "SELECT * FROM `submission`,`problem` WHERE `submission`.`sid`='$sid' AND `problem`.`id`=`submission`.`pid` LIMIT 0, 1;";
$result = mysql_query($query, $con);
if(!mysql_num_rows($result))
	header('Location: /status/list.php');
$row = mysql_fetch_array($result);
if($row['author'] != $_SESSION['uid'] && !isAdmin()) {
	$isError = true;
	$errorMsg = "No permission";
}
$uid = $row['author'];
$query = "SELECT * FROM `user` WHERE `user`.`uid`='$uid' LIMIT 0, 1;";
$result = mysql_query($query, $con);
$user = mysql_fetch_array($result);
?>
<style type="text/css">
	.CodeMirror {
		height: auto;
	}
	.CodeMirror-scroll {
		overflow: auto;
		max-height: 1000px;
	}
</style>
<div class="container-fluid">
	<div class="col-md-10 col-md-offset-1">
		<div class="row">
			<h1 class="page-header"><?php echo '更多信息 '.$row['pid']; ?> <small><?php echo $user['username']; ?></small></h1>
		</div>
		<div class="row">
			<?php if(!$isError): ?>
			<div class="col-md-6">
				<div class="row" style="margin-bottom: 10px;">
					<span style="font-size: 18px;">题目状态：</span>
					<?php
					$status_text = '未知';
					switch($row['status']) {
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
					switch($row['status']) {
						case STATUS_AC:
							echo 'success';
							break;
						case STATUS_CI: case STATUS_SEND_TO_JUDGE: case STATUS_PENDING: case STATUS_SUBMITTED: case STATUS_SUBMIT_FAILED: case STATUS_RI:
							echo 'warning';
							break;
						default:
							echo 'danger';
							break;
					}
					?>"><?php echo $status_text; ?></button>
				</div>
				<div class="row">
					<?php
					$datainformation = json_decode($row['datainfo'], true);
					$cnt_datainformation = count($datainformation);
					if($cnt_datainformation != 0):
					?>
					<table class="table" style="margin-bottom: 0px;">
						<tr>
							<td width="25%" style="border: 0px;">测试点编号</td>
							<td width="25%" style="border: 0px;">状态</td>
							<td width="25%" style="border: 0px;">内存(kB)</td>
							<td width="25%" style="border: 0px;">时间(ms)</td>
						</tr>
					</table>
					<div class="panel-group" id="data-info" role="tablist" aria-multiselectable="true">
					<?php
					for($i = 0; $i < $cnt_datainformation; $i++):
					?>
						<div class="panel panel-default">
							<table style="margin-bottom: 0px;" class="table" data-toggle="collapse" data-parent="#data-info" href="#collapse-<?php echo $i + 1; ?>" aria-controls="collapse-<?php echo $i + 1; ?>" role="tab" id="heading-<?php echo $i + 1; ?>">
								<tr class="<?php
								switch($datainformation[$i]['status']) {
									case STATUS_SINGLE_RIGHT:
									echo 'success';
									break;
									default:
									echo 'danger';
									break;
								}
								?>">
								<td width="25%"><?php echo $i + 1; ?></td>
								<td width="25%"><?php
									switch($datainformation[$i]['status']) {
										case STATUS_SINGLE_RIGHT:
										echo '正确';
										break;
										case STATUS_SINGLE_WRONG:
										echo '答案错误';
										break;
										case STATUS_SINGLE_TLE:
										echo '时间超限';
										break;
										case STATUS_SINGLE_MLE:
										echo '内存超限';
										break;
										case STATUS_SINGLE_RE:
										echo '运行错误';
										break;
										case STATUS_SINGLE_PE:
										echo '格式错误';
										break;
									}
									?></td>
									<td width="25%"><?php echo $datainformation[$i]['usedmem']; ?></td>
									<td width="25%"><?php echo $datainformation[$i]['usedtime']; ?></td>
								</tr>
							</table>
							<div id="collapse-<?php echo $i + 1; ?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading-<?php echo $i + 1; ?>">
								<div class="panel-body">
									<textarea class="form-control" style="resize: none;" rows="10" disabled><?php echo $datainformation[$i]['runinfo']; ?></textarea>
								</div>
							</div>
						</div>
					<?php endfor; ?>
					</div>
					<?php else: ?>
						<?php
						$information = trim($row['runinfo']);
						if($information != ''):
						?>
						<textarea class="form-control" style="resize: none;" rows="10" disabled><?php echo $row['runinfo']; ?></textarea>
						<?php else: ?>
							<?php echo '无详细信息'; ?>
						<?php endif; ?>
					<?php endif; ?>
				</div>
			</div>
			<div class="col-md-6">
				<div class="row">
					<textarea id="source"><?php echo $row['code']; ?></textarea>
				</div>
			</div>
			<?php else: ?>
			<h4><?php echo $errorMsg; ?></h4>
			<?php endif; ?>
		</div>
	</div>
</div>
<script type="text/javascript" src="/common/css/codemirror/addon/edit/matchbrackets.js"></script>
<script type="text/javascript" src="/common/css/codemirror/mode/clike/clike.js"></script>
<script type="text/javascript">
	$(document).ready(function() {
		var cppEditor = CodeMirror.fromTextArea(document.getElementById("source"), {
			lineNumbers: true,
			lineWrapping: true,
			matchBrackets: true,
			mode: "text/x-c++src",
			readOnly: true
		});
		var mac = CodeMirror.keyMap.default == CodeMirror.keyMap.macDefault;
		CodeMirror.keyMap.default[(mac ? "Cmd" : "Ctrl") + "-Space"] = "autocomplete";
	});
</script>
<?php require_once "../common/footer.php"; ?>