<?php
	session_start();
	$testId=$_SESSION['currentTest'];

	date_default_timezone_set('Asia/Yekaterinburg');
	include("./connect.php");

	$response="Вопрос добавлен";

	$newQuestionText=iconv("utf-8","cp1251",$_POST['question']);
	$newQuestionAnswer=iconv("utf-8","cp1251",$_POST['openQuestionCorrect']);


/*
	$newQuestionText=iconv("utf-8","cp1251",$_GET['question']);
	$newQuestionAnswer=iconv("utf-8","cp1251",$_GET['openQuestionCorrect']);
*/

	//$questionTextWhitoutSpaces = preg_replace("/\s{1,}/",'',$newQuestionText);

	if (strlen($newQuestionAnswer)<1)
	{
		die("Вопрос не должен быть пустым");
	}

	$stmt=$pdo->prepare("SELECT COUNT(*) FROM `questions` WHERE `question_text`=?");
	$stmt->execute(array($newQuestionText));
	$questionsCount=$stmt->fetch(PDO::FETCH_LAZY);

	if($questionsCount['COUNT(*)']<1)
	{
		$stmt=$pdo->prepare("INSERT INTO `questions` SET `question_text`=?, `question_test`=?, `question_type`=?");
		$stmt->execute(array($newQuestionText,$testId,3));
	}
	else
	{
		die("Вопрос уже существует");
	}

	$stmt=$pdo->prepare("SELECT * FROM `questions` WHERE `question_text`=? AND `question_test`=?");
	$stmt->execute(array($newQuestionText,$testId));
	$row=$stmt->fetch(PDO::FETCH_LAZY);
	$questionId=$row['question_id'];

	$stmt=$pdo->prepare("SELECT * FROM `open_questions` WHERE `open_question_value`=? AND `open_question_question`=?");
	$stmt->execute(array($newQuestionAnswer,$questionId));
	$row=$stmt->fetch(PDO::FETCH_LAZY);

	if ($row['open_question_id']<1)
	{
		$stmt=$pdo->prepare("INSERT INTO `open_questions` SET `open_question_value`=?, `open_question_question`=?");
		$stmt->execute(array($newQuestionAnswer,$questionId));
	}

	echo json_encode(array("response"=>$response));
?>
