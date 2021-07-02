<?php
	session_start();
	date_default_timezone_set('Asia/Yekaterinburg');
	include("./connect.php");
	
	$groups=array();
	$response=array();
	
	$test=$_SESSION['currentTest'];
	$stmt=$pdo->prepare("SELECT * FROM `tests` WHERE `test_id`=?");
	$stmt->execute(array($test));
	while($row=$stmt->fetch(PDO::FETCH_LAZY))
	{
		$response['test_token']=array
		(		
			'token'=>iconv("cp1251","utf-8",$row['test_token'])
		);
	}
	
	echo json_encode($response);
?>