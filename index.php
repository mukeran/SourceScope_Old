<?php
require_once "config.inc.php";
$this_page = 'index';
require_once __OJ_ROOT_DIR__."/common/header.php";
?>
<link href="common/css/frontpage/font-awesome.min.css" rel="stylesheet" type="text/css">
<link href="common/css/frontpage/nivo-lightbox.css" rel="stylesheet">
<link href="common/css/frontpage/animations.css" rel="stylesheet">
<link href="common/css/frontpage/style.css" rel="stylesheet">
<link href="common/css/frontpage/default.css" rel="stylesheet">
<script>
function Putit(){
	$("#search-input").slideDown(400);	
}
$(document).ready(function(){
	$("footer").css("display", "none");
	$(".row1").css("margin-top", document.body.clientHeight/3);
	$(".row2").css("margin-top", document.body.clientHeight/10);
	$("#search-input").slideUp(0);
	setTimeout("Putit()", 800);
	$("#search-input").keypress(function(e) {
       	if(e.which == 13) {
       		window.location.href = "/problem/view.php?pid=" + $("#goto-problem").val();
       	}  
   	}); 
});
</script>
<div class="container-fluid">
	<div class="row row1">
		<div class="col-lg-6 col-lg-offset-3 col-md-12 col-sm-12 col-xs-12" style="font-size: 40px;">
			<div class="Titles col-md-6 col-xs-6 col-lg-6" style="text-align:right;">
				<div class="animated fadeInDown go">Source</div>
				<div class="animated fadeInUp go">Scope</div>
			</div>
			<div class="Titles col-md-6 col-xs-6 col-lg-6" style="text-align:center;">
				<div class="animated fadeInDown go" style="font-size: 80px; text-align:left;">OJ</div>
			</div>
		</div>
	</div>
	<div class="row row2">
		<div class="col-lg-6 col-lg-offset-3 col-md-12 col-sm-12 col-xs-12" style="text-align:center;">
			<div id="search-input">
				<div class="col-md-12" style="text-align: center;">
					<input style="border: 1px solid grey;" type="text" placeholder="输入题目开始OJ之旅" id="goto-problem">
				</div>
			</div>
		</div>
	</div>
</div>

<?php require_once __OJ_ROOT_DIR__."/common/footer.php"; ?>