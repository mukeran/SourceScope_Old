<?php require_once "../common/admin_header.php"; ?>
<?php
if(!isset($_GET['mode']))
	header('Location: /admin/problem/edit.php?mode=add');
$mode = $_GET['mode'];
if($mode != 'add' && $mode != 'edit')
	header('Location: /admin/problem/edit.php?mode=add');
if($mode == 'edit') {
	if(!isset($_GET['pid']))
		header('Location: /admin/problem/edit.php?mode=add');
	$pid = $_GET['pid'];
	$query = "SELECT * FROM `problem` WHERE `pid`='$pid' LIMIT 0, 1;";
	$result = mysql_query($query, $con);
	if(!mysql_num_rows($result))
		header('Location: /admin/problem/edit.php?mode=add');
	$row = mysql_fetch_array($result);
	$default_version = $row['default_version'];
	$id = $row['id'];
	$query = "SELECT * FROM `problem_version` WHERE `vid`='$default_version' LIMIT 0, 1;";
	$result = mysql_query($query, $con);
	$row = mysql_fetch_array($result);
	$title = $row['title'];
	$tag = $row['tag'];
	$description = $row['description'];
	$description = str_replace("<br/>", "\n", $description);
	$input = $row['input'];
	$input = str_replace("<br/>", "\n", $input);
	$output = $row['output'];
	$output = str_replace("<br/>", "\n", $output);
	$sample = $row['sample'];
	$sample = json_decode($sample, true);
	$hint = $row['hint'];
	$hint = str_replace("<br/>", "\n", $hint);
	$timelimit = $row['timelimit'];
	$memorylimit = $row['memorylimit'];
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
						<span id="status-content">题目添加成功！题目编号：123</span>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<a href="javascript:void(0);" id="status-footer-returnEdit"><button type="button" class="btn btn-default">返回编辑</button></a>
				<a href="javascript:void(0);" id="status-footer-continueAdd"><button type="button" class="btn btn-default">继续添加</button></a>
				<a href="javascript:void(0);" id="status-footer-goProblem"><button type="button" class="btn btn-primary">查看题目</button></a>
			</div>
		</div>
	</div>
</div>
<div class="container-fluid">
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<div class="row">
				<div class="page-header">
					<h1><?php if($mode == 'add') echo '添加题目'; else if($mode == 'edit') echo '编辑题目'; ?><span style="font-size: 15px;"><a href="/admin/problem/list.php">&nbsp;&lt;返回题目列表</a></span></h1>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6">
					<div class="row">
						<div class="col-md-6">
							<div class="input-group">
								<span class="input-group-addon" id="id-label" style="">数据库ID</span>
								<input type="text" class="form-control" id="id" placeholder="ID" aria-describedby="id-label" value="<?php if($mode == 'add') echo 'NEW'; else echo $id;?>" disabled>
							</div>
						</div>
						<div class="col-md-6">
							<div class="input-group">
								<span class="input-group-addon" id="pid-label" style="">题目PID</span>
									<input type="text" class="form-control" id="pid" placeholder="留空会使用从1000开始递增的ID" aria-describedby="pid-label"<?php if($mode == 'edit') echo " value='$pid' disabled"; ?>>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="row">
						<div class="col-md-6">
							<div class="input-group">
								<input type="text" class="form-control" id="timelimit" placeholder="时间限制，默认 1000" aria-describedby="timelimit-label"<?php if($mode == 'edit') echo " value='$timelimit'"; ?>>
								<span class="input-group-addon" id="timelimit-label">ms</span>
							</div>
						</div>
						<div class="col-md-6">
							<div class="input-group">
								<input type="text" class="form-control" id="memorylimit" placeholder="内存限制，默认 65536" aria-describedby="memorylimit-label"<?php if($mode == 'edit') echo " value='$memorylimit'"; ?>>
								<span class="input-group-addon" id="memorylimit-label">kb</span>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="row" style="margin-top: 10px;">
				<div class="col-md-6">
					<div class="input-group">
						<span class="input-group-addon" id="pid-label">题目标题</span>
						<input type="text" class="form-control" id="title" placeholder="必填"<?php if($mode == 'edit') echo " value='$title'"; ?>>
					</div>
				</div>
				<div class="col-md-6">
					<div class="input-group">
						<span class="input-group-addon" id="pid-label">题目标签</span>
						<input type="text" class="form-control" id="tag" placeholder="用英文逗号隔开"<?php if($mode == 'edit') echo " value='$tag'"; ?>>
					</div>
				</div>
			</div>
			<div class="row" style="margin-top: 10px;">
				<ul class="nav nav-tabs" role="tablist">
					<li role="presentation" class="active"><a href="#description" aria-controls="description" role="tab" data-toggle="tab">描述</a></li>
					<li role="presentation"><a href="#input" aria-controls="input" role="tab" data-toggle="tab">输入</a></li>
					<li role="presentation"><a href="#output" aria-controls="output" role="tab" data-toggle="tab">输出</a></li>
					<li role="presentation"><a href="#sample" aria-controls="sample" role="tab" data-toggle="tab">样例</a></li>
					<li role="presentation"><a href="#hint" aria-controls="hint" role="tab" data-toggle="tab">提示</a></li>
					<li role="presentation"><a href="#data" aria-controls="data" role="tab" data-toggle="tab">数据</a></li>
					<li role="presentation" style="float: right;"><a href="javascript:void(0);" id="submit">提交</a></li>
				</ul>
			</div>
			<div class="row">
				<div class="tab-content">
					<div role="tabpanel" class="tab-pane fade in active" id="description">
						<textarea class="form-control" rows="30" style="border-top: 0px; border-radius: 0px; resize: none;" id="text-description"><?php if($mode == 'edit') echo $description; ?></textarea>
					</div>
					<div role="tabpanel" class="tab-pane fade" id="input">
						<textarea class="form-control" rows="30" style="border-top: 0px; border-radius: 0px; resize: none;" id="text-input"><?php if($mode == 'edit') echo $input; ?></textarea>
					</div>
					<div role="tabpanel" class="tab-pane fade" id="output">
						<textarea class="form-control" rows="30" style="border-top: 0px; border-radius: 0px; resize: none;" id="text-output"><?php if($mode == 'edit') echo $output; ?></textarea>
					</div>
					<div role="tabpanel" class="tab-pane fade" id="sample">
						<div class="col-md-12">
							<div class="row" style="margin-top: 10px;">
								<button class="btn btn-primary" id="add-sample"><span class="glyphicon glyphicon-plus"></span> 添加一组样例</button>
								<button class="btn btn-danger" id="remove-sample"><span class="glyphicon glyphicon-minus"></span> 删除选中的样例</button>
								<button class="btn btn-default" id="select-all-sample">全选</button>
								<button class="btn btn-default" id="select-none-sample">取消选择</button>
								<button class="btn btn-default" id="select-opposite-sample">反选</button>
							</div>
							<div class="row" style="margin-top: 10px;">
								<table class="table table-striped table-hover table-bordered" style="font-size: 15px;">
									<colgroup>
										<col width="4%"></col>
										<col width="4%"></col>
										<col width="46%"></col>
										<col width="46%"></col>
									</colgroup>
									<thead>
										<tr>
											<td></td>
											<td>ID</td>
											<td>样例输入</td>
											<td>样例输出</td>
										</tr>
									</thead>
									<tbody id="sample-table-tbody">
										<?php if($mode == 'edit') for($i = 0; $i < count($sample); $i++): ?>
										<tr class="sample-tr">
											<td>
												<input type="checkbox" class="select-sample">
											</td>
											<td class="sample-id"><?php echo $i + 1; ?></td>
											<td>
												<textarea class="form-control sample-input" rows="10"><?php echo str_replace("<br/>", "\n", $sample[$i]["sample-input"]); ?></textarea>
											</td>
											<td><textarea class="form-control sample-output" rows="10"><?php echo str_replace("<br/>", "\n", $sample[$i]['sample-output']); ?></textarea>
											</td>
										</tr>
										<?php endfor; ?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
					<div role="tabpanel" class="tab-pane fade" id="hint">
						<textarea class="form-control" rows="30" style="border-top: 0px; border-radius: 0px; resize: none;" id="text-hint"><?php if($mode == 'edit') echo $hint; ?></textarea>
					</div>
					<div role="tabpanel" class="tab-pane fade" id="data">
						<div class="col-md-12">
							<div class="row" style="margin-top: 10px;">
								<div class="col-md-8">
									<div class="row">
										<button class="btn btn-primary" id="add-data"><span class="glyphicon glyphicon-plus"></span> 添加一组数据</button>
										<button class="btn btn-danger" id="remove-data"><span class="glyphicon glyphicon-minus"></span> 删除选中的数据</button>
										<button class="btn btn-default" id="select-all-data">全选</button>
										<button class="btn btn-default" id="select-none-data">取消选择</button>
										<button class="btn btn-default" id="select-opposite-data">反选</button>
									</div>
								</div>
								<div class="col-md-4">
									<div class="row pull-right">
										<button class="btn btn-danger" id="remove-data-file"><span class="glyphicon glyphicon-remove"></span> 删除文件</button>
										<input id="upload-data" type="file" class="file" data-show-preview="false">
									</div>
								</div>
								<style type="text/css">
									.file-input {
										display: inline-block;
									}
								</style>
								<script type="text/javascript">
									$("#upload-data").fileinput({
										showCaption: false,
										showRemove: false,
										showUpload: false,
										browseLabel: "上传数据",
										language: 'zh'
									});
								</script>
							</div>
							<div class="row" style="margin-top: 10px;">
								<div class="col-md-8">
									<div class="row">
										<table class="table table-striped table-hover table-bordered" style="font-size: 15px;">
											<colgroup>
												<col width="4%"></col>
												<col width="4%"></col>
												<col width="46%"></col>
												<col width="46%"></col>
											</colgroup>
											<thead>
												<tr>
													<td></td>
													<td>ID</td>
													<td>输入</td>
													<td>输出</td>
												</tr>
											</thead>
											<tbody id="data-table-tbody">
												<tr class="data-tr">
													<td>
														<input type="checkbox" class="select-data">
													</td>
													<td class="data-id">
														
													</td>
													<td id="data-1-in">
														<div class="btn-group" data-toggle="buttons" role="tablist" id="data-1-in-select">
															<label class="btn btn-default active" href="#data-1-in-file" aria-controls="data-1-in-file" role="tab" data-toggle="tab">
																<input type="radio" name="options" autocomplete="off" checked> 上传或选择
															</label>
															<label class="btn btn-default" href="#data-1-in-input" aria-controls="data-1-in-input" role="tab" data-toggle="tab">
																<input type="radio" name="options" autocomplete="off"> 手动输入
															</label>
														</div>
														<div class="tab-content" id="data-1-in-content" style="margin-top: 10px;">
															<div role="tabpanel" class="tab-pane fade in active" id="data-1-in-file">
																<div class="col-md-7">
																	<div class="row">
																		<select class="selectpicker form-control" data-live-search="true"></select>
																	</div>
																</div>
																<div class="col-md-4 col-md-offset-1">
																	<div class="row">
																		<input id="data-1-in-file-upload" type="file" class="file" data-show-preview="false">
																	</div>
																	<script type="text/javascript">
																		$("#data-1-in-file-upload").fileinput({
																			showCaption: false,
																			showRemove: false,
																			showUpload: false,
																			browseLabel: "上传",
																			language: 'zh'
																		});
																	</script>
																</div>
															</div>
															<div role="tabpanel" class="tab-pane fade" id="data-1-in-input">
																<textarea class="form-control" style="resize: none; "></textarea>
															</div>
														</div>
													</td>
													<td id="data-1-out">
														<div class="btn-group" data-toggle="buttons" role="tablist" id="data-1-out-select">
															<label class="btn btn-default active" href="#data-1-out-file" aria-controls="data-1-out-file" role="tab" data-toggle="tab">
																<input type="radio" name="options" autocomplete="off" checked> 上传或选择
															</label>
															<label class="btn btn-default" href="#data-1-out-input" aria-controls="data-1-out-input" role="tab" data-toggle="tab">
																<input type="radio" name="options" autocomplete="off"> 手动输入
															</label>
														</div>
														<div class="tab-content" id="data-1-out-content" style="margin-top: 10px;">
															<div role="tabpanel" class="tab-pane fade in active" id="data-1-out-file">
																<div class="col-md-7">
																	<div class="row">
																		<select class="selectpicker form-control" data-live-search="true"></select>
																	</div>
																</div>
																<div class="col-md-4 col-md-offset-1">
																	<div class="row">
																		<input id="data-1-out-file-upload" type="file" class="file" data-show-preview="false">
																	</div>
																	<script type="text/javascript">
																		$("#data-1-out-file-upload").fileinput({
																			showCaption: false,
																			showRemove: false,
																			showUpload: false,
																			browseLabel: "上传",
																			language: 'zh'
																		});
																	</script>
																</div>
															</div>
															<div role="tabpanel" class="tab-pane fade" id="data-1-out-input">
																<textarea class="form-control" style="resize: none; "></textarea>
															</div>
														</div>
													</td>
												</tr>
											</tbody>
										</table>
									</div>
								</div>
								<div class="col-md-3 col-md-offset-1">
									<div class="row">
										<table class="table table-bordered table-hover table-striped">
											<colgroup>
												
											</colgroup>
											<thead>
												<tr>
													<td>当前题目文件</td>
												</tr>
											</thead>
											<tbody>
												<tr>
													<td>tesst</td>
												</tr>
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<style type="text/css">
	.sample-input, .sample-output {
		resize: none;
	}
	.data-input, .data-output {
		resize: none;
	}
</style>
<script type="text/javascript">
	$('.select-data').iCheck({
		checkboxClass: 'icheckbox_square-red',
	});
	$('.select-sample').iCheck({
		checkboxClass: 'icheckbox_square-red',
	});
	cnt_sample = <?php if(!isset($sample)) echo '0'; else echo count($sample); ?>;
	function getSample() {
		var size = $(".sample-input").size();
		var sample = [];
		for(var i = 1; i <= size; i++) {
			var text = {"sample-input": getFormatVal($(".sample-input").eq(i - 1).val()), "sample-output": getFormatVal($(".sample-output").eq(i - 1).val())};
			sample.push(text);
		}
		return JSON.stringify(sample);
	}
	function addSample(sample_input, sample_output) {
		var content = '<tr class="sample-tr"><td><input type="checkbox" class="select-sample"></td><td class="sample-id">' + (++cnt_sample) + '</td><td><textarea class="form-control sample-input" rows="10">' + sample_input + '</textarea></td><td><textarea class="form-control sample-output" rows="10">' + sample_output + '</textarea></td></tr>';
		$("#sample-table-tbody").append(content);
		$('.select-sample').iCheck({
			checkboxClass: 'icheckbox_square-red',
		});
	};
	$("#add-sample").click(function() {
		addSample('', '');
	});
	$("#remove-sample").click(function() {
		cnt_sample = $(".select-sample").size();
		$(".sample-tr").each(function() {
			if($(this).find(".select-sample").prop('checked') == true) {
				cnt_sample--;
				$(this).remove();
			}
		});
		var tcnt_sample = 0;
		$(".sample-id").each(function() {
			$(this).html(++tcnt_sample);
		});
	});
	$("#select-all-sample").click(function() {
		$(".select-sample").each(function() {
			$(this).iCheck('check');
		});
	});
	$("#select-none-sample").click(function() {
		$(".select-sample").each(function() {
			$(this).iCheck('uncheck');
		});
	});
	$("#select-opposite-sample").click(function() {
		$(".select-sample").each(function() {
			if($(this).prop('checked') == true) {
				$(this).iCheck('uncheck');
			}
			else {
				$(this).iCheck('check');
			}
		});
	});
	cnt_data = <?php if(!isset($data)) echo '0'; else echo count($data); ?>;
	function adddata(data_input, data_output) {
		var content = '<tr class="data-tr"><td><input type="checkbox" class="select-data"></td><td class="data-id">' + (++cnt_data) + '</td><td><textarea class="form-control data-input" rows="10">' + data_input + '</textarea></td><td><textarea class="form-control data-output" rows="10">' + data_output + '</textarea></td></tr>';
		$("#data-table-tbody").append(content);
		$('.select-data').iCheck({
			checkboxClass: 'icheckbox_square-red',
		});
	};
	$("#add-data").click(function() {
		adddata('', '');
	});
	$("#remove-data").click(function() {
		cnt_data = $(".select-data").size();
		$(".data-tr").each(function() {
			if($(this).find(".select-data").prop('checked') == true) {
				cnt_data--;
				$(this).remove();
			}
		});
		var tcnt_data = 0;
		$(".data-id").each(function() {
			$(this).html(++tcnt_data);
		});
	});
	$("#select-all-data").click(function() {
		$(".select-data").each(function() {
			$(this).iCheck('check');
		});
	});
	$("#select-none-data").click(function() {
		$(".select-data").each(function() {
			$(this).iCheck('uncheck');
		});
	});
	$("#select-opposite-data").click(function() {
		$(".select-data").each(function() {
			if($(this).prop('checked') == true) {
				$(this).iCheck('uncheck');
			}
			else {
				$(this).iCheck('check');
			}
		});
	});
	function getFormatVal(text) {
		return text.replace(/\n/g,'<br/>');
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
		$('#id').popover('destroy');
		$('#pid').popover('destroy');
		$('#timelimit').popover('destroy');
		$('#memorylimit').popover('destroy');
		$('#title').popover('destroy');
		$('#tag').popover('destroy');
	}
	function Popover(content, id) {
		destroyAllPopover();
		setTimeout(PopoverShow(content, id), 150);
	}
	$('#pid').click(destroyAllPopover);
	$("#submit").click(function() {
		destroyAllPopover();
		$.ajax({
			type: "post",
			url: "/admin/problem/inserter.php",
			data: {
				id: $("#id").val(),
				pid: $("#pid").val(),
				title: $("#title").val(),
				tag: $("#tag").val(),
				timelimit: $('#timelimit').val(),
				memorylimit: $('#memorylimit').val(),
				description: $("#text-description").val(),
				input: $("#text-input").val(),
				output: $("#text-output").val(),
				sample: getSample(),
				hint: $("#text-hint").val(),
				data: null
			},
			async: false,
			success: function(data) {
				var ret = JSON.parse(data);
				if(ret.status == "Same PID")
					Popover("数据库中有重复 PID", "#pid");
				else if(ret.status == "Invalid ID or PID")
					Popover("ID 或 PID 错误", "#pid");
				else {
					$('#status-footer-returnEdit').removeAttr('hidden');
					$('#status-footer-continueAdd').removeAttr('hidden');
					$('#status-footer-goProblem').removeAttr('hidden');
					$('#status-footer-returnEdit').attr('href', 'javascript:void(0);');
					$('#status-footer-continueAdd').attr('href', 'javascript:void(0);');
					$('#status-footer-goProblem').attr('href', 'javascript:void(0);');
					$('#status-footer-returnEdit').removeAttr('data-dismiss');
					if($("#id").val() == 'NEW') {
						if(ret.status == "success") {
							$('#status-content').html('添加成功！新的题目的 数据库 ID：<span class="label label-success">' + ret.id + '</span> 题目 PID：<span class="label label-success">' + ret.pid + '</span>');
							$('#status-footer-returnEdit').attr('href', '/admin/problem/edit.php?mode=edit&pid=' + ret.pid);
							$('#status-footer-continueAdd').attr('href', '/admin/problem/edit.php?mode=add');
							$('#status-footer-goProblem').attr('href', '/problem/view.php?pid=' + ret.pid);
						}
						else {
							$('#status-content').html('添加失败，有可能是数据插入失败，请检查数据库。');
							$('#status-footer-returnEdit').attr('data-dismiss', 'modal');
							$('#status-footer-continueAdd').attr('hidden', 'hidden');
							$('#status-footer-goProblem').attr('hidden', 'hidden');
						}
					}
					else {
						$('#status-footer-continueAdd').attr('hidden', 'hidden');
						if(ret.status == "success") {
							$('#status-content').html('修改成功！');
							$('#status-footer-returnEdit').attr('href', '/admin/problem/edit.php?mode=edit&pid=' + ret.pid);
							$('#status-footer-goProblem').attr('href', '/problem/view.php?pid=' + ret.pid);
						}
						else {
							$('#status-content').html('修改失败，有可能是数据插入失败，请检查数据库；或者是内容无变化。');
							$('#status-footer-returnEdit').attr('data-dismiss', 'modal');
							$('#status-footer-continueAdd').attr('hidden', 'hidden');
							$('#status-footer-goProblem').attr('hidden', 'hidden');
						}
					}
					$('#status').modal('show');
				}
			}
		});
	});
</script>

<?php require_once "../common/admin_footer.php"; ?>