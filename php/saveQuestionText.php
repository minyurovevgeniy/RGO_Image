<?php
	date_default_timezone_set('Asia/Yekaterinburg');
	include("./connect.php");

	$response=" Текст вопроса изменен";

	$id=$_POST['questionId'];
	$newQuestionText=iconv("utf-8","cp1251",$_POST['newQuestionText']);

	/*
	$id=$_GET['id'];
	$newTitle=iconv("utf-8","cp1251",$_GET['new_title']);
	*/

	$stmt=$pdo->prepare("SELECT COUNT(*) FROM `questions` WHERE `question_text`=? AND `question_id`<>?");
	$stmt->execute(array($newQuestionText,$id));
	$row=$stmt->fetch(PDO::FETCH_LAZY);
	if ($row['COUNT(*)']<1)
	{
		$stmt=$pdo->prepare("UPDATE `questions` SET `question_text`=? WHERE `question_id`=?");
		$stmt->execute(array($newQuestionText,$id));
	}
	else
	{
		$response="Такой вопрос уже существует";
	}

	echo json_encode(array("response"=>$response));
?>
