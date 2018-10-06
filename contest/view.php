<?php require_once '../common/header.php'; ?>
<?php
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
	header('Location: /contest/list.php');
$cid = $_GET['cid'];
$query = "SELECT * FROM `contest` WHERE `cid`='$cid' LIMIT 0, 1;";
$result = mysql_query($query, $con);
if(!mysql_num_rows($result))
	header('Location: /contest/list.php');
$row = mysql_fetch_array($result);

$uid = $_SESSION['uid'];
$person = json_decode($row['person'], true);
$cnt_person = count($person);
$permission = array('visitor');
for($i = 0; $i < $cnt_person; $i++)
	if($person[$i]['uid'] == $uid) {
		$permission = $person[$i]['permission'];
		break;
	}
$starttime = $row['start_time'];
$endtime = $row['end_time'];
$starttime_timestamp = strtotime($starttime);
$endtime_timestamp = strtotime($endtime);
$openness = $row['openness'];

$isManager = false;
if(in_array("manager", $permission))
	$isManager = true;
if(isAdmin()) {
	$isManager = true;
}
$isParticipant = false;
if(in_array("participant", $permission))
	$isParticipant = true;
$isSourceBrowser = false;
if(in_array("sourcebrowser", $permission))
	$isSourceBrowser = true;

$cnt_permission = count($permission);
if($starttime_timestamp > time()) {
	if(!$isManager)
		header('Location: /contest/list.php');
}
if(time() >= $starttime_timestamp) {
	if($openness == 'private') {
		if(!$isParticipant && !$isManager && !$isSourceBrowser)
			header('Location: /contest/list.php');
	}
}

$problems = $row['problem'];
$problems = json_decode($problems, true);
$cnt_problem = count($problems);

if(isset($_GET['pid'])) {
	$pid = $_GET['pid'];
	$isok = true;
	$l = strlen($pid);
	for($i = 0; $i < $l; $i++)
		if(!is_upper($pid[$i]) || ($i != 0 && $pid[$i] != $pid[$i - 1])) {
			$isok = false;
			break;
		}
	if(!$isok)
		header("Location: /contest/view.php?cid=$cid");
}
?>

<style type="text/css">
	a:hover, a:link {
		text-decoration: none;
	}
