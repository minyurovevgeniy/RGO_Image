<?php
	session_start();
	date_default_timezone_set('Asia/Yekaterinburg');
	//if ($_SESSION['mdf843hrk52']<=0 or !isset($_SESSION['mdf843hrk52'])) die("OK");
	$test=$_SESSION['currentTest'];	
	
	$response="invisible";
	
	include("./connect.php");
	$stmt=$pdo->prepare("SELECT * FROM `tests` WHERE `test_id`=?");
	$stmt->execute(array($test));
	$row=$stmt->fetch(PDO::FETCH_LAZY);
	if (time()>$row['test_duration']+$row['test_start_time'])
	{
		$response="visible";
	}
	
	echo json_encode(array("state"=>$response));
?>