<?php
	date_default_timezone_set('Asia/Yekaterinburg');
	include("./connect.php");

	$newTitle=iconv("utf-8","cp1251",$_POST['new_title']);
	$newDescription=iconv("utf-8","cp1251",$_POST['new_description']);


	$response="Тест ".$_POST['new_title']." добавлен";

	$stmt=$pdo->prepare("SELECT COUNT(*) FROM `tests` WHERE `test_title`=?");
	$stmt->execute(array($newTitle));
	$row=$stmt->fetch(PDO::FETCH_LAZY);

	include("./randomTestTokenString.php");

	$testToken=$randomString;

	if ($row["COUNT(*)"]<1)
	{
		$stmt=$pdo->prepare("INSERT INTO `tests` SET `test_title`=?, `test_token`=?, `test_description`=?");
		$stmt->execute(array($newTitle,$testToken,$newDescription));
	}
	else
	{
		$response="Такой тест уже существует";
	}

	echo json_encode(array("response"=>$response));
?>
