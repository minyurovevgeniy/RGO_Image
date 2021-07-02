<?php
	session_start();
	$_SESSION['login']="true";
	
	date_default_timezone_set('Asia/Yekaterinburg');
	
	$login=$_POST['login'];
	$password=$_POST['password'];
	
	
	if ($login=="uspu" and $password=="uspu")
	{
		echo json_encode(array("ok"=>"ok"));
	}
	else
	{
		die("error");
	}
		
?>