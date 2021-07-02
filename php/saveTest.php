<?php
	date_default_timezone_set('Asia/Yekaterinburg');
	include("./connect.php");

	$response="Название теста изменено";


	$id=$_POST['id'];
	$newTitle=iconv("utf-8","cp1251",$_POST['new_title']);
	$newDescription=iconv("utf-8","cp1251",$_POST['new_description']);

	/*
	$id=$_GET['id'];
	$newTitle=iconv("utf-8","cp1251",$_GET['new_title']);
	*/

	$stmt=$pdo->prepare("SELECT COUNT(*) FROM `tests` WHERE `test_title`=? AND `test_id`<>?");
	$stmt->execute(array($newTitle,$id));
	$row=$stmt->fetch(PDO::FETCH_LAZY);
	if ($row["COUNT(*)"]<1)
	{
		$stmt=$pdo->prepare("UPDATE `tests` SET `test_title`=?, `test_description`=? WHERE `test_id`=?");
		$stmt->execute(array($newTitle,$newDescription,$id));
	}
	else
	{
		$response="Такой тест уже существует";
	}

	echo json_encode(array("response"=>$response));
?>
