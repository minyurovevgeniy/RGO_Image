<?php
	session_start();
	$testId=$_SESSION['currentTest'];

	date_default_timezone_set('Asia/Yekaterinburg');
	include("./connect.php");

  $id=$_POST['id'];
	$question=iconv("utf-8","cp1251",$_POST['question']);
	$variantsArray=iconv("utf-8","cp1251",$_POST['variantsArray']);
	$variantCorrect=iconv("utf-8","cp1251",$_POST['variantCorrect']);
	$newQuestionType=1;

	$questionTextWhitoutSpaces = preg_replace("/\s{1,}/",'',$question);

	if (mb_strlen($questionTextWhitoutSpaces)<1)
	{
		echo 'Строка №'.$rowNumber.'<br>';
		echo '<a href="./loadQuestions.php">Попробовать еще раз</a><br>';
		die("Вопрос не должен быть пустым");
	}

		$stmt=$pdo->prepare("UPDATE `questions` SET `question_text`=?, `question_test`=?, `question_type`=? WHERE `question_id`=?");
		$stmt->execute(array($question,$testId,$newQuestionType,$id));

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

  $stmt=$pdo->prepare("DELETE FROM `single_question_variants` WHERE `single_question_question`=?");
  $stmt->execute(array($id));

	foreach ($splitVariants as $value)
	{
		$stmt=$pdo->prepare("INSERT INTO `single_question_variants` SET `single_question_variant`=?, `single_question_correct`=0, `single_question_question`=?");
		$stmt->execute(array($value,$id));
	}

	$stmt=$pdo->prepare("UPDATE `single_question_variants` SET `single_question_correct`=1 WHERE `single_question_variant`=? AND `single_question_question`=?");
	$stmt->execute(array($variantCorrect,$id));

	echo json_encode(array("response"=>"Вопрос изменен"));
?>
