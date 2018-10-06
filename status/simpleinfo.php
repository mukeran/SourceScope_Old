<?php
session_start();
require_once "../common/function.php";
require_once "../common/database.php";
$isAdmin = isAdmin();
if(!isLogin())
	die('No Login');
if(!isset($_GET['sid']))
	die('No SID');
$sid = $_GET['sid'];
if(!is_numeric($sid))
	die('Invalid SID');
$query = "SELECT * FROM `submission` WHERE `submission`.`sid`='$sid' LIMIT 0, 1;";
$result = mysql_query($query, $con);
if(!mysql_num_rows($result))
	die('No such submission');
$submission = mysql_fetch_array($result);
if($_SESSION['uid'] != $submission['author'] && !$isAdmin)
	die('No permission');
$information = $submission['runinfo'];
$information = trim($information);
$datainformation = json_decode($submission['datainfo'], true);
$cnt_datainformation = count($datainformation);
?>
<div class="col-md-10 col-md-offset-1">
	<?php if($cnt_datainformation == 0 && $information == ''): ?>
		<span>无详细信息</span>
	<?php else: ?>
	<?php if($information != ''): ?>
		<textarea class="form-control" rows="20" style="resize: none;" disabled><?php echo $information; ?></textarea>
	<?php else: ?>
	<table class="table table-hover table-striped">
		<colgroup>
			<col width="25%"></col>
			<col width="25%"></col>
			<col width="25%"></col>
			<col width="25%"></col>
		</colgroup>
		<thead>
			<tr>
				<td>测试点</td>
				<td>状态</td>
				<td>内存(kB)</td>
				<td>时间(ms)</td>
			</tr>
		</thead>
		<tbody>
			<?php for($i = 0; $i < $cnt_datainformation; $i++): ?>
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
					<td><?php echo $i + 1; ?></td>
					<td><?php
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
					<td><?php echo $datainformation[$i]['usedmem']; ?></td>
					<td><?php echo $datainformation[$i]['usedtime']; ?></td>
				</tr>
			<?php endfor; ?>
		</tbody>
	</table>
	<?php endif; ?>
	<?php endif; ?>
</div>