<?php
	session_start();
	date_default_timezone_set('Asia/Yekaterinburg');
	$test=$_SESSION['currentTest'];

	include("./connect.php");
	
	$stmt=$pdo->prepare("SELECT `tokens_file_path` FROM `reports_and_tokens_files` WHERE `test`=?");
	$stmt->execute(array($test));
	$row=$stmt->fetch(PDO::FETCH_LAZY);
	
	echo json_encode(array("tokens_file_path"=>$row['tokens_file_path']));
	
?>