</style>
<div class="container-fluid">
	<div class="col-md-10 col-md-offset-1">
		<div class="row">
			<h1 class="page-header"><?php echo $row['name']; ?>&nbsp;<small><?php echo $row['cid']; ?></small><small style="font-size: 15px;">&lt;<a href="/contest/list.php">返回比赛列表</a></small></h1>
		</div>
		<div class="row">
			<p id="introduce"><?php echo $row['introduce']; ?></p>
		</div>
		<div class="row">
			<span>状态：</span>
		</div>
		<div class="row" style="margin-top: 20px;">
			<ul class="nav nav-tabs" role="tablist" id="tab">
				<li role="presentation" class="active"><a href="#overview" aria-controls="overview" role="tab" data-toggle="tab">总览</a></li>
				<li role="presentation"><a href="#problem" aria-controls="problem" role="tab" data-toggle="tab">问题</a></li>
				<li role="presentation"><a href="#submit" aria-controls="submit" role="tab" data-toggle="tab">提交</a></li>
				<li role="presentation"><a href="#status" aria-controls="status" role="tab" data-toggle="tab">状态</a></li>
				<li role="presentation"><a href="#rank" aria-controls="rank" role="tab" data-toggle="tab">排名</a></li>
				<li role="presentation"><a href="#discuss" aria-controls="discuss" role="tab" data-toggle="tab">讨论</a></li>
			</ul>
		</div>
		<div class="row">
			<div class="tab-content">
				<div role="tabpanel" class="tab-pane fade in active" id="overview">
					<div class="col-md-10 col-md-offset-1">
						<div class="row" style="margin-top: 30px;">
							<table class="table table-striped table-hover table-bordered">
								<colgroup>
									<col width="10%"></col>
									<col width="50%"></col>
									<col width="10%"></col>
									<col width="10%"></col>
									<col width="10%"></col>
									<col width="10%"></col>
								</colgroup>
								<thead>
									<tr>
										<td>编号</td>
										<td>标题</td>
										<td>通过人数</td>
										<td>尝试人数</td>
										<td>通过人次</td>
										<td>尝试人次</td>
									</tr>
								</thead>
								<tbody>
									<?php
									for($i = 0; $i < $cnt_problem; $i++): ?>
									<tr>
										<td><?php echo getProblemCharID($i + 1); ?></td>
										<td><a href="javascript:void(0);" class="problem-goto" data-problem-id="<?php echo getProblemCharID($i + 1); ?>"><?php echo $problems[$i]['custom_title']; ?></a></td>
										<td>0</td>
										<td>0</td>
										<td>0</td>
										<td>0</td>
									</tr>
									<?php endfor; ?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
				<div role="tabpanel" class="tab-pane fade" id="problem">
					<div class="col-md-10 col-md-offset-1">
						<div class="row pull-middle text-center">
							<nav>
								<ul class="pagination">
									<li class="disabled" id="problem-navi-previous"><a href="javascript:void(0);" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>
									
									<li class="disabled" id="problem-navi-next"><a href="javascript:void(0);" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>
								</ul>
							</nav>
						</div>
						<div class="row" id="problem-content">
							
						</div>
					</div>
				</div>
				<div role="tabpanel" class="tab-pane fade" id="submit" style="margin-top: 10px;">
					<div class="col-md-8 col-xs-8">
						<div class="row">
							<div class="col-md-6">
								<div class="row">
									<div class="col-md-5">
										<div class="row">
											<div class="input-group">
												<span class="input-group-addon" id="submit-pid-label">题目ID</span>
												<input class="form-control" aria-describedby="submit-pid-label" id="submit-pid" value="A">
											</div>
										</div>
									</div>
									<div class="col-md-5 col-md-offset-1">
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
						<textarea id="program"></textarea>
						</div>
					</div>
					<div class="col-md-4 col-xs-4">
						<table class="table table-striped table-hover">
							<thead>
								<tr>
									<td>提交记录</td>
								</tr>
							</thead>
							<tbody>
								<tr><td>test</td></tr>
								<tr><td>test</td></tr>
								<tr><td>test</td></tr>
								<tr><td>test</td></tr>
								<tr><td>test</td></tr>
								<tr><td>test</td></tr>
							</tbody>
						</table>
					</div>
				</div>
				<div role="tabpanel" class="tab-pane fade" id="status" style="margin-top: 10px;">
				<div class="col-md-10 col-md-offset-1">
				<?php
				$submissions_per_page = 20;
				if(!isset($_GET['page']))
					$page = 1;
				else
					$page = $_GET['page'];
				$query = "SELECT count(*) AS `total` FROM `submission` WHERE `cid`='$cid';";
				$result = mysql_query($query, $con);
				$row_rs = mysql_fetch_array($result);
				$submissions = $row_rs['total'];
				$pages = floor(($submissions - 1) / $submissions_per_page + 1);

				$start = ($page - 1) * $submissions_per_page;
				$query = "SELECT * FROM `submission` WHERE `cid`='$cid' ORDER BY `submission`.`sid` DESC LIMIT $start, $submissions_per_page;";
				$result = mysql_query($query, $con);
				?>
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
						<table class="table table-hover table-bordered table-striped">
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
								<td>内存(Kb)</td>
								<td>时间(ms)</td>
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
							<td>长度(b)</td>
							<td>提交时间</td>
						</tr>
					</thead>
					<tbody>
						<?php while($row = mysql_fetch_array($result)): ?>
							<tr class="<?php
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
									echo $user['username'];
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
										switch($row['status']) {
											case STATUS_AC:
											echo '正确';
											break;
											case STATUS_CI:
											echo '编译中';
											break;
											case STATUS_RI:
											echo '运行中';
											break;
											case STATUS_SEND_TO_JUDGE:
											echo '发送中';
											break;
											case STATUS_PENDING:
											echo '等待';
											break;
											case STATUS_SUBMITTED:
											echo '已提交';
											break;
											case STATUS_WA:
											echo '答案错误';
											break;
											case STATUS_TLE:
											echo '时间超限';
											break;
											case STATUS_MLE:
											echo '内存超限';
											break;
											case STATUS_RE:
											echo '运行错误';
											break;
											case STATUS_PE:
											echo '格式错误';
											break;
											case STATUS_UNPASS:
											echo '未通过';
											break;
											case STATUS_SUBMIT_FAILED:
											echo '提交失败';
											break;
											case STATUS_CE:
											echo '编译错误';
											break;
										}
										?></td>
										<td><?php
											if($row['memory'] == -1)
												echo '-';
											else
												echo $row['memory'];
											?></td>
											<td><?php
												if($row['running_time'] == -1)
													echo '-';
												else
													echo $row['running_time'];
												?></td>
												<td><?php
													$flag = false;
													if($row['author'] == @$_SESSION['uid'] || @isAdmin()) {
														$flag = true;
														echo '<a href="/status/source.php?sid='.$row['sid'].'">';
													}
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
													<td><?php echo $row['length']; ?></td>
													<td><?php echo $row['submit_time']; ?></td>
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
				<div role="tabpanel" class="tab-pane fade" id="rank">
					test4
				</div>
				<div role="tabpanel" class="tab-pane fade" id="discuss">
					test5
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript" src="/common/css/codemirror/addon/edit/matchbrackets.js"></script>
<script type="text/javascript" src="/common/css/codemirror/mode/clike/clike.js"></script>
<script type="text/javascript">
	var cnt_problem = <?php echo $cnt_problem; ?>;
	var cid, pid, problem_page;
	var maxpage;
	(function($){
		$.getUrlParam = function(name) {
			var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)");
			var r = window.location.search.substr(1).match(reg);
			if (r != null)
				return unescape(r[2]);
			return null;
		}
	})(jQuery);
	function getProblem(cid, pid) {
		var content = '<div class="row"><h1 class="page-header">加载中 <small>' + pid + '</small></h1></div>';
		$('#problem-content').html(content);
		$.get('/contest/getProblem.php?cid=' + cid + '&pid=' + pid, function(data) {
			$('#problem-content').html(data);
		});
		$('.problem-navi-a').each(function() {
			if($(this).html() == pid) {
				$('.problem-navi').removeClass('active');
				$(this).parent().addClass('active');
			}
		});
	}
	function getProblemCharID(cnt_problem) {
		var t = (cnt_problem - 1) / 26 + 1;
		var id = '';
		for(var i = 1; i <= t; i++)
			id += String.fromCharCode((cnt_problem - 1) % 26 + 'A'.charCodeAt());
		return id;
	}
	function getProblemNumID(str) {
		var l = str.length;
		var order = 26 * (l - 1);
		order += str[0].charCodeAt() - 'A'.charCodeAt() + 1;
		return order;
	}
	function printProblemNavi(page) {
		$('.problem-navi').each(function() {
			$(this).remove();
		});
		var cur = getProblemNumID(pid);
		var start = (page - 1) * 26 + 1;
		var end = Math.min(cnt_problem, page * 26);
		for(var i = start; i <= end; i++) {
			var nowpid = getProblemCharID(i);
			var content = $('<li class="problem-navi"><a href="javascript:void(0);" class="problem-navi-a" data-problem-id="' + nowpid + '">' + nowpid + '</a></li>');
			if(pid == nowpid)
				content = $(content).addClass('active');
			if(i == start)
				$('#problem-navi-previous').after(content);
			else
				$('.problem-navi:last').after(content);
		}
		if(page > 1)
			$('#problem-navi-previous').removeClass('disabled');
		else
			$('#problem-navi-previous').addClass('disabled');
		if(page < maxpage)
			$('#problem-navi-next').removeClass('disabled');
		else
			$('#problem-navi-next').addClass('disabled');
		$('.problem-navi-a').click(function() {
			pid = $(this).attr('data-problem-id');
			problem_page = Math.floor((getProblemNumID(pid) - 1) / 26) + 1;
			printProblemNavi(problem_page);
			getProblem(cid, pid);
		});
		problem_page = page;
	}
	$('#problem-navi-previous').click(function() {
		if(problem_page != 1)
			printProblemNavi(problem_page - 1);
	});
	$('#problem-navi-next').click(function() {
		if(problem_page != maxpage)
			printProblemNavi(problem_page + 1);
	});
	$(document).ready(function() {
		cid = $.getUrlParam('cid');
		pid = $.getUrlParam('pid');
		if(pid == null)
			pid = 'A';
		else
			$('#tab a[href="#problem"]').tab('show');
		getProblem(cid, pid);
		problem_page = Math.floor((getProblemNumID(pid) - 1) / 26) + 1;
		maxpage = Math.floor((cnt_problem - 1) / 26) + 1;
		printProblemNavi(problem_page);
	});
	$('a[data-toggle="tab"]').on('show.bs.tab', function (e) {
		$(document).scrollTop(0);
	});
	$(".problem-goto").click(function() {
		$('#tab a[href="#problem"]').tab('show');
		pid = $(this).attr('data-problem-id');
		problem_page = Math.floor((getProblemNumID(pid) - 1) / 26) + 1;
		printProblemNavi(problem_page);
		getProblem(cid, pid);
	});
	var isSetEditor = false;
	var editor;
	function setEditor() {
		isSetEditor = true;
		editor = CodeMirror.fromTextArea(document.getElementById("program"), {
			width: '100%',
			autofocus: true,
			lineNumbers: true,
			matchBrackets: true,
			mode: "text/x-c++src",
			indentUnit: 4
		});
		var mac = CodeMirror.keyMap.default == CodeMirror.keyMap.macDefault;
		CodeMirror.keyMap.default[(mac ? "Cmd" : "Ctrl") + "-Space"] = "autocomplete";
	}
	$('[aria-controls="submit"]').on("show.bs.tab", function (e) {
		$('#submit-pid').val($('#contest-pid').html());
	});
	$('[aria-controls="submit"]').on('shown.bs.tab', function (e) {
		if(!isSetEditor)
			setEditor();
		editor.focus();
	});
	$('#submit-button').click(function() {
		var submit_pid = $('#submit-pid').val();
		var language = $('#submit-language-select').selectpicker('val');
		var code = editor.getValue();
		$.ajax({
			type: 'POST',
			async: true,
			url: '/submission/submit.php',
			data: {
				pid: submit_pid,
				cid: cid,
				language: language,
				code: code
			},
			success: function() {
				alert('success');
			}
		});
	});
</script>
<?php require_once '../common/footer.php'; ?>