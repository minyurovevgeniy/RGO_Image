<?php
	date_default_timezone_set('Asia/Yekaterinburg');

	$testId=$_POST['id'];
	include("./connect.php");

	$questionIds=array();
	$stmt=$pdo->prepare("SELECT * FROM `questions` WHERE `question_test`=?");
	$stmt->execute(array($testId));
	while($row=$stmt->fetch(PDO::FETCH_LAZY))
	{
		$questionIds[]=$row['question_id'];
	}

	foreach($questionIds as $question)
	{
		$stmt=$pdo->prepare("DELETE FROM `single_question_variants` WHERE `single_question_question`=?");
		$stmt->execute(array($question));

		$stmt=$pdo->prepare("DELETE FROM `single_question_answers` WHERE `single_question_question`=?");
		$stmt->execute(array($question));

		$stmt=$pdo->prepare("DELETE FROM `multiple_question_variants` WHERE `multiple_question_variants_question`=?");
		$stmt->execute(array($question));

		$stmt=$pdo->prepare("DELETE FROM `multiple_question_answers` WHERE `multiple_question_answers_question`=?");
		$stmt->execute(array($question));

		$stmt=$pdo->prepare("DELETE FROM `open_questions` WHERE `open_question_question`=?");
		$stmt->execute(array($question));

		$stmt=$pdo->prepare("DELETE FROM `open_questions_answers` WHERE `open_questions_answers_question`=?");
		$stmt->execute(array($question));

		$stmt=$pdo->prepare("DELETE FROM `questions` WHERE `question_id`=?");
		$stmt->execute(array($question));
	}

	$stmt=$pdo->prepare("DELETE FROM `tests` WHERE `test_id`=?");
	$stmt->execute(array($testId));

	echo json_encode(array("ok"=>"ok"));
?>
