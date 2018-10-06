<link href="//cdn.bootcss.com/bootstrap-datetimepicker/4.17.37/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
<script src="//cdn.bootcss.com/moment.js/2.11.2/moment-with-locales.min.js"></script>
<script src="//cdn.bootcss.com/bootstrap-datetimepicker/4.17.37/js/bootstrap-datetimepicker.min.js"></script>
<div class="container-fluid">
	<div class="row">
		<div class="col-sm-12 col-xs-12 col-md-3 col-lg-3">
			<h2>我的学生</h2>
			<div>
				<table class="table table-hover table-striped table-bordered">
					<colgroup>
					<col width="20%"></col>
					<col width="80%"></col>
				</colgroup>
				<thead>
					<tr>
						<td>
						</td>
						<td>
							名称
						</td>
					</tr>
				</thead>
				<tbody id="user-list">
					<?php 
					$uid = $_SESSION['uid'];
					$query = "SELECT `username`, `uid` FROM `user` o WHERE EXISTS(SELECT * FROM `homework_relative` WHERE `teacher_id`='$uid' AND `uid`=o.`uid` AND `status`='1' LIMIT 0,1)";
					$result = mysql_query($query);
					while($row = mysql_fetch_array($result)):
						?>
					<tr>
						<td style="text-align: center;">
							<input type="checkbox" user-id="<?php echo $row['uid']; ?>">
						</td>
						<td style="text-align: center;">
							<a href="javascript:void(0);" id="mystudents"  user-id="<?php echo $row['uid']; ?>" user-name="<?php echo $row['username'];?>"><?php 
								echo $row['username'];
								?></a>
							</td>
						</tr>
					<?php endwhile;?>
				</tbody>
			</table>
		</div>
	</div>
	<div class="col-sm-12 col-xs-12 col-md-9 col-lg-9">
		<div class="row"><h2>新建作业</h2></div>
		<div class="row">
			<div class="col-md-12 col-lg-12 col-sm-12 col-xs-12" style="text-align: center;">
				<a href="javascript:void(0);" id="new-problem" class="btn btn-primary">加入新的题目</a>
				<a href="javascript:void(0);" class="btn btn-danger" id="del-problem" style="margin-left: 5px">删除选中的题目</a>
				<a href="javascript:void(0);" class="btn btn-success" id="handout-homework" style="margin-left: 5px;">发布</a>
			</div>
		</div>
		<div class="row">
			<div class="col-md-8 col-lg-6 col-sm-8 col-xs-12 col-md-offset-2 col-lg-offset-3 col-sm-offset-2">
				<div class="input-group date" id="starttimePicker" style="margin-top: 5px;">
					<span class="input-group-addon" id="starttime-label">结束时间</span>
					<input type="text" class="form-control" placeholder="必填" aria-describedby="starttime-label" id="starttime">
					<span class="input-group-addon">
						<span class="glyphicon glyphicon-calendar"></span>
					</span>
				</div>
			</div>
		</div>
		<div class="row">
			<table class="table table-hover table-striped table-bordered" style="margin-top: 5px;" id="problem-list">
				<colgroup>
					<col width="7%"></col>
					<col width="43%"></col>
					<col width="50%"></col>
				</colgroup>
				<thead>
					<tr>
						<td></td>
						<td style="text-align:center;">
							题目id
						</td>
						<td style="text-align:center;">
							题目名称
						</td>
					</tr>
				</thead>
				<tbody>
				</tbody>
			</table>
		</div>
	</div>
</div>
</div>
<div class="modal fade" id="user-info" tabindex="-1" role="dialog" aria-labelledby="user-title">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="user-title">学生信息</h4>
			</div>
			<div class="modal-body">
				<div class="container-fluid" id="user-content">
					
				</div>
			</div>
			<div class="modal-footer">
				<a type="button" class="btn btn-primary" id="user-more-information">更多信息</a>
				<a type="button" class="btn btn-default" data-dismiss="modal">关闭</a>
			</div>
		</div>
	</div>
</div>
<script>
	$(document).ready(function(){
		$('input[type="checkbox"]').iCheck({
			checkboxClass: 'icheckbox_flat-blue',
			radioClass: 'iradio_flat-blue'
		});
		$('#starttimePicker').datetimepicker({
			format: 'YYYY-MM-DD HH:mm:ss',  
			locale: 'zh-CN'
		});
	});
	$("#new-problem").click(function(){
		$("#problem-list tbody").append("<tr><td style='text-align:center; padding-top:15px;'><input type='checkbox'></td><td style='text-align:center;'><input class='form-control' type='text'></td><td style='text-align:center;'><label style='padding-top:6px;' id='problem-name'>N/A</label></td></tr>");
		$('input[type="checkbox"]').iCheck({
			checkboxClass: 'icheckbox_flat-blue',
			radioClass: 'iradio_flat-blue'
		});
		$('input[type="text"]').keyup(function(){
			var thiss = $(this).parent().next();
			var pid = $(this).val();
			$.get(
				"./getProblemTitle.php?pid="+pid,
				function(result){
					thiss.html(result);
				}
				);
		})
	});
	$("#del-problem").click(function(){
		$("#problem-list .checked").parent().parent().remove();
	});
	$('#mystudents').click(function() {
		var uid = $(this).attr('user-id');
		$('#user-more-information').attr("href", "/account/space.php?uid=" + uid);
		$('#user-content').html('<span>加载中...</span>');
		$('#user-info').modal('show');
		$.get('./simpleinfo.php?username='+$(this).attr('user-name'), function(result) {
			$('#user-content').html(result);
		});
	});
	$("#handout-homework").click(function(){
		var json_problem_id = "";
		var json_user_id = "";
		$("#problem-list input[type='text']").each(function(index){
			if(index == 0)
				json_problem_id = "\""+$(this).val()+"\"";
			else json_problem_id = json_problem_id + ",\""+$(this).val()+"\"";
		});
		json_problem_id = '[' + json_problem_id + ']';
		$("#user-list .checked > input").each(function(index){
			if(index == 0)
				json_user_id = "\""+$(this).attr('user-id')+"\"";
			else json_user_id = json_user_id + ",\""+$(this).attr('user-id')+"\"";
		});
		json_user_id = '[' + json_user_id + ']';
		//alert(json_user_id);
		$.ajax({
			type:"post",
			async:false,
			url:"./giveout.php",
			data:{
				problem_id: json_problem_id,
				user_id: json_user_id,
				end_time: $("#starttime").val()
			},
			success: function(data){
				if(data == "0")
					window.location.href="/homework/index.php";
				else if(data == "Invaild time")
					alert("时间不合法");
				else if(data == "Invaild Problem")
					alert("题目不能为空");
				else if(data == "Invaild user")
					alert("学生不能为空");
				else alert(data);
			}
		});
	});
</script>