<?php
$this_page = 'settings';
require_once "../common/admin_header.php";
?>

<style type="text/css">
	li {
		text-decoration: none;
		list-style-type: none;
	}
	.setting-sidebar-first, .setting-sidebar-first:visited, .setting-sidebar-first:link {
		display: block;
		width: 100%;
		height: 40px;
		line-height: 40px;
		font-size: 18px;
		font-weight: normal;
		text-align: right;
		padding-right: 10px;
		color: grey;
		border-radius: 5px;
	}
	.setting-sidebar-first:hover {
		border-left: 3px solid black;
		color: black;
	}
	.setting-sidebar-first[aria-expanded="true"] {
		border-left: 3px solid black;
		color: black;
		background-color: rgba(0, 0, 0, 0.03);
	}
	.setting-sidebar-second {
		padding: 0px;
	}
	.setting-sidebar-second li a, .setting-sidebar-second li a:visited, .setting-sidebar-second li a:link {
		margin: 12px 0px;
		display: block;
		width: 100%;
		height: 30px;
		line-height: 30px;
		font-size: 15px;
		text-align: right;
		padding-right: 10px;
		color: grey;
	}
	.setting-sidebar-second li a:hover {
		background-color: white;
		color: black;
		border-left: 3px solid grey;
	}
	.setting-sidebar-second-selected {
		border-left: 3px solid black !important;
		color: black !important;
	}
	#expand-list {
		position: absolute;
		right: 0px;
		top: 15px;
		color: black;
	}
	#expand-list:hover {
		color: grey;
		cursor: pointer;
	}
</style>
<div class="container-fluid">
	<div class="col-md-10 col-md-offset-1 col-sm-12 col-xs-12">
		<div class="col-md-2 col-sm-2 col-xs-2">
			<div class="row">
				<div class="setting-sidebar">
					<span style="display: inline-block; font-size: 25px; margin-bottom: 15px;">设置</span>
					<a id="expand-list"><span class="glyphicon glyphicon-th-list"></span></a>
					<a href="#commonSettings" class="setting-sidebar-first collapsed" data-toggle="collapse">常规</a>
					<ul id="commonSettings" class="collapse setting-sidebar-second" role="tablist">
						<li><a href="#commonSettings-standard" aria-controls="commonSettings-standard" role="tab" data-toggle="tab">基础</a></li>
						<li><a href="#commonSettings-extra" aria-controls="commonSettings-extra" role="tab" data-toggle="tab">扩展</a></li>
					</ul>
					<a href="#problemSettings" class="setting-sidebar-first collapsed" data-toggle="collapse">问题</a>
					<ul id="problemSettings" class="collapse setting-sidebar-second" role="tablist">
						<li><a href="#">基础</a></li>
						<li><a href="#">扩展</a></li>
					</ul>
					<a href="#contestSettings" class="setting-sidebar-first collapsed" data-toggle="collapse">比赛</a>
					<ul id="contestSettings" class="collapse setting-sidebar-second" role="tablist">
						<li><a href="#">基础</a></li>
						<li><a href="#">扩展</a></li>
					</ul>
				</div>
			</div>
		</div>
		<div class="col-md-10 col-sm-10 col-xs-10">
			<div class="tab-content">
				<div role="tabpanel" class="tab-pane fade in active" id="commonSettings-standard">
				test
				</div>
				<div role="tabpanel" class="tab-pane fade" id="commonSettings-extra">
				test2
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	$('#expand-list').click(function() {
		$('.setting-sidebar-first').each(function() {
			if(!($(this).attr('aria-expanded') == 'true'))
				$(this).click();
		});
	});
	$("a[data-toggle='tab']").click(function() {
		$("a[data-toggle='tab']").each(function() {
			$(this).removeClass("setting-sidebar-second-selected");
		});
		$(this).addClass("setting-sidebar-second-selected");
	});
</script>
<?php require_once "../common/admin_footer.php"; ?>