<?php
date_default_timezone_set('Asia/Yekaterinburg');
	$studentToken=$_POST['studentToken'];
	$testToken=$_POST['testToken'];

	include("./connect.php");

	$stmt=$pdo->prepare("SELECT `student_id` FROM `students` WHERE `student_token`=?");
	$stmt->execute(array($studentToken));
	$row=$stmt->fetch(PDO::FETCH_LAZY);
	$studentId=$row['student_id'];

	$stmt=$pdo->prepare("SELECT `test_id`,`test_title` FROM `tests` WHERE `test_token`=?");
	$stmt->execute(array($testToken));
	$row=$stmt->fetch(PDO::FETCH_LAZY);
	$testId=$row['test_id'];
	$testTitle=iconv("cp1251","utf-8",$row['test_title']);

	$questions=array();
	$stmt=$pdo->prepare("SELECT `question_id` FROM `questions` WHERE `question_test`=?");
	$stmt->execute(array($testId));
	while($row=$stmt->fetch(PDO::FETCH_LAZY))
	{
		$questions[]=$row['question_id'];
	}

	$questionsCount=0;
	$stmt=$pdo->prepare("SELECT COUNT(*) FROM `questions` WHERE `question_test`=?");
	$stmt->execute(array($testId));
	$row=$stmt->fetch(PDO::FETCH_LAZY);
	$questionsCount=$row['COUNT(*)'];


	$rightAnswersCount=0;

	$wrongQuestions=array();
	$wrongQuestionCounter=0;

	foreach($questions as $id)
	{
		$wrongQuestionCounter++;
		$stmt=$pdo->prepare("SELECT `question_type` FROM `questions` WHERE `question_id`=?");
		$stmt->execute(array($id));
		$row=$stmt->fetch(PDO::FETCH_LAZY);
		$questionType=$row['question_type'];

		if ($questionType==1)
		{
			$stmt=$pdo->prepare("SELECT `single_question_answers_answer` FROM `single_question_answers` WHERE `single_question_answers_question`=? AND `single_question_answers_student`=?");
			$stmt->execute(array($id,$studentId));
			$row=$stmt->fetch(PDO::FETCH_LAZY);
			$answer=$row['single_question_answers_answer'];

			$stmt=$pdo->prepare("SELECT `single_question_id` FROM `single_question_variants` WHERE `single_question_question`=? AND `single_question_correct`=?");
			$stmt->execute(array($id,1));
			$row=$stmt->fetch(PDO::FETCH_LAZY);
			$rightAnswer=$row['single_question_id'];

			if ($answer==$rightAnswer)
			{
				$rightAnswersCount++;
			}
			else
			{
				$wrongQuestions[]=$wrongQuestionCounter;
			}
		}
		else
		{
			if ($questionType==2)
			{
				$answers=array();
				$stmt=$pdo->prepare("SELECT `multiple_question_answers_answer` FROM `multiple_question_answers` WHERE `multiple_question_answers_question`=? AND `multiple_question_answers_student`=?");
				$stmt->execute(array($id,$studentId));
				while($row=$stmt->fetch(PDO::FETCH_LAZY))
				{
					$answers[]=$row['multiple_question_answers_answer'];
				}
				sort($answers);

				$rightAnswers=array();
				$stmt=$pdo->prepare("SELECT `multiple_question_variants_id` FROM `multiple_question_variants` WHERE `multiple_question_variants_question`=? AND `multiple_question_variants_correct`=?");
				$stmt->execute(array($id,1));
				while($row=$stmt->fetch(PDO::FETCH_LAZY))
				{
					$rightAnswers[]=$row['multiple_question_variants_id'];
				}
				sort($rightAnswers);

				if ($answers==$rightAnswers)
				{
					$rightAnswersCount++;
				}
				else
				{
					$wrongQuestions[]=$wrongQuestionCounter;
				}
			}
			else
			{
				$answer="";
				$stmt=$pdo->prepare("SELECT `open_questions_answers_answer` FROM `open_questions_answers` WHERE `open_questions_answers_question`=? AND `open_questions_answers_student`=?");
				$stmt->execute(array($id,$student));
				$row=$stmt->fetch(PDO::FETCH_LAZY);
				$answer=$row['open_questions_answers_answer'];

				$rightAnswer="";
				$stmt=$pdo->prepare("SELECT `open_question_value` FROM `open_questions` WHERE `open_question_question`=?");
				$stmt->execute(array($id));
				$row=$stmt->fetch(PDO::FETCH_LAZY);
				$rightAnswer=$row['open_question_value'];

				if ($answers==$rightAnswers and $answer != "")
				{
					$rightAnswersCount++;
				}
				else
				{
					$wrongQuestions[]=$wrongQuestionCounter;
				}
			}
		}
	}


	$wrongQuestionsString=implode(", ",$wrongQuestions);

	$result=
	array
	(
		"rightAnswersCount"=>$rightAnswersCount,
		'questionsCount'=>$questionsCount,
		'testTitle'=>$testTitle,
		'wrong_questions'=>$wrongQuestionsString
	);

	echo json_encode($result);
?>
