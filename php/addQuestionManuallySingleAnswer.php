<?php
	session_start();
	$testId=$_SESSION['currentTest'];

	date_default_timezone_set('Asia/Yekaterinburg');
	include("./connect.php");

	$question=iconv("utf-8","cp1251",$_POST['question']);
	$variantsArray=iconv("utf-8","cp1251",$_POST['variantsArray']);
	$variantCorrect=iconv("utf-8","cp1251",$_POST['variantCorrect']);
	$newQuestionType=1;

	$stmt=$pdo->prepare("SELECT COUNT(*) FROM `questions` WHERE `question_text`=? AND `question_test`=? AND `question_type`=?");
	$stmt->execute(array($question,$testId,$newQuestionType));
	$questionsCount=$stmt->fetch(PDO::FETCH_LAZY);

	$questionTextWhitoutSpaces = preg_replace("/\s{1,}/",'',$question);

	if (mb_strlen($questionTextWhitoutSpaces)<1)
	{
		echo 'Строка №'.$rowNumber.'<br>';
		echo '<a href="./loadQuestions.php">Попробовать еще раз</a><br>';
		die("Вопрос не должен быть пустым");
	}

	if($questionsCount['COUNT(*)']<1)
	{
		$stmt=$pdo->prepare("INSERT INTO `questions` SET `question_text`=?, `question_test`=?, `question_type`=?");
		$stmt->execute(array($question,$testId,$newQuestionType));
	}

	$stmt=$pdo->prepare("");

	$splitVariants = explode("_",$variantsArray);
	unset($splitVariants[count($splitVariants)-1]);
	$length=count($splitVariants)-1;

	if ($length<1)
	{
		echo "Номер строки:".$rowNumber.'<br>';
		echo '<a href="./loadQuestions.php">Попробовать еще раз</a>';
		die("Варианты ответа не должны быть пустыми");
	}
	else
	{
		for( $i=0;$i<$length;$i++)
		{
			if (mb_strlen($splitVariants[$i])<1)
			{
				echo '<a href="./loadQuestions.php">Попробовать еще раз</a>';
				die("Варианты ответа не должны быть пустыми");
			}
		}
	}

	$stmt=$pdo->prepare("SELECT * FROM `questions` WHERE `question_text`=? AND `question_test`=?");
	$stmt->execute(array($question,$testId));
	$row=$stmt->fetch(PDO::FETCH_LAZY);
	$questionId=$row['question_id'];

	foreach ($splitVariants as $value)
	{
		$stmt=$pdo->prepare("INSERT INTO `single_question_variants` SET `single_question_variant`=?, `single_question_correct`=0, `single_question_question`=?");
		$stmt->execute(array($value,$questionId));
	}

	$stmt=$pdo->prepare("UPDATE `single_question_variants` SET `single_question_correct`=1 WHERE `single_question_variant`=? AND `single_question_question`=?");
	$stmt->execute(array($variantCorrect,$questionId));

	echo json_encode(array("response"=>"Вопрос добавлен"));
?>
