<?php
$this_page = 'status';
require_once "../common/header.php";
?>
<?php
$submissions_per_page = 20;
if(!isset($_GET['page']))
	$page = 1;
else
	$page = $_GET['page'];
$query = "SELECT count(*) AS `total` FROM `submission`;";
$result = mysql_query($query, $con);
$row = mysql_fetch_array($result);
$submissions = $row['total'];
$pages = floor(($submissions - 1) / $submissions_per_page + 1);

if(!is_numeric($page) || $page < 1 || $page > $pages)
	header("Location: /status/list.php");

$start = ($page - 1) * $submissions_per_page;
$query = "SELECT * FROM `submission` WHERE `cid`='-1' ORDER BY `submission`.`sid` DESC LIMIT $start, $submissions_per_page;";
$result = mysql_query($query, $con);
?>
<div class="modal fade" id="status" tabindex="-1" role="dialog" aria-labelledby="status-title">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="status-title">状态</h4>
			</div>
			<div class="modal-body">
				<div class="container-fluid" id="status-content">
					
				</div>
			</div>
			<div class="modal-footer">
				<a type="button" class="btn btn-primary" id="status-more-information">更多信息</a>
				<a type="button" class="btn btn-default" data-dismiss="modal">关闭</a>
			</div>
		</div>
	</div>
