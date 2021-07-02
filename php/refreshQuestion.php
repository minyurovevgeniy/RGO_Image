	<?php
	date_default_timezone_set('Asia/Yekaterinburg');

	$questionId=$_POST['id'];
	//$questionId=$_GET['id'];

	include("./connect.php");

	$stmt=$pdo->prepare("SELECT `question_type`, `question_text` FROM `questions` WHERE `question_id`=?");
	$stmt->execute(array($questionId));

	$row=$stmt->fetch(PDO::FETCH_LAZY);
	$questionType=intval($row['question_type']);
	$questionText=$row['question_text'];

	$correctAnswer="";
	$answers="";

	if ($questionType==1)
	{
		$stmt=$pdo->prepare("SELECT `single_question_id` FROM `single_question_variants` WHERE `single_question_correct`=? AND `single_question_question`=?");
		$stmt->execute(array(1,$questionId));
		$row=$stmt->fetch(PDO::FETCH_LAZY);
		$correctAnswer=$row['single_question_id'];

		$answers=array();
		$stmt=$pdo->prepare("SELECT `single_question_image_link`, `single_question_id` FROM `single_question_variants` WHERE `single_question_question`=?");
		$stmt->execute(array($questionId));
		while($row=$stmt->fetch(PDO::FETCH_LAZY))
		{
			$answers[]=$row['single_question_id']."__".$row['single_question_image_link'];
		}
	}
	else
	{
		if ($questionType==2)
		{
			$correctAnswer=array();
			$stmt=$pdo->prepare("SELECT `multiple_question_variants_id` FROM `multiple_question_variants` WHERE `multiple_question_variants_correct`=? AND `multiple_question_variants_question`=?");
			$stmt->execute(array(1,$questionId));
			while($row=$stmt->fetch(PDO::FETCH_LAZY))
			{
				$correctAnswer[]=$row['multiple_question_variants_id'];
			}

			$answers=array();
			$stmt=$pdo->prepare("SELECT `multiple_question_variants_image_link`,`multiple_question_variants_id` FROM `multiple_question_variants` WHERE `multiple_question_variants_question`=?");
			$stmt->execute(array($questionId));
			while($row=$stmt->fetch(PDO::FETCH_LAZY))
			{
				$answers[]=$row['multiple_question_variants_id']."__".$row['multiple_question_variants_image_link'];
			}
		}
		else
		{
			if ($questionType==3)
			{
				$stmt=$pdo->prepare("SELECT * FROM `open_questions` WHERE `open_question_question`=?");
				$stmt->execute(array($questionId));
				$row=$stmt->fetch(PDO::FETCH_LAZY);
				$correctAnswer=iconv("cp1251","utf-8",$row['open_question_value']);
				$answers=$row['open_question_image_link'];
			}
		}
	}


	$stmt=$pdo->prepare("SELECT `question_type_name` FROM `question_types` WHERE `question_type_id`=?");
	$stmt->execute(array($questionType));
	$questionTypeName=$stmt->fetch(PDO::FETCH_LAZY)['question_type_name'];

	$response=array
	(
		'question_text'=>iconv("cp1251","utf-8",$questionText),
		'question_type'=>$questionType,
		'question_type_name'=>iconv("cp1251","utf-8",$questionTypeName),
		'correct_answers'=>$correctAnswer,
		'answers'=>$answers
	);

	echo json_encode(array('response'=>$response));
?>
