<?php require_once "../common/header.php"; ?>
<?php
$isError = false;
$errorMsg = '';
if(!isset($_GET['sid']))
	header('Location: /status/list.php');
$sid = $_GET['sid'];
$query = "SELECT * FROM `submission`,`problem` WHERE `submission`.`sid`='$sid' AND `problem`.`id`=`submission`.`pid` LIMIT 0, 1;";
$result = mysql_query($query, $con);
if(!mysql_num_rows($result))
	header('Location: /status/list.php');
$row = mysql_fetch_array($result);
if($row['author'] != $_SESSION['uid'] && !isAdmin()) {
	$isError = true;
	$errorMsg = "No permission";
}
$uid = $row['author'];
$query = "SELECT * FROM `user` WHERE `user`.`uid`='$uid' LIMIT 0, 1;";
$result = mysql_query($query, $con);
$user = mysql_fetch_array($result);
?>
<style type="text/css">
	.CodeMirror {
		height: auto;
	}
	.CodeMirror-scroll {
		overflow: auto;
		max-height: 1000px;
	}
</style>
<div class="container-fluid">
	<div class="col-md-10 col-md-offset-1">
		<div class="row">
			<h1 class="page-header"><?php echo '源代码 '.$row['pid']; ?> <small><?php echo $user['username']; ?></small></h1>
		</div>
		<div class="row">
			<?php if(!$isError): ?>
			<textarea id="source"><?php echo $row['code']; ?></textarea>
			<?php else: ?>
			<h4><?php echo $errorMsg; ?></h4>
			<?php endif; ?>
		</div>
	</div>
</div>
<script type="text/javascript" src="/common/css/codemirror/addon/edit/matchbrackets.js"></script>
<script type="text/javascript" src="/common/css/codemirror/mode/clike/clike.js"></script>
<script type="text/javascript">
	var cppEditor = CodeMirror.fromTextArea(document.getElementById("source"), {
		lineNumbers: true,
		lineWrapping: true,
		matchBrackets: true,
		mode: "text/x-c++src",
		readOnly: true
	});
	var mac = CodeMirror.keyMap.default == CodeMirror.keyMap.macDefault;
	CodeMirror.keyMap.default[(mac ? "Cmd" : "Ctrl") + "-Space"] = "autocomplete";
</script>
<?php require_once "../common/footer.php"; ?>