</div>
<div class="container-fluid">
	<div class="col-md-10 col-md-offset-1 col-xs-12">
		<div class="row hidden-sm hidden-xs">
			<div class="col-md-8">
				<div class="row">
					<ul class="nav nav-pills">
						<li role="presentation" class="<?php if($pages == 0 || $page == 1) echo 'disabled';?>">
							<a href="<?php if($pages != 0 && $pages != 0 && $page != 1) echo '/status/list.php?page=' . ($page - 1); else echo 'javascript:void(0);';?>">上一页</a>
						</li>
						<?php if($pages <= 6): ?>
							<?php for($i = 1; $i <= $pages; $i++): ?>
								<li role="presentation" class="<?php if($page == $i) echo 'active'; ?>"><a href="/status/list.php?page=<?php echo $i; ?>"><?php echo $i;?></a></li>
							<?php endfor; ?>
						<?php else: ?>
							<?php if($page > 2 && $page < $pages - 1): ?>
								<?php for($i = 1; $i <= 2; $i++): ?>
									<li role="presentation" class="<?php if($page == $i) echo 'active'; ?>"><a href="/status/list.php?page=<?php echo $i; ?>"><?php echo $i;?></a></li>
								<?php endfor; ?>
								<li role="presentation">
									<a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
										...
									</a>
									<ul class="dropdown-menu" style="margin: 0px; padding: 0px; border: 0px;">
										<li>
											<div class="input-group">
												<input type="text" class="form-control goto-input" placeholder="跳转到">
												<span class="input-group-btn">
													<button class="btn btn-default goto-button" type="button">Go</button>
												</span>
											</div>
										</li>
									</ul>
								</li>
								<li role="presentation" class="active"><a href="/status/list.php?page=<?php echo $page; ?>"><?php echo $page; ?></a></li>
								<li role="presentation">
									<a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
										...
									</a>
									<ul class="dropdown-menu" style="margin: 0px; padding: 0px; border: 0px;">
										<li>
											<div class="input-group">
												<input type="text" class="form-control goto-input" placeholder="跳转到">
												<span class="input-group-btn">
													<button class="btn btn-default goto-button" type="button">Go</button>
												</span>
											</div>
										</li>
									</ul>
								</li>
								<?php for($i = $pages - 1; $i <= $pages; $i++): ?>
									<li role="presentation" class="<?php if($page == $i) echo 'active'; ?>"><a href="/status/list.php?page=<?php echo $i; ?>"><?php echo $i;?></a></li>
								<?php endfor; ?>
							<?php else: ?>
								<?php for($i = 1; $i <= 3; $i++): ?>
									<li role="presentation" class="<?php if($page == $i) echo 'active'; ?>"><a href="/status/list.php?page=<?php echo $i; ?>"><?php echo $i;?></a></li>
								<?php endfor; ?>
								<li role="presentation">
									<a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
										...
									</a>
									<ul class="dropdown-menu" style="margin: 0px; padding: 0px; border: 0px;">
										<li>
											<div class="input-group">
												<input type="text" class="form-control goto-input" placeholder="跳转到">
												<span class="input-group-btn">
													<button class="btn btn-default goto-button" type="button">Go</button>
												</span>
											</div>
										</li>
									</ul>
								</li>
								<?php for($i = $pages - 2; $i <= $pages; $i++): ?>
									<li role="presentation" class="<?php if($page == $i) echo 'active'; ?>"><a href="/status/list.php?page=<?php echo $i; ?>"><?php echo $i;?></a></li>
								<?php endfor; ?>
							<?php endif; ?>
						<?php endif; ?>
						<li role="presentation" class="<?php if($pages == 0 || $page == $pages) echo 'disabled';?>">
							<a href="<?php if($pages != 0 && $page != $pages) echo '/status/list.php?page=' . ($page + 1); else echo 'javascript:void(0);';?>">下一页</a>
						</li>
					</ul>
				</div>
			</div>
			<div class="col-md-4 col-sm-4 col-xs-4">
				<div class="row">
					<button class="btn btn-default pull-right" id="filter-switch"><span class="glyphicon glyphicon-sort"></span> 筛选</button>
				</div>
			</div>
		</div>
		<div class="row" style="margin-top: 10px;">
			<table class="table table-hover table-striped">
				<colgroup>
					<col width="8%"></col>
					<col width="22%"></col>
					<col width="8%"></col>
					<col width="10%"></col>
					<col width="8%"></col>
					<col width="8%"></col>
					<col width="8%"></col>
					<col width="8%"></col>
					<col width="20%"></col>
				</colgroup>
				<thead>
					<tr>
						<td>SID</td>
						<td>
						用户
						<div class="filter" id="filter-user" style="margin-top: 5px; display: block;">
							<input type="text" class="form-control input-sm" id="filter-user-input">
						</div>
						</td>
						<td>
						PID
						<div class="filter" id="filter-pid" style="margin-top: 5px; display: block;">
							<input type="text" class="form-control input-sm" id="filter-pid-input">
						</div>
						</td>
						<td>
						状态
						<div class="filter dropdown" id="filter-status" style="margin-top: 5px; display: block;" role="group">
							<button class="btn btn-default btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								<span id="filter-status-selected" data-filter-status="-1">未选择...</span> <span class="caret"></span>
							</button>
							<ul class="dropdown-menu">
								<li><a href="javascript:void(0);" class="">test</a></li>
							</ul>
							</div>
						</td>
						<td class="hidden-xs">内存(Kb)</td>
						<td class="hidden-xs">时间(ms)</td>
						<td>
						语言
						<div class="filter dropdown" id="filter-language" style="margin-top: 5px; display: block;" role="group">
							<button class="btn btn-default btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								<span id="filter-language-selected" data-filter-status="-1">未选择</span> <span class="caret"></span>
							</button>
							<ul class="dropdown-menu">
								<li><a href="javascript:void(0);" class="">test</a></li>
							</ul>
							</div>
						</td>
						</td>
						<td class="hidden-xs">长度(b)</td>
						<td class="hidden-xs">提交时间</td>
					</tr>
				</thead>
				<tbody>
					<?php while($row = mysql_fetch_array($result)): ?>
						<?php
						$flag = false;
						if($row['author'] == @$_SESSION['uid'] || @isAdmin())
							$flag = true;
						?>
					<tr class="<?php
					switch($row['status']) {
						case STATUS_AC:
							echo 'success';
							break;
						case STATUS_CI: case STATUS_SEND_TO_JUDGE: case STATUS_PENDING: case STATUS_SUBMITTED: case STATUS_SUBMIT_FAILED: case STATUS_RI: case STATUS_PENDINGREJUDGE:
							echo 'warning';
							break;
						default:
							echo 'danger';
							break;
					}
					?>">
						<td><?php echo $row['sid']; ?></td>
						<td><?php
						$uid = $row['author'];
						$query = "SELECT * FROM `user` WHERE `uid`='$uid' LIMIT 0, 1;";
						$user_result = mysql_query($query, $con);
						if(!mysql_num_rows($result))
							echo 'No such user';
						else {
							$user = mysql_fetch_array($user_result);
							echo $user['nickname'];
						}
						?></td>
						<td><?php
						$pid = $row['pid'];
						$query = "SELECT * FROM `problem` WHERE `problem`.`id`='$pid' LIMIT 0, 1;";
						$rs = mysql_query($query, $con);
						$problem = mysql_fetch_array($rs);
						echo "<a href='/problem/view.php?pid=".$problem['pid']."'>".$problem['pid']."</a>";
						?></td>
						<td><?php
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
							case STATUS_PENDINGREJUDGE:
								$status_text = '等待重判';
								break;
						}
						if($flag)
							echo '<a data-sid="'.$row['sid'].'" class="status-information" href="javascript:void(0);">'.$status_text.'</a>';
						else
							echo $status_text;
						?></td>
						<td class="hidden-xs"><?php
						if($row['memory'] == -1)
							echo '-';
						else
							echo $row['memory'];
						?></td>
						<td class="hidden-xs"><?php
						if($row['running_time'] == -1)
							echo '-';
						else
							echo $row['running_time'];
						?></td>
						<td><?php
						if($flag)
							echo '<a href="/status/source.php?sid='.$row['sid'].'">';
						?><?php
						switch ($row['language']) {
							case LANGUAGE_C:
								echo 'C';
								break;
							case LANGUAGE_CPP:
								echo 'C++';
								break;
							default:
								echo 'Unknown';
								break;
						}
						?><?php if($flag) echo '</a>'; ?></td>
						<td class="hidden-xs"><?php echo $row['length']; ?></td>
						<td class="hidden-xs"><?php echo $row['submit_time']; ?></td>
					</tr>
					<?php endwhile; ?>
				</tbody>
			</table>
		</div>
		<div class="row">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="row">
					<ul class="nav nav-pills">
						<li role="presentation" class="<?php if($pages == 0 || $page == 1) echo 'disabled';?>">
							<a href="<?php if($pages != 0 && $pages != 0 && $page != 1) echo '/status/list.php?page=' . ($page - 1); else echo 'javascript:void(0);';?>">上一页</a>
						</li>
						<?php if($pages <= 6): ?>
							<?php for($i = 1; $i <= $pages; $i++): ?>
								<li role="presentation" class="<?php if($page == $i) echo 'active'; ?>"><a href="/status/list.php?page=<?php echo $i; ?>"><?php echo $i;?></a></li>
							<?php endfor; ?>
						<?php else: ?>
							<?php if($page > 2 && $page < $pages - 1): ?>
								<?php for($i = 1; $i <= 2; $i++): ?>
									<li role="presentation" class="<?php if($page == $i) echo 'active'; ?>"><a href="/status/list.php?page=<?php echo $i; ?>"><?php echo $i;?></a></li>
								<?php endfor; ?>
								<li role="presentation">
									<a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
										...
									</a>
									<ul class="dropdown-menu" style="margin: 0px; padding: 0px; border: 0px;">
										<li>
											<div class="input-group">
												<input type="text" class="form-control goto-input" placeholder="跳转到">
												<span class="input-group-btn">
													<button class="btn btn-default goto-button" type="button">Go</button>
												</span>
											</div>
										</li>
									</ul>
								</li>
								<li role="presentation" class="active"><a href="/status/list.php?page=<?php echo $page; ?>"><?php echo $page; ?></a></li>
								<li role="presentation">
									<a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
										...
									</a>
									<ul class="dropdown-menu" style="margin: 0px; padding: 0px; border: 0px;">
										<li>
											<div class="input-group">
												<input type="text" class="form-control goto-input" placeholder="跳转到">
												<span class="input-group-btn">
													<button class="btn btn-default goto-button" type="button">Go</button>
												</span>
											</div>
										</li>
									</ul>
								</li>
								<?php for($i = $pages - 1; $i <= $pages; $i++): ?>
									<li role="presentation" class="<?php if($page == $i) echo 'active'; ?>"><a href="/status/list.php?page=<?php echo $i; ?>"><?php echo $i;?></a></li>
								<?php endfor; ?>
							<?php else: ?>
								<?php for($i = 1; $i <= 3; $i++): ?>
									<li role="presentation" class="<?php if($page == $i) echo 'active'; ?>"><a href="/status/list.php?page=<?php echo $i; ?>"><?php echo $i;?></a></li>
								<?php endfor; ?>
								<li role="presentation">
									<a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
										...
									</a>
									<ul class="dropdown-menu" style="margin: 0px; padding: 0px; border: 0px;">
										<li>
											<div class="input-group">
												<input type="text" class="form-control goto-input" placeholder="跳转到">
												<span class="input-group-btn">
													<button class="btn btn-default goto-button" type="button">Go</button>
												</span>
											</div>
										</li>
									</ul>
								</li>
								<?php for($i = $pages - 2; $i <= $pages; $i++): ?>
									<li role="presentation" class="<?php if($page == $i) echo 'active'; ?>"><a href="/status/list.php?page=<?php echo $i; ?>"><?php echo $i;?></a></li>
								<?php endfor; ?>
							<?php endif; ?>
						<?php endif; ?>
						<li role="presentation" class="<?php if($pages == 0 || $page == $pages) echo 'disabled';?>">
							<a href="<?php if($pages != 0 && $page != $pages) echo '/status/list.php?page=' . ($page + 1); else echo 'javascript:void(0);';?>">下一页</a>
						</li>
					</ul>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	$(".goto-input").keyup(function() {
		function doing(data) {
			alert(data);
		};
		var text = this.value;
		$(".goto-input").each(function(i, data){
			this.value = text;
		});
	});
	$(".goto-input").keypress(function(event) {
		if(event.keyCode == "13")
			$(".goto-button").click();
	});
	$(".goto-button").click(function() {
		window.location.href = "/status/list.php?page=" + $(".goto-input").val();
	});
	$('#filter-switch').click(function() {
		$('.filter').toggle(200);
	});
	$(document).ready(function() {
		$('.filter').hide();
		setTimeout(function() {
			window.location.reload();
		}, 10000);
	});
	$('.status-information').click(function() {
		var sid = $(this).attr('data-sid');
		$('#status-more-information').attr("href", "/status/info.php?sid=" + sid);
		$('#status-content').html('<span>加载中...</span>');
		$('#status').modal('show');
		$.get('/status/simpleinfo.php?sid=' + sid, function(result) {
			$('#status-content').html(result);
		});
	});
</script>
<?php require_once "../common/footer.php"; ?>