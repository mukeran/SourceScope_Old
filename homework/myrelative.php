<?php
?>
<div class="container-fluid">
	<div class="row" style="margin-top: 10px;">
		<div class="col-sm-12 col-xs-12 col-md-10 col-md-offset-1 col-lg-10 col-lg-offset-1">
			<div class="col-sm-8"><h1>等待同意列表</h1></div>
			<div class="col-sm-4" style="text-align: right; padding-top: 35px;"><a style="color: black;" href="javascript:void(0);"><span id="open-list" class="glyphicon glyphicon-plus" aria-hidden="true"></span></a></div>
			<script>
				var opened = false;
				$(document).ready(function(){
					$("#waitforaccept").slideUp(0);
				});
				$("#open-list").click(function(){
					if(opened){
						$("#waitforaccept").slideUp(500);
						$("#open-list").removeClass("glyphicon-minus");
						$("#open-list").addClass("glyphicon-plus");
						opened = false;
					}else{
						$("#waitforaccept").slideDown(500);
						$("#open-list").removeClass("glyphicon-plus");
						$("#open-list").addClass("glyphicon-minus");
						opened = true;
					}
				});
			</script>
			<div class="col-md-12 col-xs-12 col-lg-12 col-sm-12">
				<div id="waitforaccept" style="display:block; height: 600px; overflow-x: auto;
	    overflow-y: auto;">
					<table id="unfinished" class="table table-hover table-striped table-bordered">
						<colgroup>
							<col width="60%"></col>
							<col width="20%"></col>
							<col width="20%"></col>
						</colgroup>
						<thead>
							<tr>
								<td style="text-align:center;">学生名称</td>
								<td style="text-align:center;">同意</td>
								<td style="text-align:center;">拒绝</td>
							</tr>
						</thead>
						<tbody>
							<?php $tcount = 0; $uid = $_SESSION['uid']; ?>
							<?php 
							$query = "SELECT * FROM `user` o WHERE EXISTS(SELECT DISTINCT `uid` FROM `homework_relative` WHERE `status`='0' AND `uid`=o.`uid` AND `teacher_id`='$uid');";
							$result = mysql_query($query);
							?>
							<?php while($row = mysql_fetch_array($result)):?>
							<?php $tcount ++; ?>
							<tr>
								<td style="text-align:center;">
									<a href="/account/space.php?uid=<?php echo $row['uid'];?>">
										<?php echo $row['username']; ?>
									</a>
								</td>
								<td style="text-align:center;">
									<a href="javascript:void(0);" class="btn btn-success" id="accept" user-id="<?php echo $row['uid']; ?>">同意</a>
								</td>
								<td style="text-align:center;">
									<a href="javascript:void(0);" class="btn btn-danger" id="refuse" user-id="<?php echo $row['uid']; ?>">拒绝</a>
								</td>
							</tr>
							<?php endwhile; ?>
							<?php $tcount = 13-$tcount; while($tcount-- > 0) echo "<tr><td>&nbsp</td><td></td><td></td></tr>"?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-12 col-xs-12 col-md-10 col-md-offset-1 col-lg-10 col-lg-offset-1">
			<h1>我的学生</h1>
			<div style="display:block; height: 600px; overflow-x: auto;
    overflow-y: auto;">
				<table id="mystudent-list-all" class="table table-hover table-striped table-bordered">
					<colgroup>
						<col width="50%"></col>
						<col width="20%"></col>
						<col width="10%"></col>
						<col width="20%"></col>
					</colgroup>
					<thead>
						<tr>
							<td style="text-align:center;">学生名称</td>
							<td style="text-align:center;">Rank</td>
							<td style="text-align:center;">完成进度</td>
							<td style="text-align:center;">删除学生</td>
						</tr>
					</thead>
					<tbody>
					<?php $tcount = 0; $uid = $_SESSION['uid']; ?>
							<?php 
							$query = "SELECT * FROM `user` o WHERE EXISTS(SELECT DISTINCT `uid` FROM `homework_relative` WHERE `status`='1' AND `uid`=o.`uid` AND `teacher_id`='$uid');";
							$result = mysql_query($query);
							?>
							<?php while($row = mysql_fetch_array($result)):?>
							<?php $tcount ++; ?>
							<tr>
								<td style="text-align:center;">
								<div style="margin-top: 5px;">
									<a href="/account/space.php?uid=<?php echo $row['uid'];?>">
										<?php echo $row['username']; ?>
									</a>
									</div>
								</td>
								<td style="text-align:center;"><div style="margin-top: 5px;">
									<?php
										$uid = $row['uid'];
										$query = "SELECT count(DISTINCT `pid`) as total FROM `submission` WHERE `author`='$uid' AND `status`='2';";
										$res = mysql_query($query);
										$rowss = mysql_fetch_array($res);
										$account = $rowss['total'];
										$query = "SELECT count(*) as total FROM `user` o WHERE (SELECT count(DISTINCT `pid`) FROM `submission` WHERE `author`=o.`uid` AND `status`='2')>'$account';";
										$res = mysql_query($query);
										$rowss = mysql_fetch_array($res);
										$rank = $rowss['total'] + 1;
										echo $rank;
									?></div>
								</td>
								<td style="text-align:center; ">
								<div style="margin-top: 5px">
										<?php
											$querym = "SELECT * FROM `user` WHERE `uid`='$uid' LIMIT 0, 1;";
											$resultm = mysql_query($querym, $con);
											$userm = mysql_fetch_array($resultm);
											$vism = array();
											$passratem = "0";
											$homeworksm = array();
											
											if(trim($userm['homework']) == ''){
												$passratem='100%';
												$szm = 0;
											}
											else{
												$homeworksm = json_decode($userm['homework'], true);
												
												$szm = count($homeworksm);
												for($im=0;$im<$szm;$im++)
													$vism[$homeworksm[$im]['pid']] = true;

												$szm = count($vism);

												$querym = "SELECT `pid` FROM `problem` o WHERE `id`=(SELECT DISTINCT `pid` FROM `submission` WHERE `author`='$uid' AND `status`='2' AND `pid`=o.`id`)";
												$resultm = mysql_query($querym);
					
												$countm = 0;
												while($rowm = mysql_fetch_array($resultm)){
													if(isset($vism[$rowm['pid']])){
														$countm++;
														$vism[$rowm['pid']] = false;
													}
												}
												$passratem = floor(($countm / $szm) * 100) . "%";
											}
											echo $passratem;
										?>
										</div>
								</td>
								<td style="text-align: center;">
									<a href="javascript:void(0);" user-id="<?php echo $row['uid']; ?>" class="btn btn-danger" id="remover">Remove</a>
								</td>
							</tr>
							<?php endwhile; ?>
							<?php $tcount = 13-$tcount; while($tcount-- > 0) echo "<tr><td>&nbsp</td><td></td><td></td><td></td></tr>"?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<script>
$("#refuse").click(function(){
	var uid = $(this).attr("user-id");
	$.get("/homework/remover-require.php?uid="+uid, function(result){});
	$(this).parent().parent().remove();
});
$("#accept").click(function(){
	var uid = $(this).attr("user-id");
	$.get("/homework/accept-require.php?uid="+uid, function(result){});
	$(this).parent().parent().remove();
});
$("#remover").click(function(){
	var uid = $(this).attr("user-id");
	$.get("/homework/remover-require.php?uid="+uid, function(result){});
	$(this).parent().parent().remove();
});
</script>