<?php
	session_start();
	date_default_timezone_set('Asia/Yekaterinburg');
	
	$_SESSION['currentTest']=$_POST['test'];
	echo json_encode(array("ok"=>"ok"));
?>