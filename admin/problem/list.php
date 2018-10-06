<?php 
$this_page = 'problem';
require_once "../common/admin_header.php";
?>
<?php
$problem_per_page = 10;
$page = 1;
if(isset($_GET['page'])) {
	$page = $_GET['page'];
}

$query = 'SELECT count(*) AS `total` FROM problem';
$result = mysql_query($query, $con);
$row = mysql_fetch_array($result);
$rows_len = $row['total'];
$pages_len = floor(($rows_len - 1) / $problem_per_page + 1);
if(($pages_len != 0 && ($page < 1 || $page > $pages_len)) || ($pages_len == 0 && $page != 1)) {
	header("Location: /admin/problem/list.php");
	exit();
}
$start = ($page - 1) * $problem_per_page;
$query = 'SELECT * FROM problem order by pid asc LIMIT '.$start.', '.$problem_per_page;
$result = mysql_query($query, $con);
?>

<div class="container-fluid">
	<div class="col-md-10 col-md-offset-1">
		<div class="row">
			<div class="col-md-8 hidden-xs hidden-sm">
				<div class="row">
					<ul class="nav nav-pills">
						<li role="presentation" class="<?php if($pages_len == 0 || $page == 1) echo 'disabled';?>">
							<a href="<?php if($pages_len != 0 && $pages_len != 0 && $page != 1) echo '/admin/problem/list.php?page=' . ($page - 1); else echo 'javascript:void(0);';?>">上一页</a>
						</li>
						<?php if($pages_len <= 6): ?>
							<?php for($i = 1; $i <= $pages_len; $i++): ?>
								<li role="presentation" class="<?php if($page == $i) echo 'active'; ?>"><a href="/admin/problem/list.php?page=<?php echo $i; ?>"><?php echo $i;?></a></li>
							<?php endfor; ?>
						<?php else: ?>
							<?php if($page > 2 && $page < $pages_len - 1): ?>
								<?php for($i = 1; $i <= 2; $i++): ?>
									<li role="presentation" class="<?php if($page == $i) echo 'active'; ?>"><a href="/admin/problem/list.php?page=<?php echo $i; ?>"><?php echo $i;?></a></li>
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
								<li role="presentation" class="active"><a href="/admin/problem/list.php?page=<?php echo $page; ?>"><?php echo $page; ?></a></li>
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
								<?php for($i = $pages_len - 1; $i <= $pages_len; $i++): ?>
									<li role="presentation" class="<?php if($page == $i) echo 'active'; ?>"><a href="/admin/problem/list.php?page=<?php echo $i; ?>"><?php echo $i;?></a></li>
								<?php endfor; ?>
							<?php else: ?>
								<?php for($i = 1; $i <= 3; $i++): ?>
									<li role="presentation" class="<?php if($page == $i) echo 'active'; ?>"><a href="/admin/problem/list.php?page=<?php echo $i; ?>"><?php echo $i;?></a></li>
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
								<?php for($i = $pages_len - 2; $i <= $pages_len; $i++): ?>
									<li role="presentation" class="<?php if($page == $i) echo 'active'; ?>"><a href="/admin/problem/list.php?page=<?php echo $i; ?>"><?php echo $i;?></a></li>
								<?php endfor; ?>
							<?php endif; ?>
						<?php endif; ?>
						<li role="presentation" class="<?php if($pages_len == 0 || $page == $pages_len) echo 'disabled';?>">
							<a href="<?php if($pages_len != 0 && $page != $pages_len) echo '/admin/problem/list.php?page=' . ($page + 1); else echo 'javascript:void(0);';?>">下一页</a>
						</li>
					</ul>
				</div>
			</div>
			<div class="col-md-4 col-sm-12 col-xs-12">
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
		<div class="row" style="margin-top: 15px;">
			<table class="table table-striped table-hover table-bordered">
				<colgroup>
					<col width="10%"></col>
					<col width="60%"></col>
					<col width="10%"></col>
					<col width="10%"></col>
					<col width="10%"></col>
				</colgroup>
				<thead>
					<tr>
						<td>ID</td>
						<td>名称</td>
						<td>提交人次</td>
						<td>提交人数</td>
						<td>通过人数</td>
					</tr>
				</thead>
				<tbody>
					<?php while($row = mysql_fetch_array($result)):?>
						<?php
						$default_version = $row['default_version'];
						$query = "SELECT * FROM `problem_version` WHERE `vid`='$default_version' LIMIT 0, 1;";
						$result2 = mysql_query($query, $con);
						$row2 = mysql_fetch_array($result2);
						$title = $row2['title'];
						?>
						<tr>
							<td><?php echo $row['pid'];?></td>
							<td><a href="/admin/problem/edit.php?mode=edit&pid=<?php echo $row['pid'];?>"><?php echo $title;?></a></td>
							<td>0</td>
							<td>0</td>
							<td>0</td>
						</tr>
					<?php endwhile;?>
				</tbody>
			</table>
		</div>
		<div class="row">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="row">
					<ul class="nav nav-pills">
						<li role="presentation" class="<?php if($pages_len == 0 || $page == 1) echo 'disabled';?>">
							<a href="<?php if($pages_len != 0 && $pages_len != 0 && $page != 1) echo '/admin/problem/list.php?page=' . ($page - 1); else echo 'javascript:void(0);';?>">上一页</a>
						</li>
						<?php if($pages_len <= 6): ?>
							<?php for($i = 1; $i <= $pages_len; $i++): ?>
								<li role="presentation" class="<?php if($page == $i) echo 'active'; ?>"><a href="/admin/problem/list.php?page=<?php echo $i; ?>"><?php echo $i;?></a></li>
							<?php endfor; ?>
						<?php else: ?>
							<?php if($page > 2 && $page < $pages_len - 1): ?>
								<?php for($i = 1; $i <= 2; $i++): ?>
									<li role="presentation" class="<?php if($page == $i) echo 'active'; ?>"><a href="/admin/problem/list.php?page=<?php echo $i; ?>"><?php echo $i;?></a></li>
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
								<li role="presentation" class="active"><a href="/admin/problem/list.php?page=<?php echo $page; ?>"><?php echo $page; ?></a></li>
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
								<?php for($i = $pages_len - 1; $i <= $pages_len; $i++): ?>
									<li role="presentation" class="<?php if($page == $i) echo 'active'; ?>"><a href="/admin/problem/list.php?page=<?php echo $i; ?>"><?php echo $i;?></a></li>
								<?php endfor; ?>
							<?php else: ?>
								<?php for($i = 1; $i <= 3; $i++): ?>
									<li role="presentation" class="<?php if($page == $i) echo 'active'; ?>"><a href="/admin/problem/list.php?page=<?php echo $i; ?>"><?php echo $i;?></a></li>
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
								<?php for($i = $pages_len - 2; $i <= $pages_len; $i++): ?>
									<li role="presentation" class="<?php if($page == $i) echo 'active'; ?>"><a href="/admin/problem/list.php?page=<?php echo $i; ?>"><?php echo $i;?></a></li>
								<?php endfor; ?>
							<?php endif; ?>
						<?php endif; ?>
						<li role="presentation" class="<?php if($pages_len == 0 || $page == $pages_len) echo 'disabled';?>">
							<a href="<?php if($pages_len != 0 && $page != $pages_len) echo '/admin/problem/list.php?page=' . ($page + 1); else echo 'javascript:void(0);';?>">下一页</a>
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
		window.location.href = "/admin/problem/list.php?s=" + $("#search-input").val();
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
		window.location.href = "/admin/problem/list.php?page=" + $(".goto-input").val();
	});
</script>
<?php require_once "../common/admin_footer.php"; ?>