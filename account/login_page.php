<?php require_once "../common/header.php"; ?>
<?php
$url = '/';
if(isset($_SERVER['HTTP_REFERER']))
	$url = $_SERVER['HTTP_REFERER'];
if(isLogin())
	header("Location: ".$url);
?>

<style type="text/css">
	a:hover, a:focus {
		text-decoration: none;
	}
</style>
<div class="container-fluid">
	<div class="row">
		<div class="col-md-6 col-md-offset-3 col-xs-10 col-xs-offset-1">
			<div class="row">
				<div class="page-header">
					<h1>用户登录</h1>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6">
					<div class="row" style="margin-top: 10px;">
						<input type="text" class="form-control input-lg" id="username" placeholder="用户名" name="username" autofocus>
					</div>
					<div class="row" style="margin-top: 10px;">
						<input type="password" class="form-control input-lg" id="password" placeholder="密码" name="user_password">
					</div>
					<div class="row" style="margin-top: 10px;">
						<div class="input-group">
							<input type="text" class="form-control input-lg" placeholder="验证" aria-describedby="vcode-span" id="vcode">
							<span class="input-group-addon" id="vcode-span" style="padding: 0px; border: 0px;"><img src="/account/vcode.php" style="height: 46px; border-top-right-radius: 6px; border-bottom-right-radius: 6px;" id="vcode-img"></span>
						</div>
					</div>
					<div class="row" style="margin-top: 10px;">
						<div class="col-md-8">
							<div class="row">
								<input type="checkbox" id="remember">
								<label for="remember">记住我的登录</label>
							</div>
						</div>
						<div class="col-md-4">
							<div class="row">
								<a href="/account/forget.php" style="float: right;">忘记密码？</a>
							</div>
						</div>
					</div>
					<div class="row" style="margin-top: 10px;">
						<button class="btn btn-primary btn-lg" id="login-button" type="button" style="width: 100%;">登录</button>
					</div>
				</div>
				<div class="col-md-6" style="margin-top: 10px; text-align: center;">
					<span>还没有注册账号？<a href="/account/register_page.php">点此注册</a></span>
				</div>
			</div>
		</div>
	</div>
</div>
<style type="text/css">
	body {
		overflow-y: hidden;
	}
</style>
<script>
	$('#vcode-img').click(function() {
		$(this).attr("src", "/account/vcode.php");
	});
	$(document).ready(function(){
		$('input').iCheck({
			checkboxClass: 'icheckbox_flat-grey',
			radioClass: 'iradio_flat-grey'
		});
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
		$('#username').focus(function() {
			$('#username').popover("destroy");
			$('#password').popover("destroy");
			$('#vcode-span').popover("destroy");
		});
		$('#password').focus(function() {
			$('#username').popover("destroy");
			$('#password').popover("destroy");
			$('#vcode-span').popover("destroy");
		});
		$('#username').keypress(function(){
			$('#username').popover("destroy");
			$('#password').popover("destroy");
			$('#vcode-span').popover("destroy");
			if(event.keyCode == "13") {
				$('#login-button').click();
			}
		});
		$('#password').keypress(function(){
			$('#username').popover("destroy");
			$('#password').popover("destroy");
			$('#vcode-span').popover("destroy");
			if(event.keyCode == "13") {
				$('#login-button').click();
			}
		});
		$('#vcode').keypress(function(){
			$('#username').popover("destroy");
			$('#password').popover("destroy");
			$('#vcode-span').popover("destroy");
			if(event.keyCode == "13") {
				$('#login-button').click();
			}
		});
		$('#login-button').click(function() {
			$('#username').popover("destroy");
			$('#password').popover("destroy");
			$('#vcode-span').popover("destroy");
			if($('#username').val() == "" && $('#password').val() == "") {
				setTimeout(PopoverShow("请输入用户名和密码", "#username"), 150);
			}
			else if($('#username').val() == "") {
				setTimeout(PopoverShow("请输入用户名", "#username"), 150);
			}
			else if($('#password').val() == "") {
				setTimeout(PopoverShow("请输入密码", "#password"), 150);
			}
			else {
				$(this).text("登录中");
				$(this).attr("disabled", "disabled");
				$.ajax({
					type: "post",
					url: "/account/login.php",
					data: {
						username: $("#username").val(),
						password: $("#password").val(),
						vcode: $("#vcode").val()
					},
					async: true,
					success: function(data){
						if(data == "Username or password not match")
							setTimeout(PopoverShow("用户名或密码错误", "#username"), 150);
						else if(data == "success")
							window.location.href = "<?php echo $url; ?>";
						else if(data == "No username and password")
							setTimeout(PopoverShow("请输入用户名和密码", "#username"), 150);
						else if(data == "No username")
							setTimeout(PopoverShow("请输入用户名", "#username"), 150);
						else if(data == "No password")
							setTimeout(PopoverShow("请输入密码", "#password"), 150);
						else if(data == "Vcode wrong")
							setTimeout(PopoverShow("验证码错误", "#vcode-span"), 150);
						$('#login-button').blur();
						$("#password").val("");
						$("#vcode").val("");
						$('#login-button').text('登录');
						$('#login-button').removeAttr('disabled');
						$('#vcode-img').attr("src", "/account/vcode.php");
					}
				});
			}
		});
	});
</script>
<?php require_once "../common/footer.php"; ?>