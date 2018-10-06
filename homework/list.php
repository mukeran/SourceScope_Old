<?php $this_page="homework"; require_once "../common/header.php"; ?>
<div class="container-fluid">
	<div class="row">
		<div class="col-xs-12 col-sm-10 col-sm-offset-1 col-md-10 col-md-offset-1 col-lg-8 col-lg-offset-2">
			<div>
				<ul class="nav nav-tabs" role="tablist">
					<li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab">我的作业</a></li>
					<li role="presentation"><a href="#give-homework" aria-controls="give-homework" role="tab" data-toggle="tab">布置作业</a></li>
					<li role="presentation"><a href="#relative" aria-controls="relative" role="tab" data-toggle="tab">我的关系</a></li>
				</ul>
				<div class="tab-content">
					<div role="tabpanel" class="tab-pane active" id="home"><?php if($isLogin)require_once "./myhomework.php";?></div>
					<div role="tabpanel" class="tab-pane" id="give-homework"><?php if($isLogin) require_once "./givehomework.php";?></div>
					<div role="tabpanel" class="tab-pane" id="relative"><?php if($isLogin)require_once "./myrelative.php";?></div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php require_once "../common/footer.php"; ?>