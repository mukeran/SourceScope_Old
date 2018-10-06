<?php require "../common/admin_header.php"; ?>

<div class="container-fluid">
	<div class="col-md-8 col-md-offset-2">
		<div class="row">
			<h1 class="page-header">重判题目</h1>	
		</div>
		<div class="row">
			<div class="col-md-6">
				<div class="row" style="margin-top: 10px;">
					<div class="input-group">
						<span class="input-group-addon" id="sid-span">SID</span>
						<input class="form-control" aria-describedby="sid-span" id="sid">
						<span class="input-group-btn">
							<button class="btn btn-default" type="button" id="sid-button">重判</button>
						</span>
					</div>
				</div>
				<div class="row" style="margin-top: 10px;">
					<div class="input-group">
						<span class="input-group-addon" id="pid-span">PID</span>
						<input class="form-control" aria-describedby="pid-span" id="pid">
						<span class="input-group-btn">
							<button class="btn btn-default" type="button" id="pid-button">重判</button>
						</span>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	$('#sid-button').click(function() {
		if($('#sid').val() == '') {
			alert('No SID');
			return;
		}
		$.ajax({
			type: 'POST',
			url: '/submission/rejudge.php',
			data: {
				sid: $('#sid').val()
			},
			success: function(data) {
				alert(data);
			}
		});
	});
	$('#pid-button').click(function() {
		if($('#pid').val() == '') {
			alert('No PID');
			return;
		}
		$.ajax({
			type: 'POST',
			url: '/submission/rejudge.php',
			data: {
				pid: $('#pid').val()
			},
			success: function(data) {
				alert(data);
			}
		});
	});
</script>
<?php require "../common/admin_footer.php"; ?>