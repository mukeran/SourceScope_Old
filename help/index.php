<?php
$this_page = 'help';
require_once "../common/header.php";
?>
<div class="container-fluid">
    <div class="row">
		<div class="col-xs-12 col-sm-8 col-sm-offset-2 col-md-10 col-md-offset-1 col-lg-6 col-lg-offset-3">
			<div role="tabpanel" class="tab-pane fade in active" id="content">
				<div class="row">
					<div class="col-md-9 col-xs-9">
						<div id="content-oj-description">
							<h1> OJ简介 </h1>
							<div class="content-oj-description-content" style="font-size: 18px;">
								&nbsp&nbsp&nbsp&nbspSSOJ倾注了我们很多的心血，这里感谢mukeran给我们带来了友好的用户交互的体验。
								<br>&nbsp&nbsp&nbsp&nbsp这个Online Judge为了广大的OIers所开发的,为了让大家在做题的时候有更好的用户体验我们使用了Bootstrap，当然也使用了其他的多方面的插件让题目描述看起来更加美观。
							</div>
						</div>
						<hr>
						<div id="content-oj-problem">
							<h1> 如何做题 </h1>
							<div class="content-oj-problem-content" style="font-size: 18px;">
								&nbsp&nbsp&nbsp&nbsp呵呵，如你所见，题目提交方式非常简单，点击问题我们可以看见简介的列表点击题目编号可以在题目现实中随意的切换标签页，这样使整个界面看上去并不十分拥挤。
								<br>
								&nbsp&nbsp&nbsp&nbsp我们使用的评测机在Windows系统下所以如果要使用long long输出时请使用cout或者%I64d我们的编译命令如下
								<table class="table table-bordered">
									<colgroup>
										<col width="10%"></col>
										<col width="90%"></col>
									</colgroup>
									<thead>
										<tr>
											<td style="text-align:center;">
												语言	
											</td>
											<td style="text-align:center;">
												命令
											</td>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td>C++</td>
											<td>g++ main.cpp -o main -w -Wall -DONLINE_JUDGE</td>
										</tr>
										<tr>
											<td>C</td>
											<td>gcc main.c -o main -w -Wall -DONLINE_JUDGE</td>
										</tr>
									</tbody>
								</table>
								在这里我们希望所有的OIer都注意一下C语言在提交的过程中如果没有使用return 0有可能会被识别成运行错误<del>毕竟返回值不是0</del>
							</div>
						</div>
						<hr>
						<div id="content-oj-contest">
							<h1> 参加比赛 </h1>
							<div class="content-oj-contest-content" style="font-size: 18px;">
								&nbsp&nbsp&nbsp&nbsp首先点击导航栏中的比赛，我们可以发现右边的开放性存在两种状态&nbsp公开&nbsp和&nbsp私有&nbsp
								<h2>公开</h2>
								&nbsp&nbsp&nbsp&nbsp对于公开的竞赛是任何人都可以进入并且提交题目的，但是对于已经结束和正在开始的比赛任何人都是无法进入的（除了比赛的管理者和代码查看者）
								<h2>私有</h2>
								&nbsp&nbsp&nbsp&nbsp以上已经提到了每一场比赛的所有人员存在四种身份：管理者、代码查看者、参与者、游客，当然在私有的模式中游客是无法进入的，在比赛没有开始或者已经结束的时候是任何人都无法进行提交题目并且参与者也是无法访问的，但是管理者和代码查看者是可以在任意时刻进入的，只有管理者可以对参与者和代码查看者的名单进行管理<del>是不是很贴心</del>
							</div>
						</div>
						<hr>
						<div id="content-oj-homework">
							<h1> 建立自己的教学团队 </h1>
							<div class="content-oj-homework-content" style="font-size: 18px;">
								&nbsp&nbsp&nbsp&nbsp打破常规模式，脱离单独的竞赛的形式，我们的OJ将为老师们提供非常便利的学生管理的机制，您可以方便的查看学生的做题进度，RANK排名以及对不同的学生布置不同的作业等等。
								<br>
								&nbsp&nbsp&nbsp&nbsp首先我们打开作业选项卡，我们可以在我的作业选项卡中轻松的查看当前的任务以及我们当前哪些任务是已经过期的，这样的作业将会显示红色的背景防止被遗忘。<br>
								&nbsp&nbsp&nbsp&nbsp在第二个选项卡布置作业中，我们可以通过添加题目的方式同时添加多个题目给多个学生，方便管理，题目只会推送给左边选择了的学生，同时我们可以通过删除按钮删除一个或多个我们错误添加的题目信息。点击每一个学生的名称会显示当前学生作业的详细信息。<br>
								&nbsp&nbsp&nbsp&nbsp在“我的关系”中，老师们可以方便的管理当前的学生信息总览以及每一个学生的RANK和作业的完成程度，方便对不同的学生采取不同的作业量。
								<br>
								&nbsp&nbsp&nbsp&对于学生来说如果要找到老师请在排名选项卡中搜索老师的姓名然后点击“老师邀请”按钮即可。
							</div>
						</div>
						<hr>
						<div id="content-oj-developer">
							<h1> 开发人员名单 </h1>
							<div class="content-oj-developer-content" style="font-size: 18px;">
								·Mukeran<br>·JeremyGJY<br>呵呵只有两人
							</div>
						</div>
						<hr>
					</div>
					<div class="col-md-3 col-xs-3" id="content-nav-container">
						<div id="content-nav">
							<ul class="nav nav-pills nav-stacked">
								<li role="presentation" id="content-nav-description"><a href="#content-oj-description">OJ简介</a></li>
								<li role="presentation" id="content-nav-problem"><a href="#content-oj-problem">如何做题</a></li>
								<li role="presentation" id="content-nav-contest"><a href="#content-oj-contest">参加比赛</a></li>
								<li role="presentation" id="content-nav-developer"><a href="#content-oj-developer">开发人员名单</a></li>
								<li role="presentation" id="content-nav-homework"><a href="#content-oj-homework">布置作业</a></li>
								<li role="presentation" id="content-nav-totop"><a href="#content-oj-description">返回顶部</a></li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
	var wid;
	function setNormalContentNav() {
		$('#content-nav').css("position", "static");
		$('#content-nav').css("margin-top", "40px");
	}
	function setScrollingContentNav() {
		$('#content-nav').css("position", "fixed");
		$('#content-nav').css("top", "20px");
		$('#content-nav').css("margin-top", "0px");
		$('#content-nav').css("width", wid + "px");
	}
	$(document).ready(function() {
		setNormalContentNav();
		wid = $("#content-nav").width();
		changeContentNavActive('#content-nav-description');
	});
	function changeContentNavActive(id) {
		$('#content-nav').find('li').removeClass('active');
		$(id).addClass('active');
	}
	function doScroll() {
		var offset = 5;
		var height = $(document).scrollTop();
		var description = $('#content-oj-description').offset().top - offset;
		var input = $('#content-oj-problem').offset().top - offset;
		var output = $('#content-oj-contest').offset().top - offset;
		var sample = $('#content-oj-developer').offset().top - offset;
		var homework = $('#content-oj-homework').offset().top - offset;
		if(height >= description)
			setScrollingContentNav();
		else
			setNormalContentNav();
		if(height < input)
			changeContentNavActive('#content-nav-description');
		else if(height >= input && height < output)
			changeContentNavActive('#content-nav-problem');
		else if(height >= output && height < sample)
			changeContentNavActive('#content-nav-contest');
		else if(height >= sample && height < homework)
			changeContentNavActive('#content-nav-developer');
		else if(height >= homework)
			changeContentNavActive('#content-nav-homework');
	};
	$(document).scroll(doScroll);
</script>
<?php require_once "../common/footer.php"; ?>