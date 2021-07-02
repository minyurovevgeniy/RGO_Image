<?php
	date_default_timezone_set('Asia/Yekaterinburg');

	$questionId=$_POST['questionId'];
	$correctAnswers=$_POST['correctAnswers'];
	$correctAnswersArray=explode("_",$correctAnswers);

	include("./connect.php");

	$stmt=$pdo->prepare("SELECT * FROM `questions` WHERE `question_id`=?");
	$stmt->execute(array($questionId));
	$row=$stmt->fetch(PDO::FETCH_LAZY);
	$questionType=$row['question_type'];

	if ($questionType==1)
	{
		$stmt=$pdo->prepare("UPDATE `single_question_variants` SET `single_question_correct`=? WHERE `single_question_question`=?");
		$stmt->execute(array(0,$questionId));

		foreach($correctAnswersArray as $key)
		{
			$stmt=$pdo->prepare("UPDATE `single_question_variants` SET `single_question_correct`=? WHERE `single_question_question`=? AND `single_question_id`=?");
			$stmt->execute(array(1,$questionId,$key));
		}
	}
	else
	{
		if ($questionType==2)
		{
			$stmt=$pdo->prepare("UPDATE `multiple_question_variants` SET `multiple_question_variants_correct`=? WHERE `multiple_question_variants_question`=?");
			$stmt->execute(array(0,$questionId));

			foreach($correctAnswersArray as $key)
			{
				$stmt=$pdo->prepare("UPDATE `multiple_question_variants` SET `multiple_question_variants_correct`=? WHERE `multiple_question_variants_question`=? AND `multiple_question_variants_id`=?");
				$stmt->execute(array(1,$questionId,$key));
			}
		}
	}

	echo json_encode(array("ok"=>"ok"));
?>
