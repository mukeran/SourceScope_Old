<?php require_once '../common/admin_header.php'; ?>
<?php
function getProblemCharID($cnt_problem) {
	$t = ($cnt_problem - 1) / 26 + 1;
	$id = '';
	for($i = 1; $i <= $t; $i++)
		$id .= chr(($cnt_problem - 1) % 26 + ord('A'));
		return $id;
}
if(!isset($_GET['mode']))
	header("Location: /admin/contest/edit.php?mode=add");
$mode = $_GET['mode'];
if($mode != 'add' && $mode != 'edit')
	header("Location: /admin/contest/edit.php?mode=add");
if($mode == 'edit') {
	if(!isset($_GET['cid']))
		header("Location: /admin/contest/edit.php?mode=add");
	$cid = $_GET['cid'];
	$query = "SELECT * FROM `contest` WHERE `contest`.`cid`='$cid' LIMIT 0, 1;";
	$result = mysql_query($query, $con);
	if(!mysql_num_rows($result))
		header("Location: /admin/contest/edit.php?mode=add");
	$row = mysql_fetch_array($result);
}
?>
<div class="modal fade" id="status" tabindex="-1" role="dialog" aria-labelledby="status-title" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="status-title">状态</h4>
			</div>
			<div class="modal-body">
				<div class="container-fluid">
					<div class="row">
						<span id="status-content"></span>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<a href="javascript:void(0);" id="status-footer-returnEdit"><button type="button" class="btn btn-default">返回编辑</button></a>
				<a href="javascript:void(0);" id="status-footer-continueAdd"><button type="button" class="btn btn-default">继续添加</button></a>
				<a href="javascript:void(0);" id="status-footer-goContest"><button type="button" class="btn btn-primary">查看比赛</button></a>
			</div>
		</div>
	</div>
