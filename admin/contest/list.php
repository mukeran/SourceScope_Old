<?php
$this_page = 'contest';
require_once '../common/admin_header.php';
?>

<?php
if(!isset($_GET['page']))
	$page = 1;
else
	$page = $_GET['page'];
$contests_by_page = 10;

$query = "SELECT count(*) AS `total` FROM `contest`";
$result = mysql_query($query, $con);
$row = mysql_fetch_array($result);
$total_contests = $row['total'];

$pages = floor(($total_contests - 1) / $contests_by_page + 1);
$start = ($page - 1) * $contests_by_page;
$query = "SELECT * FROM `contest` ORDER BY `cid` DESC LIMIT $start, $contests_by_page";
$result = mysql_query($query, $con);
?>
<div class="container-fluid">
	<div class="col-md-10 col-md-offset-1">
		<div class="row" style="margin-top: 20px;">
			<div class="col-md-8 hidden-xs hidden-sm">
				<div class="row">
					<ul class="nav nav-pills">
						<li role="presentation" class="<?php if($pages == 0 || $page == 1) echo 'disabled';?>">
							<a href="<?php if($pages != 0 && $pages != 0 && $page != 1) echo '/admin/contest/list.php?page=' . ($page - 1); else echo 'javascript:void(0);';?>">上一页</a>
						</li>
						<?php if($pages <= 6): ?>
							<?php for($i = 1; $i <= $pages; $i++): ?>
								<li role="presentation" class="<?php if($page == $i) echo 'active'; ?>"><a href="/admin/contest/list.php?page=<?php echo $i; ?>"><?php echo $i;?></a></li>
							<?php endfor; ?>
						<?php else: ?>
							<?php if($page > 2 && $page < $pages - 1): ?>
								<?php for($i = 1; $i <= 2; $i++): ?>
									<li role="presentation" class="<?php if($page == $i) echo 'active'; ?>"><a href="/admin/contest/list.php?page=<?php echo $i; ?>"><?php echo $i;?></a></li>
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
								<li role="presentation" class="active"><a href="/admin/contest/list.php?page=<?php echo $page; ?>"><?php echo $page; ?></a></li>
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
									<li role="presentation" class="<?php if($page == $i) echo 'active'; ?>"><a href="/admin/contest/list.php?page=<?php echo $i; ?>"><?php echo $i;?></a></li>
								<?php endfor; ?>
							<?php else: ?>
								<?php for($i = 1; $i <= 3; $i++): ?>
									<li role="presentation" class="<?php if($page == $i) echo 'active'; ?>"><a href="/admin/contest/list.php?page=<?php echo $i; ?>"><?php echo $i;?></a></li>
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
									<li role="presentation" class="<?php if($page == $i) echo 'active'; ?>"><a href="/admin/contest/list.php?page=<?php echo $i; ?>"><?php echo $i;?></a></li>
								<?php endfor; ?>
							<?php endif; ?>
						<?php endif; ?>
						<li role="presentation" class="<?php if($pages == 0 || $page == $pages) echo 'disabled';?>">
							<a href="<?php if($pages != 0 && $page != $pages) echo '/admin/contest/list.php?page=' . ($page + 1); else echo 'javascript:void(0);';?>">下一页</a>
						</li>
					</ul>
				</div>
			</div>
			<div class="col-md-4">
				<div class="row">
					<div class="input-group" style="margin-bottom: 0px;" style="float: right;">
						<input type="text" class="form-control" id="search-input" placeholder="关键词/ID"  style="height: 40px; float: right;">
						<span class="input-group-btn">
							<button class="btn btn-default search-button" type="button" style="height: 40px">搜索</button>
						</span>
					</div>
				</div>
			</div>
		</div>
		<div class="row" style="margin-top: 20px;">
			<table class="table table-striped table-hover table-bordered">
				<colgroup>
					<col width="7%"></col>
					<col width="31%"></col>
					<col width="8%"></col>
					<col width="8%"></col>
					<col width="18%"></col>
					<col width="18%"></col>
					<col width="10%"></col>
				</colgroup>
				<thead>
					<tr>
						<td>CID</td>
						<td>名称</td>
						<td>比赛形式</td>
						<td>状态</td>
						<td>开始时间</td>
						<td>结束时间</td>
						<td>开放性</td>
					</tr>
				</thead>
				<tbody>
					<?php while($row = mysql_fetch_array($result)): ?>
					<?php
					$starttime = $row['start_time'];
					$endtime = $row['end_time'];
					$starttime_timestamp = strtotime($starttime);
					$endtime_timestamp = strtotime($endtime);
					$timezone = "PRC";
					if(function_exists('date_default_timezone_set')){
						date_default_timezone_set($timezone);
					}
					?>
					<tr class="<?php if($starttime_timestamp > time()) echo 'warning'; else if($endtime_timestamp > time()) echo 'success'; else echo 'danger';?>">
						<td><?php echo $row['cid']; ?></td>
						<td><a href="/admin/contest/edit.php?mode=edit&cid=<?php echo $row['cid']; ?>"><?php echo $row['name']; ?></a></td>
						<td><?php echo $row['contestform']; ?></td>
						<td><?php if($starttime_timestamp > time()) echo '未开始'; else if($endtime_timestamp > time()) echo '运行中'; else echo '已结束';?></td>
						<td><?php echo $starttime; ?></td>
						<td><?php echo $endtime; ?></td>
						<td><?php if($row['openness'] == 'public') echo '公开'; else echo '私有'; ?></td>
					</tr>
					<?php endwhile; ?>
				</tbody>
			</table>
		</div>
		<div class="row" style="margin-top: 20px;">
			<div class="col-md-12">
				<div class="row">
					<ul class="nav nav-pills">
						<li role="presentation" class="<?php if($pages == 0 || $page == 1) echo 'disabled';?>">
							<a href="<?php if($pages != 0 && $pages != 0 && $page != 1) echo '/admin/contest/list.php?page=' . ($page - 1); else echo 'javascript:void(0);';?>">上一页</a>
						</li>
						<?php if($pages <= 6): ?>
							<?php for($i = 1; $i <= $pages; $i++): ?>
								<li role="presentation" class="<?php if($page == $i) echo 'active'; ?>"><a href="/admin/contest/list.php?page=<?php echo $i; ?>"><?php echo $i;?></a></li>
							<?php endfor; ?>
						<?php else: ?>
							<?php if($page > 2 && $page < $pages - 1): ?>
								<?php for($i = 1; $i <= 2; $i++): ?>
									<li role="presentation" class="<?php if($page == $i) echo 'active'; ?>"><a href="/admin/contest/list.php?page=<?php echo $i; ?>"><?php echo $i;?></a></li>
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
								<li role="presentation" class="active"><a href="/admin/contest/list.php?page=<?php echo $page; ?>"><?php echo $page; ?></a></li>
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
									<li role="presentation" class="<?php if($page == $i) echo 'active'; ?>"><a href="/admin/contest/list.php?page=<?php echo $i; ?>"><?php echo $i;?></a></li>
								<?php endfor; ?>
							<?php else: ?>
								<?php for($i = 1; $i <= 3; $i++): ?>
									<li role="presentation" class="<?php if($page == $i) echo 'active'; ?>"><a href="/admin/contest/list.php?page=<?php echo $i; ?>"><?php echo $i;?></a></li>
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
									<li role="presentation" class="<?php if($page == $i) echo 'active'; ?>"><a href="/admin/contest/list.php?page=<?php echo $i; ?>"><?php echo $i;?></a></li>
								<?php endfor; ?>
							<?php endif; ?>
						<?php endif; ?>
						<li role="presentation" class="<?php if($pages == 0 || $page == $pages) echo 'disabled';?>">
							<a href="<?php if($pages != 0 && $page != $pages) echo '/admin/contest/list.php?page=' . ($page + 1); else echo 'javascript:void(0);';?>">下一页</a>
						</li>
					</ul>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	$("#search-input").keypress(function() {
		if(event.keyCode == "13")
			$(".search-button").click();
	});
	$(".search-button").click(function() {
		window.location.href = "/admin/contest/list.php?s=" + $("#search-input").val();
	});
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
		window.location.href = "/admin/contest/list.php?page=" + $(".goto-input").val();
	});
</script>
<?php require_once '../common/admin_footer.php'; ?>