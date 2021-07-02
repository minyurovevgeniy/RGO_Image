<?php
	session_start();
	date_default_timezone_set('Asia/Yekaterinburg');
	include("./connect.php");


	date_default_timezone_set('Asia/Yekaterinburg');


	$stmt=$pdo->prepare("SELECT `test_title` FROM `tests` WHERE `test_id`=?");
	$stmt->execute(array($_SESSION['currentTest']));
	$row=$stmt->fetch(PDO::FETCH_LAZY);



	echo json_encode(array("title"=>iconv("cp1251","utf-8",$row['test_title'])));
?>
