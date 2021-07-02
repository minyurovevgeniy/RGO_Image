<?php
	session_start();
	$testId=$_SESSION['currentTest'];

	date_default_timezone_set('Asia/Yekaterinburg');
	include("./connect.php");

	$response="Вопрос изменен";

	$id=$_POST['id'];
	$newQuestionText=iconv("utf-8","cp1251",$_POST['question']);
	$newQuestionVariants=iconv("utf-8","cp1251",$_POST['variantsArray']);
	$newCorrectVariants=iconv("utf-8","cp1251",$_POST['variantCorrect']);
	$newQuestionType=2;

/*
	$stmt=$pdo->prepare("SELECT COUNT(*) FROM `questions` WHERE `question_text`=? AND `question_test`=? AND `question_type`=?");
	$stmt->execute(array($question,$testId,$newQuestionType));
	$questionsCount=$stmt->fetch(PDO::FETCH_LAZY);
*/


	$splitCorrectVariants=explode("_",$newCorrectVariants);

	$questionTextWhitoutSpaces = preg_replace("/\s{1,}/",'',$newQuestionText);

	if (mb_strlen($questionTextWhitoutSpaces)<1)
	{
		echo 'Строка №'.$rowNumber.'<br>';
		echo '<a href="./loadQuestions.php">Попробовать еще раз</a><br>';
		die("Вопрос не должен быть пустым");
	}


	$stmt=$pdo->prepare("UPDATE `questions` SET `question_text`=?, `question_test`=?, `question_type`=? WHERE `question_id`=?");
	$stmt->execute(array($newQuestionText,$testId,$newQuestionType,$id));

	$splitVariants = explode("_",$newQuestionVariants);

	$length=count($splitVariants);
	unset($splitVariants[$length-1]);
	if ($length<2)
	{
		echo "Номер строки:".$rowNumber.'<br>';
		echo '<a href="./loadQuestions.php">Попробовать еще раз</a>';
		die("Варианты ответа не должны быть пустыми");
	}
	else
	{
		for( $i=0;$i<$length-1;$i++)
		{
			if (mb_strlen($splitVariants[$i])<1)
			{
				echo '<a href="./loadQuestions.php">Попробовать еще раз</a>';
				die("Варианты ответа не должны быть пустыми");
			}
		}
	}


	$stmt=$pdo->prepare("SELECT * FROM `questions` WHERE `question_text`=? AND `question_test`=?");
	$stmt->execute(array($newQuestionText,$testId));
	$row=$stmt->fetch(PDO::FETCH_LAZY);
	$questionId=$row['question_id'];

	$stmt=$pdo->prepare("DELETE FROM `multiple_question_variants` WHERE `multiple_question_variants_question`=?");
	$stmt->execute(array($id));

	foreach($splitVariants as $value)
	{
		$stmt=$pdo->prepare("INSERT INTO `multiple_question_variants` SET `multiple_question_variants_correct`=?, `multiple_question_variants_variant`=?, `multiple_question_variants_question`=?");
		$stmt->execute(array(0,$value,$id));
	}

	foreach($splitCorrectVariants as $value)
	{
		$stmt=$pdo->prepare("UPDATE `multiple_question_variants` SET `multiple_question_variants_correct`=? WHERE `multiple_question_variants_question`=? AND `multiple_question_variants_variant`=?");
		$stmt->execute(array(1,$id,$value));
	}


	echo json_encode(array("response"=>$response));
?>
