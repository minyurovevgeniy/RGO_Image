<?php
	session_start();
	date_default_timezone_set('Asia/Yekaterinburg');
	$test=$_SESSION['currentTest'];
	
	include("./randomTestTokenString.php");
	
	$testToken=$randomString;
	
	include("./connect.php");
	
	$stmt=$pdo->prepare("UPDATE `tests` SET `test_token`=? WHERE `test_id`=?");
	$stmt->execute(array(iconv("utf-8","cp1251",$testToken),$test));
	
	echo json_encode(array('token'=>$testToken));
?>