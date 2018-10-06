<?php require_once '../common/header.php'; ?>
<?php
if(!$isLogin)
	header('Location: /');
$uid = $_SESSION['uid'];
$query = "SELECT * FROM `user` WHERE `uid`='$uid' LIMIT 0, 1;";
$user = mysql_query($query, $con);
$user = mysql_fetch_array($user);
$information = json_decode($user['information'], true);
?>
<div class="container-fluid">
	<div class="col-md-8 col-md-offset-2">
		<div class="row">
			<h1 class="page-header">修改资料</h1>
		</div>
		<div class="row">
			<div class="col-md-6">
				<input type="text" class="form-control input-lg" id="username" value="<?php echo $user['username']; ?>" disabled>
				<input type="text" class="form-control input-lg" id="nickname" placeholder="昵称" value="<?php echo $user['nickname']; ?>" style="margin-top: 10px">
				<input type="password" class="form-control input-lg" id="password" placeholder="密码（必填）" style="margin-top: 10px;">
				<input type="password" class="form-control input-lg" id="repeat-password" placeholder="重复密码" style="margin-top: 10px;">
				<input type="email" class="form-control input-lg" id="email" placeholder="邮箱（必填）" value="<?php echo $user['email']; ?>" style="margin-top: 10px; margin-bottom: 10px;">
			</div>
			<div class="col-md-6">
				<input type="text" class="form-control input-lg" id="school" placeholder="学校" value="<?php echo $information['school']; ?>">
				<input type="text" class="form-control input-lg" id="self-page" placeholder="个人主页" style="margin-top: 10px" value="<?php echo $information['self_page']; ?>">
				<textarea class="form-control input-lg" id="sign" placeholder="签名" style="margin-top: 10px; resize: none;" rows="3"><?php echo $information['sign']; ?></textarea>
				<div class="row" style="margin-top: 18px;">
					<div class="col-md-8" style="margin-bottom: 10px;">
						<div class="input-group">
							<input type="text" class="form-control input-lg" placeholder="验证" aria-describedby="vcode-span" id="vcode">
							<span class="input-group-addon" id="vcode-span" style="padding: 0px; border: 0px;"><img src="/account/vcode.php" style="height: 46px; border-top-right-radius: 6px; border-bottom-right-radius: 6px;" id="vcode-img"></span>
						</div>
					</div>
					<div class="col-md-4">
						<button type="button" id="edit-button" class="btn btn-primary btn-lg pull-right">修改</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php require_once "../common/footer.php"; ?>