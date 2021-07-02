<?php
	session_start();
	date_default_timezone_set('Asia/Yekaterinburg');
	include("./connect.php");

	$_SESSION['currentTest']=$_POST['test'];
?>