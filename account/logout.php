<?php
session_start();
session_destroy();
$url = '/';
if(isset($_SERVER['HTTP_REFERER']))
	$url = $_SERVER['HTTP_REFERER'];
header("Location: ".$url);