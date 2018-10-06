<?php
session_start();
require_once "vcodeClass.php";
$vcode = new Vcode();
$vcode -> doimg();
$_SESSION['vcode'] = strtolower($vcode -> getCode());