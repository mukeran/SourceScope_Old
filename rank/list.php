<?php
$this_page = 'rank';
require_once "../common/header.php";
?>
<?php
$submissions_per_page = 20;
if(!isset($_GET['page']))
	$page = 1;
else
	$page = $_GET['page'];
$query = "SELECT count(*) AS `total` FROM `user`;";
$result = mysql_query($query, $con);
$row = mysql_fetch_array($result);
$submissions = $row['total'];
$pages = floor(($submissions - 1) / $submissions_per_page + 1);

$start = ($page - 1) * $submissions_per_page;
$query = "SELECT * FROM `user` o ORDER BY (SELECT COUNT(DISTINCT  `pid`) as total FROM  `submission` WHERE  `author`=o.`uid` AND `status`='2') ".(isset($_GET['rev'])?"ASC":"DESC")." LIMIT $start, $submissions_per_page;";
//echo $query;
$result = mysql_query($query, $con);
?>
<div class="modal fade" id="user-info" tabindex="-1" role="dialog" aria-labelledby="user-title">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="user-title">用户信息</h4>
			</div>
			<div class="modal-body">
				<div class="container-fluid" id="user-content">
					
				</div>
			</div>
			<div class="modal-footer">
				<a type="button" class="btn btn-success" id="user-build-relative">老师邀请</a>
				<a type="button" class="btn btn-primary" id="user-more-information">更多信息</a>
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
							<a href="<?php if($pages != 0 && $pages != 0 && $page != 1) echo '/rank/list.php?page=' . ($page - 1); else echo 'javascript:void(0);';?>">上一页</a>
						</li>
						<?php if($pages <= 6): ?>
							<?php for($i = 1; $i <= $pages; $i++): ?>
								<li role="presentation" class="<?php if($page == $i) echo 'active'; ?>"><a href="/rank/list.php?page=<?php echo $i; ?>"><?php echo $i;?></a></li>
							<?php endfor; ?>
						<?php else: ?>
							<?php if($page > 2 && $page < $pages - 1): ?>
								<?php for($i = 1; $i <= 2; $i++): ?>
									<li role="presentation" class="<?php if($page == $i) echo 'active'; ?>"><a href="/rank/list.php?page=<?php echo $i; ?>"><?php echo $i;?></a></li>
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
								<li role="presentation" class="active"><a href="/rank/list.php?page=<?php echo $page; ?>"><?php echo $page; ?></a></li>
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
									<li role="presentation" class="<?php if($page == $i) echo 'active'; ?>"><a href="/rank/list.php?page=<?php echo $i; ?>"><?php echo $i;?></a></li>
								<?php endfor; ?>
							<?php else: ?>
								<?php for($i = 1; $i <= 3; $i++): ?>
									<li role="presentation" class="<?php if($page == $i) echo 'active'; ?>"><a href="/rank/list.php?page=<?php echo $i; ?>"><?php echo $i;?></a></li>
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
									<li role="presentation" class="<?php if($page == $i) echo 'active'; ?>"><a href="/rank/list.php?page=<?php echo $i; ?>"><?php echo $i;?></a></li>
								<?php endfor; ?>
							<?php endif; ?>
						<?php endif; ?>
						<li role="presentation" class="<?php if($pages == 0 || $page == $pages) echo 'disabled';?>">
							<a href="<?php if($pages != 0 && $page != $pages) echo '/rank/list.php?page=' . ($page + 1); else echo 'javascript:void(0);';?>">下一页</a>
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
			<table class="table table-hover table-striped table-bordered">
				<colgroup>
					<col width="10%"></col>
					<col width="70%"></col>
					<col width="10%"></col>
					<col width="10%"></col>
				</colgroup>
				<thead>
					<tr>
						<td style="text-align:center;">
						排名
						<div class="filter dropdown" id="filter-status" style="margin-top: 5px; display: block;" role="group">
							<button class="btn btn-default btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								<span id="filter-status-selected" data-filter-status="-1"><?php if(isset($_GET['rev'])) echo "逆序"; else echo "正序";?></span> <span class="caret"></span>
							</button>
							<ul class="dropdown-menu">
								<li><a href="/rank/list.php" class="">正序</a></li>
								<li><a href="/rank/list.php?rev=1" class="">逆序</a></li>
							</ul>
							</div></td>
						<td style="text-align:center;">
						用户名
						<div class="filter" id="filter-rank" style="margin-top: 5px; display: block;">
							<input type="text" class="form-control input-sm" id="filter-user-input">
						</div>
						</td>
						<td style="text-align:center;">
						正确数量
						</td>
						<td style="text-align:center;">
						正确率
						</td>
						</td>
					</tr>
				</thead>
				<tbody>
					<?php 
					$counter=$start; 
					if(isset($_GET['rev'])){
						$query = "SELECT count(*) as total FROM `user`";
						$all_counter_res = mysql_query($query);
						$all_counter_tmp = mysql_fetch_array($all_counter_res);
						$all_counter = $all_counter_tmp['total'];
						$counter = $all_counter-$start+1;
					}

					?>
					<?php while($row = mysql_fetch_array($result)): ?>
						<tr style="<?php
							$counter += (isset($_GET['rev'])?-1:1);
							if($counter==1)
								echo "background-color: rgba(255,255,0,0.43)";
							else if($counter==2)
								echo "background-color: rgba(0,0,0,0.13)";
							else if($counter==3)
								echo "background-color: rgba(255,147,21,0.5)";
								?>">
							<td style="text-align:center;"><?php echo $counter; ?></td>
							<td class="<?php echo $row['username']?>" style="text-align:center;"><a href="javascript:void(0)" class="user-information" user-uid="<?php echo $row['uid'];?>" user-rank="<?php echo $counter; ?>"><?php 
								echo $row['username'];?>
							</a></td>
							<td style="text-align:center;"><?php
								$uid = $row['uid'];
								$tquery = "SELECT COUNT(DISTINCT  `pid`) as total FROM  `submission` WHERE  `author`='$uid' AND `status`='2' LIMIT 0, 1;";
								//echo $tquery;
								$user_result = mysql_query($tquery, $con);

								$user = mysql_fetch_array($user_result);
								$account = $user['total'];
								echo $account;
							?></td>
							<td style="text-align:center;"><?php
								$uid = $row['uid'];
								$tquery = "SELECT count(*) as total FROM `submission` o WHERE `author`='$uid' AND `status`='2' AND NOT EXISTS(SELECT * FROM `submission` WHERE `submit_time`<o.`submit_time` AND `status`='2' AND `author`='$uid' AND `pid`=o.`pid`);";
								$user_result = mysql_query($tquery, $con);

								$user = mysql_fetch_array($user_result);
								$account = $user['total'];

								$tquery = "SELECT count(*) as total FROM `submission` o WHERE (`status`='2' OR `status`='3' OR `status`='5' OR `status`='6' OR `status`='7' OR `status`='8') AND `author`='$uid' AND NOT EXISTS(SELECT * FROM `submission` WHERE `submit_time`<o.`submit_time` AND `status`='2' AND `author`='$uid' AND `pid`=o.`pid`);";
								$user_result = mysql_query($tquery, $con);

								$user = mysql_fetch_array($user_result);
								$all_count = $user['total'];

								if(!$all_count) echo "0%";
								else echo ceil(($account / $all_count)*100).'%';
							?></td>
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
							<a href="<?php if($pages != 0 && $pages != 0 && $page != 1) echo '/rank/list.php?page=' . ($page - 1); else echo 'javascript:void(0);';?>">上一页</a>
						</li>
						<?php if($pages <= 6): ?>
							<?php for($i = 1; $i <= $pages; $i++): ?>
								<li role="presentation" class="<?php if($page == $i) echo 'active'; ?>"><a href="/rank/list.php?page=<?php echo $i; ?>"><?php echo $i;?></a></li>
							<?php endfor; ?>
						<?php else: ?>
							<?php if($page > 2 && $page < $pages - 1): ?>
								<?php for($i = 1; $i <= 2; $i++): ?>
									<li role="presentation" class="<?php if($page == $i) echo 'active'; ?>"><a href="/rank/list.php?page=<?php echo $i; ?>"><?php echo $i;?></a></li>
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
								<li role="presentation" class="active"><a href="/rank/list.php?page=<?php echo $page; ?>"><?php echo $page; ?></a></li>
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
									<li role="presentation" class="<?php if($page == $i) echo 'active'; ?>"><a href="/rank/list.php?page=<?php echo $i; ?>"><?php echo $i;?></a></li>
								<?php endfor; ?>
							<?php else: ?>
								<?php for($i = 1; $i <= 3; $i++): ?>
									<li role="presentation" class="<?php if($page == $i) echo 'active'; ?>"><a href="/rank/list.php?page=<?php echo $i; ?>"><?php echo $i;?></a></li>
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
									<li role="presentation" class="<?php if($page == $i) echo 'active'; ?>"><a href="/rank/list.php?page=<?php echo $i; ?>"><?php echo $i;?></a></li>
								<?php endfor; ?>
							<?php endif; ?>
						<?php endif; ?>
						<li role="presentation" class="<?php if($pages == 0 || $page == $pages) echo 'disabled';?>">
							<a href="<?php if($pages != 0 && $page != $pages) echo '/rank/list.php?page=' . ($page + 1); else echo 'javascript:void(0);';?>">下一页</a>
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
		window.location.href = "/rank/list.php?page=" + $(".goto-input").val();
	});
	$('#filter-switch').click(function() {
		$('.filter').toggle(200);
	});
	$(document).ready(function() {
		$('.filter').hide();
		$("#filter-user-input").keypress(function(e) {
       	if(e.which == 13) {
       		$.get("/rank/getuserid.php?username="+$("#filter-user-input").val(), function(result){$('#user-more-information').attr("href", "/account/space.php?uid=" + result);
       			$('#user-build-relative').attr("user-id", result);
       		});
       		$('#user-content').html('<span>加载中...</span>');
       		$('#user-info').modal('show');
       		$.get("/rank/simpleinfo.php?username="+$("#filter-user-input").val(), function(result){
       			$('#user-content').html(result);
       		});
       	}  
   	}); 
	});
	$('.user-information').click(function() {
		var uid = $(this).attr('user-uid');
		$('#user-more-information').attr("href", "/account/space.php?uid=" + uid);
		$('#user-build-relative').attr("user-id", uid);
		$('#user-content').html('<span>加载中...</span>');
		$('#user-info').modal('show');
		$.get('/rank/simpleinfo.php?uid=' + uid + "&rank=" + $(this).attr("user-rank"), function(result) {
			$('#user-content').html(result);
		});
	});
	$("#user-build-relative").click(function(){
		var uid = $(this).attr("user-id");
		$.get("/homework/build-relative.php?uid="+uid, function(result){alert(result);});
	});
</script>
<?php require_once "../common/footer.php"; ?>