</div>
<div class="container-fluid">
	<div class="col-md-8 col-md-offset-2">
		<div class="row">
			<div class="page-header">
				<h1><?php if($mode == 'add') echo '添加比赛'; else if($mode == 'edit') echo '编辑比赛'; ?><span style="font-size: 15px;"><a href="/admin/problem/list.php">&nbsp;&lt;返回比赛列表</a></span></h1>
			</div>
		</div>
		<div class="row">
			<div class="col-md-3">
				<div class="input-group">
					<span class="input-group-addon" id="cid-label">比赛 CID</span>
					<input type="text" class="form-control" value="<?php if($mode == 'edit') echo $row['cid']; else echo 'NEW'; ?>" aria-describedby="cid-label" disabled id="cid">
				</div>
			</div>
			<div class="col-md-9">
				<div class="input-group">
					<span class="input-group-addon" id="name-label">比赛名称</span>
					<input type="text" class="form-control" placeholder="必填" aria-describedby="name-label" id="name" value="<?php if($mode == 'edit') echo $row['name']; ?>">
				</div>
			</div>
		</div>
		<div class="row" style="margin-top: 10px;">
			<div class="col-md-6">
				<div class="input-group date" id="starttimePicker">
					<span class="input-group-addon" id="starttime-label">开始时间</span>
					<input type="text" class="form-control" placeholder="必填" aria-describedby="starttime-label" id="starttime">
					<span class="input-group-addon">
						<span class="glyphicon glyphicon-calendar"></span>
					</span>
				</div>
			</div>
			<div class="col-md-6">
				<div class="input-group date" id="endtimePicker">
					<span class="input-group-addon" id="endtime-label">结束时间</span>
					<input type="text" class="form-control" placeholder="必填" aria-describedby="endtime-label" id="endtime">
					<span class="input-group-addon">
						<span class="glyphicon glyphicon-calendar"></span>
					</span>
				</div>
			</div>
		</div>
		<div class="row" style="margin-top: 10px;">
			<div class="col-md-6">
				<div class="input-group">
					<span class="input-group-addon" id="contestform-label">比赛形式</span>
					<select class="selectpicker form-control" aria-describedby="contestform-label" id="contestform">
						<option value="ACM/ICPC" <?php
						if($mode == 'edit')
							if($row['contestform'] == 'ACM/ICPC') echo 'selected';
						?>>ACM/ICPC</option>
						<option value="CodeForces" <?php
						if($mode == 'edit')
							if($row['contestform'] == 'CodeForces') echo 'selected';
						?>>CodeForces</option>
					</select>
				</div>
			</div>
			<div class="col-md-6">
				<div class="input-group">
					<span class="input-group-addon" id="openness-label">参与权限</span>
					<select class="selectpicker form-control" aria-describedby="openness-label" id="openness">
						<option value="public" <?php
						if($mode == 'edit')
							if($row['openness'] == 'public') echo 'selected';
						?>>开放</option>
						<option value="private" <?php
						if($mode == 'edit')
							if($row['openness'] == 'private') echo 'selected';
						?>>私有</option>
					</select>
				</div>
			</div>
		</div>
		<div class="row" style="margin-top: 20px">
			<ul class="nav nav-tabs" role="tablist">
				<li role="presentation" class="active"><a href="#problem" aria-controls="problem" role="tab" data-toggle="tab">题目</a></li>
				<li role="presentation"><a href="#introduce" aria-controls="introduce" role="tab" data-toggle="tab">介绍</a></li>
				<li role="presentation"><a href="#person" aria-controls="person" role="tab" data-toggle="tab">人员</a></li>
				<li role="presentation" class="pull-right"><a href="javascript:void(0);" id="submit">提交</a></li>
			</ul>
		</div>
		<div class="row">
			<div class="tab-content">
				<div role="tabpanel" class="tab-pane fade in active" id="problem">
					<div class="container-fluid">
						<div class="row" style="margin-top: 10px;">
							<button class="btn btn-primary" id="problem-add"><span class="glyphicon glyphicon-plus"></span> 添加一道题目</button>
							<button class="btn btn-danger" id="problem-remove"><span class="glyphicon glyphicon-minus"></span> 删除选中的题目</button>
							<button class="btn btn-default" id="problem-select-all">全选</button>
							<button class="btn btn-default" id="problem-select-opposite">反选</button>
							<button class="btn btn-default" id="problem-select-none">取消选择</button>
						</div>
						<div class="row" style="margin-top: 10px;">
							<table class="table table-striped table-hover table-bordered">
								<colgroup>
									<col width="6%"></col>
									<col width="10%"></col>
									<col width="10%"></col>
									<col width="37%"></col>
									<col width="37%"></col>
								</colgroup>
								<thead>
									<tr>
										<td></td>
										<td>编号</td>
										<td>PID</td>
										<td>自定义标题</td>
										<td>原始标题</td>
									</tr>
								</thead>
								<tbody id="problem-table-tbody">
								<?php
								if($mode == 'edit'): 
								$problem = json_decode($row['problem'], true);
								$cnt_problem = count($problem);
								for($i = 0; $i < $cnt_problem; $i++): 
								?>
								<tr class="problem-tr" data-problem-id="<?php echo getProblemCharID($i + 1); ?>">
									<td>
										<input type="checkbox" class="problem-select">
									</td>
									<td class="problem-id">
										<?php echo getProblemCharID($i + 1); ?>
									</td>
									<td>
										<input type="text" class="form-control problem-pid" value="<?php echo $problem[$i]['pid']; ?>">
									</td>
									<td>
										<input type="text" class="form-control problem-custom-title" value="<?php echo $problem[$i]['custom_title']; ?>">
									</td>
									<td class="problem-title">
										
									</td>
								</tr>
								<?php endfor; ?>
								<?php endif; ?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
				<div role="tabpanel" class="tab-pane fade" id="introduce">
					<div class="container-fluid">
						<div class="row">
							<textarea class="form-control" style="resize: none; border-top: 0px;" rows="20" id="text-introduce"><?php if($mode == 'edit') echo $row['introduce']; ?></textarea>
						</div>
					</div>
				</div>
				<div role="tabpanel" class="tab-pane fade" id="person">
					<div class="container-fluid">
						<div class="row" style="margin-top: 10px;">
							<button class="btn btn-primary" id="person-add"><span class="glyphicon glyphicon-plus"></span> 添加人员</button>
							<!-- 使用上传文件上传格式化的表格 -->
							<button class="btn btn-warning" id="person-import"><span class="glyphicon glyphicon-import"></span> 导入人员</button>
							<button class="btn btn-danger" id="person-remove"><span class="glyphicon glyphicon-minus"></span> 删除人员</button>
							<button class="btn btn-default" id="person-select-all">全选</button>
							<button class="btn btn-default" id="person-select-opposite">反选</button>
							<button class="btn btn-default" id="person-select-none">取消选择</button>
						</div>
						<div class="row" style="margin-top: 10px;">
							<table class="table table-striped table-hover table-bordered">
								<colgroup>
									<col width="6%"></col>
									<col width="10%"></col>
									<col width="24%"></col>
									<col width="60%"></col>
								</colgroup>
								<thead>
									<tr>
										<td></td>
										<td>编号</td>
										<td>用户名</td>
										<td>权限</td>
									</tr>
								</thead>
								<tbody id="person-table-tbody">
									<?php if($mode == 'edit'):
									$person = json_decode($row['person'], true);
									$cnt_person = count($person);
									for($i = 0; $i < $cnt_person; $i++):
									?>
									<?php
									$person_uid = $person[$i]['uid'];
									$query = "SELECT * FROM `user` WHERE `user`.`uid`='$person_uid' LIMIT 0, 1;";
									$person_result = mysql_query($query, $con);
									$person_user = mysql_fetch_array($person_result);
									mysql_free_result($person_result);
									?>
									<tr class="person-tr">
										<td>
											<input type="checkbox" class="person-select">
										</td>
										<td class="person-id">
											<?php echo $i + 1; ?>
										</td>
										<td>
											<input type="text" class="form-control person-username" value="<?php echo $person_user['username']; ?>">
										</td>
										<td>
											<select class="selectpicker form-control person-permission" multiple>
												<option value="participant" <?php if(in_array("participant", $person[$i]['permission'])) echo 'selected'; ?>>参与者</option>
												<option value="manager" <?php if(in_array("manager", $person[$i]['permission'])) echo 'selected'; ?>>管理者</option>
												<option value="sourcebrowser" <?php if(in_array("sourcebrowser", $person[$i]['permission'])) echo 'selected'; ?>>代码查看者</option>
											</select>
										</td>
									</tr>
									<?php endfor; ?>
									<?php endif; ?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	function setProblemPIDKeyUp() {
		$('.problem-pid').keyup(function() {
			$this_id = $(this).parent().parent().attr('data-problem-id');
			if($(this).val() == '') {
				$this.parent().parent().find('.problem-title').html('');
				return;
			}
			$(this).parent().parent().find('.problem-title').html('加载中...');
			$.ajax({
				type: 'POST',
				async: true,
				url: 'getProblemTitle.php',
				data: {
					pid: $(this).val(),
					id: $this_id
				},
				success: function(data) {
					var ret = JSON.parse(data);
					$('.problem-tr[data-problem-id="' + ret.id + '"]').find('.problem-title').html(ret.title);
				}
			});
		});
	}
	$(document).ready(function() {
		setProblemPIDKeyUp();
		<?php if($mode == 'edit'): ?>
		$('#starttime').val("<?php echo $row['start_time']; ?>");
		$('#endtime').val("<?php echo $row['end_time']; ?>");
		$('.problem-pid').keyup();
		<?php endif; ?>
	});
	$('input').iCheck({
		checkboxClass: 'icheckbox_square-blue',
		radioClass: 'iradio_square-blue',
	});
	$('#starttimePicker').datetimepicker({
		format: 'YYYY-MM-DD HH:mm:ss',  
		locale: 'zh-CN'
	});
	$('#endtimePicker').datetimepicker({
		format: 'YYYY-MM-DD HH:mm:ss',  
		locale: 'zh-CN'
	});
	// start problem control
	cnt_problem = <?php if(isset($cnt_problem)) echo $cnt_problem; else echo 0; ?>;
	function getProblemID(cnt_problem) {
		var t = (cnt_problem - 1) / 26 + 1;
		var id = '';
		for(var i = 1; i <= t; i++)
			id += String.fromCharCode((cnt_problem - 1) % 26 + 'A'.charCodeAt());
		return id;
	}
	function addProblem(pid, title) {
		var content = '<tr class="problem-tr" data-problem-id="' + getProblemID(++cnt_problem) + '"><td><input type="checkbox" class="problem-select"></td><td class="problem-id">' + getProblemID(cnt_problem) + '</td><td><input type="text" class="form-control problem-pid" value="' + pid + '"></td><td><input type="text" class="form-control problem-custom-title" value="' + title + '"></td><td class="problem-title"></td></tr>';
		$('#problem-table-tbody').append(content);
		$('input').iCheck({
			checkboxClass: 'icheckbox_square-blue',
			radioClass: 'iradio_square-blue',
		});
		// problem title AJAX
		setProblemPIDKeyUp();
	}
	$('#problem-add').click(function() {
		addProblem('', '');
	});
	$('#problem-remove').click(function() {
		cnt_problem = $(".problem-tr").size();
		$(".problem-tr").each(function() {
			if($(this).find(".problem-select").prop('checked') == true) {
				cnt_problem--;
				$(this).remove();
			}
		});
		var tcnt_problem = 0;
		$(".problem-id").each(function() {
			$(this).html(getProblemID(++tcnt_problem));
		});
	});
	$('#problem-select-all').click(function() {
		$(".problem-select").each(function() {
			$(this).iCheck('check');
		});
	});
	$('#problem-select-opposite').click(function() {
		$(".problem-select").each(function() {
			if($(this).prop('checked') == true) {
				$(this).iCheck('uncheck');
			}
			else {
				$(this).iCheck('check');
			}
		});
	});
	$('#problem-select-none').click(function() {
		$(".problem-select").each(function() {
			$(this).iCheck('uncheck');
		});
	});
	// end problem control
	// start person control
	cnt_person = <?php if(isset($cnt_person)) echo $cnt_person; else echo 0; ?>;
	function addPerson(username) {
		var content = '<tr class="person-tr"><td><input type="checkbox" class="person-select"></td><td class="person-id">' + ++cnt_person + '</td><td><input type="text" class="form-control person-username" value="' + username + '"></td><td><select class="selectpicker form-control person-permission" multiple><option value="participant" selected>参与者</option><option value="manager">管理者</option><option value="sourcebrowser">代码查看者</option></select></td></tr>';
		$('#person-table-tbody').append(content);
		$('input').iCheck({
			checkboxClass: 'icheckbox_square-blue',
			radioClass: 'iradio_square-blue',
		});
		$('.selectpicker').selectpicker('show');
	}
	$('#person-add').click(function() {
		addPerson('');
	});
	$('#person-remove').click(function() {
		cnt_person = $(".person-tr").size();
		$(".person-tr").each(function() {
			if($(this).find(".person-select").prop('checked') == true) {
				cnt_person--;
				$(this).remove();
			}
		});
		var tcnt_person = 0;
		$(".person-id").each(function() {
			$(this).html(++tcnt_person);
		});
	});
	$('#person-select-all').click(function() {
		$(".person-select").each(function() {
			$(this).iCheck('check');
		});
	});
	$('#person-select-opposite').click(function() {
		$(".person-select").each(function() {
			if($(this).prop('checked') == true) {
				$(this).iCheck('uncheck');
			}
			else {
				$(this).iCheck('check');
			}
		});
	});
	$('#person-select-none').click(function() {
		$(".person-select").each(function() {
			$(this).iCheck('uncheck');
		});
	});
	// end person control
	function getProblem() {
		var size = $(".problem-tr").size();
		var problem = [];
		for(var i = 1; i <= size; i++) {
			var text = {"pid": $(".problem-pid").eq(i - 1).val(), "custom_title": $(".problem-custom-title").eq(i - 1).val()};
			problem.push(text);
		}
		return JSON.stringify(problem);
	}
	function getPerson() {
		var size = $(".person-tr").size();
		var person = [];
		for(var i = 1; i <= size; i++) {
			var text = {"username": $(".person-username").eq(i - 1).val(), "permission": $(".selectpicker.person-permission").eq(i - 1).selectpicker('val')};
			person.push(text);
		}
		return JSON.stringify(person);
	}
	function _PopoverShow(content, id) {
		$(id).popover({
			trigger: "manual",
			container: "body",
			html: true,
			content: content
		});
		$(id).popover('show');
	}
	function PopoverShow(content, id) {
		return function() {
			_PopoverShow(content, id);
		};
	}
	function destroyAllPopover() {
		$('#cid').popover('destroy');
		$('#name').popover('destroy');
		$('#starttimePicker').popover('destroy');
		$('#endtimePicker').popover('destroy');
		$('#contestform').popover('destroy');
		$('#openness').popover('destroy');
		$('.problem-pid').popover('destroy');
		$('.person-username').popover('destroy');
	}
	function Popover(content, id) {
		destroyAllPopover();
		setTimeout(PopoverShow(content, id), 150);
	}
	// submit
	$('#name').keyup(function() {
		$('#name').popover('destroy');
	});
	$('#starttime').keyup(function() {
		$('#starttimePicker').popover('destroy');
	});
	$('#starttimePicker').on("dp.change", function(e) {
		$('#starttimePicker').popover('destroy');
	});
	$('#endtime').keyup(function() {
		$('#endtimePicker').popover('destroy');
	});
	$('#endtimePicker').on("dp.change", function(e) {
		$('#endtimePicker').popover('destroy');
	});
	$('#submit').click(function() {
		$.ajax({
			type: 'POST',
			async: false,
			url: 'inserter.php',
			data: {
				cid: $('#cid').val(),
				name: $('#name').val(),
				starttime: $('#starttime').val(),
				endtime: $('#endtime').val(),
				contestform: $('#contestform').selectpicker('val'),
				openness: $('#openness').selectpicker('val'),
				problem: getProblem(),
				introduce: $('#text-introduce').val(),
				person: getPerson()
			},
			success: function(data) {
				var ret = JSON.parse(data);
				console.log(ret);
				if(ret.status == 'Invalid name') {
					Popover('名称不能为空', '#name');
				}
				else if(ret.status == 'No problem') {
					Popover('题目不能为空', 'a[aria-controls="problem"]');
					setTimeout(function() {$('a[aria-controls="problem"]').popover('destroy')}, 5000);
				}
				else if(ret.status == 'Invalid start time') {
					Popover('开始时间不合法', '#starttimePicker');
				}
				else if(ret.status == 'Invalid end time') {
					Popover('结束时间不合法', '#endtimePicker');
				}
				else if(ret.status == 'End time is before start time') {
					Popover('结束时间在开始时间之前', '#endtimePicker');
				}
				else if(ret.status == 'Invalid problem format') {

				}
				else if(ret.status == 'Invalid PID') {

				}
				else if(ret.status == 'Problem Error') {

				}
				else if(ret.status == 'Invaild CID') {
					Popover('CID 不合法', '#cid');
				}
				else {
					$('#status-footer-returnEdit').removeAttr('hidden');
					$('#status-footer-continueAdd').removeAttr('hidden');
					$('#status-footer-goContest').removeAttr('hidden');
					$('#status-footer-returnEdit').attr('href', 'javascript:void(0);');
					$('#status-footer-continueAdd').attr('href', 'javascript:void(0);');
					$('#status-footer-goContest').attr('href', 'javascript:void(0);');
					$('#status-footer-returnEdit').removeAttr('data-dismiss');
					if($('#cid').val() == 'NEW') {
						if(ret.status == 'success') {
							$('#status-content').html('添加成功！新的比赛的 CID：<span class="label label-success">' + ret.cid + '</span>');
							$('#status-footer-returnEdit').attr('href', '/admin/contest/edit.php?mode=edit&cid=' + ret.cid);
							$('#status-footer-continueAdd').attr('href', '/admin/contest/edit.php?mode=add');
							$('#status-footer-goContest').attr('href', '/contest/view.php?cid=' + ret.cid);
						}
						else if(ret.status == 'failed') {
							$('#status-content').html('添加失败，有可能是数据插入失败，请检查数据库。');
							$('#status-footer-returnEdit').attr('data-dismiss', 'modal');
							$('#status-footer-continueAdd').attr('hidden', 'hidden');
							$('#status-footer-goContest').attr('hidden', 'hidden');
						}
					}
					else {
						$('#status-footer-continueAdd').attr('hidden', 'hidden');
						if(ret.status == "success") {
							$('#status-content').html('修改成功！');
							$('#status-footer-returnEdit').attr('href', '/admin/contest/edit.php?mode=edit&cid=' + ret.cid);
							$('#status-footer-goContest').attr('href', '/contest/view.php?cid=' + ret.cid);
						}
						else if(ret.status == 'failed') {
							$('#status-content').html('修改失败，有可能是数据插入失败，请检查数据库；或者是内容无变化。');
							$('#status-footer-returnEdit').attr('data-dismiss', 'modal');
							$('#status-footer-continueAdd').attr('hidden', 'hidden');
							$('#status-footer-goContest').attr('hidden', 'hidden');
						}
					}
					$('#status').modal('show');
				}
			}
		});
	});
	//$('#status').modal('show');
</script>
<?php require_once '../common/admin_footer.php'; ?>