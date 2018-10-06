<?php require_once "../common/header.php"; ?>

<div class="container-fluid">
	<div class="row">
		<div class="col-md-6 col-md-offset-3 col-xs-10 col-xs-offset-1">
			<div class="row">
				<div class="page-header">
					<h1>用户注册</h1>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6">
					<input type="text" class="form-control input-lg" id="username" placeholder="用户名（必填）">
					<input type="text" class="form-control input-lg" id="nickname" placeholder="昵称" style="margin-top: 10px">
					<input type="password" class="form-control input-lg" id="password" placeholder="密码（必填）" style="margin-top: 10px;">
					<input type="password" class="form-control input-lg" id="repeat-password" placeholder="重复密码（必填）" style="margin-top: 10px;">
					<input type="email" class="form-control input-lg" id="email" placeholder="邮箱（必填）" style="margin-top: 10px; margin-bottom: 10px;">
				</div>
				<div class="col-md-6">
					<input type="text" class="form-control input-lg" id="school" placeholder="学校">
					<input type="text" class="form-control input-lg" id="self-page" placeholder="个人主页" style="margin-top: 10px">
					<textarea class="form-control input-lg" id="sign" placeholder="签名" style="margin-top: 10px; resize: none;" rows="3"></textarea>
					<div class="row" style="margin-top: 18px;">
						<div class="col-md-8" style="margin-bottom: 10px;">
							<div class="input-group">
								<input type="text" class="form-control input-lg" placeholder="验证" aria-describedby="vcode-span" id="vcode">
								<span class="input-group-addon" id="vcode-span" style="padding: 0px; border: 0px;"><img src="/account/vcode.php" style="height: 46px; border-top-right-radius: 6px; border-bottom-right-radius: 6px;" id="vcode-img"></span>
							</div>
						</div>
						<div class="col-md-4">
							<button type="button" id="register-button" class="btn btn-primary btn-lg pull-right">注册</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	$('#vcode-img').click(function() {
		$(this).attr("src", "/account/vcode.php");
	});
	$('#register-button').click(function() {
		var password = $("#password").val();
		var repeat_password = $("#repeat-password").val();
		if(password != repeat_password) {
			return;
		}
		$.ajax({
			type: "post",
			url: "/account/register.php",
			data: {
				username: $("#username").val(),
				nickname: $("#nickname").val(),
				password: $("#password").val(),
				email: $("#email").val(),
				school: $("#school").val(),
				self_page: $("#self-page").val(),
				sign: $("#sign").val(),
				vcode: $("#vcode").val()
			},
			async: false,
			success: function(data){
				$('#vcode-img').attr("src", "/account/vcode.php");
				alert(data);
			}
		});
	});
</script>
<?php require_once "../common/footer.php"; ?>