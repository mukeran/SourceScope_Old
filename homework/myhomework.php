<?php
function arr_sort($array,$key,$order="asc"){
	$arr_nums=$arr=array();
	foreach($array as $k=>$v)
		$arr_nums[$k]=$v[$key];
	if($order=='asc'){
		asort($arr_nums);
	}else{
		arsort($arr_nums);
	}
	foreach($arr_nums as $k=>$v)
		$arr[$k]=$array[$k];

	return $arr;
}
?>
<div class="container-fluid">
	<div class="row" style="margin-top: 10px;">
		<div class="col-xs-2 col-sm-2 col-md-2 col-lg-2 col-md-offset-1 col-lg-offset-1" style="text-align:right;">
			<font style="font-size: 18px;">当前进度</font>
		</div>
		<div class="col-xs-10 col-sm-10 col-md-8 col-lg-8" style="text-align:left;">
			<div class="col-xs-11"><div class="progress">
				<div class="progress-bar progress-bar-info progress-bar-striped active" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: <?php
					$uid = $_SESSION['uid'];
					$query = "SELECT * FROM `user` WHERE `uid`='$uid' LIMIT 0, 1;";
					$result = mysql_query($query, $con);
					$user = mysql_fetch_array($result);
					
					$vis_teacher = array();
					$vis = array();
					if(trim($user['homework']) == ''){
						$passrate="100%";
						$sz = 0;
					}
					else{
						$homeworks = json_decode($user['homework'], true);

						$sz = count($homeworks);
						arr_sort($homeworks, "pid");
						for($i=0;$i<$sz;$i++){
							$vis[$homeworks[$i]['pid']] = true;
						}
						$sz = count($vis);

						$query = "SELECT `pid` FROM `problem` o WHERE `id`=(SELECT DISTINCT `pid` FROM `submission` WHERE `author`='$uid' AND `status`='2' AND `pid`=o.`id`)";
						$result = mysql_query($query);

						$count = 0;
						while($row = mysql_fetch_array($result)){
							if(isset($vis[$row['pid']])){
								$count++;
								$vis[$row['pid']] = false;
							}
						}

						$passrate = floor(($count / $sz) * 100) . "%";
					}
					echo $passrate;
					$now_time=strtotime (date("y-m-d h:i:s"));
				?>">
				</div>
			</div></div>
			<div class="col-xs-1"><?php echo $passrate;?></div>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-12 col-xs-12 col-md-10 col-md-offset-1 col-lg-10 col-lg-offset-1">
			<h1>未完成的作业</h1>
			<div style="display:block; height: 600px; overflow-x: auto;
    overflow-y: auto;">
				<table id="unfinished" class="table table-hover table-striped table-bordered">
					<colgroup>
						<col width="10%"></col>
						<col width="40%"></col>
						<col width="30%"></col>
						<col width="20%"></col>
					</colgroup>
					<thead>
						<tr>
							<td style="text-align:center;">PID</td>
							<td style="text-align:center;">题目名称</td>
							<td style="text-align:center;">规定时间</td>
							<td style="text-align:center;">教师</td>
						</tr>
					</thead>
					<tbody>
						<?php $tcount=0; for($i=0;$i<$sz;$i++):?>
						<?php if(!$vis[$homeworks[$i]["pid"]]) continue; $tcount++;?>
						<tr class="<?php 
								$zero2=strtotime ($homeworks[$i]["end_time"]);
								if($zero2 < $now_time) echo 'danger';
								else echo 'info';
						?>">
							<td style="text-align:center;">
								<a href="/problem/view.php?pid=<?php echo $homeworks[$i]["pid"];?>"><?php echo $homeworks[$i]["pid"];?></a>
							</td>
							<td style="text-align:center;"><?php
								$pro_id = $homeworks[$i]["pid"];
								$query = "SELECT `title` FROM `problem_version` o WHERE o.`vid`=(SELECT `default_version` FROM `problem` WHERE `pid`='$pro_id') LIMIT 0, 1;";
								$result = mysql_query($query);
								$row = mysql_fetch_array($result);
								echo $row['title'];
							?>
							</td>
							<td style="text-align:center;"><?php
								echo $homeworks[$i]["end_time"];
							?>
							</td>
							<td style="text-align:center;">
								<?php
									if(isset($vis_teacher[$homeworks[$i]["teacher_id"]]))
										echo $vis_teacher[$homeworks[$i]["teacher_id"]];
									else{
										$tid = $homeworks[$i]["teacher_id"];
										$query = "SELECT `username` FROM `user` WHERE `uid`='$tid' LIMIT 0, 1;";
										$result = mysql_query($query);
										$row = mysql_fetch_array($result);
										$vis_teacher[$tid] = $row['username'];
										echo $vis_teacher[$tid];
									}
								?>
							</td>
						</tr>
						<?php endfor;?>
						<?php $tcount=13-$tcount; while($tcount-- > 0):?>
						<tr><td>&nbsp</td><td></td><td></td><td></td></tr>
						<?php endwhile; ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-12 col-xs-12 col-md-10 col-md-offset-1 col-lg-10 col-lg-offset-1">
			<h1>已完成的作业</h1>
			<div style="display:block; height: 600px; overflow-x: auto;
    overflow-y: auto;">
				<table id="finished" class="table table-hover table-striped table-bordered">
					<colgroup>
						<col width="10%"></col>
						<col width="40%"></col>
						<col width="30%"></col>
						<col width="20%"></col>
					</colgroup>
					<thead>
						<tr>
							<td style="text-align:center;">PID</td>
							<td style="text-align:center;">题目名称</td>
							<td style="text-align:center;">规定时间</td>
							<td style="text-align:center;">教师</td>
						</tr>
					</thead>
					<tbody>
						<?php $tcount=0; for($i=0;$i<$sz;$i++):?>
						<?php if($vis[$homeworks[$i]["pid"]]) continue; $tcount++;?>
						<tr class="success">
							<td style="text-align:center;">
								<a href="/problem/view.php?pid=<?php echo $homeworks[$i]["pid"];?>"><?php echo $homeworks[$i]["pid"];?></a>
							</td>
							<td style="text-align:center;"><?php
								$pro_id = $homeworks[$i]["pid"];
								$query = "SELECT `title` FROM `problem_version` o WHERE o.`vid`=(SELECT `default_version` FROM `problem` WHERE `pid`='$pro_id') LIMIT 0, 1;";
								$result = mysql_query($query);
								$row = mysql_fetch_array($result);
								echo $row['title'];
							?>
							</td>
							<td style="text-align:center;"><?php
								echo $homeworks[$i]["end_time"];
							?>
							</td>
							<td style="text-align:center;">
								<?php
									if(isset($vis_teacher[$homeworks[$i]["teacher_id"]]))
										echo $vis_teacher[$homeworks[$i]["teacher_id"]];
									else{
										$tid = $homeworks[$i]["teacher_id"];
										$query = "SELECT `username` FROM `user` WHERE `uid`='$tid' LIMIT 0, 1;";
										$result = mysql_query($query);
										$row = mysql_fetch_array($result);
										$vis_teacher[$tid] = $row['username'];
										echo $vis_teacher[$tid];
									}
								?>
							</td>
						</tr>
						<?php endfor;?>
						<?php $tcount=13-$tcount; while($tcount-- > 0):?>
						<tr><td>&nbsp</td><td></td><td></td><td></td></tr>
						<?php endwhile; ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>