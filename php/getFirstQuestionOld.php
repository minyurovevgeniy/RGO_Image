<?php
date_default_timezone_set('Asia/Yekaterinburg');
  include("./connect.php");

  $studentToken=iconv("utf-8","cp1251",$_POST['studentToken']);
  $testToken=iconv("utf-8","cp1251",$_POST['testToken']);

  //$studentToken=iconv("utf-8","cp1251","6kLickal8r");
  //$testToken=iconv("utf-8","cp1251","3");

  $stmt=$pdo->prepare("SELECT * FROM `students` WHERE `student_token`=?");
  $stmt->execute(array($studentToken));
  $row=$stmt->fetch(PDO::FETCH_LAZY);
  $student=$row['student_id'];

  $stmt=$pdo->prepare("SELECT `test_id` FROM `tests` WHERE `test_token`=?");
  $stmt->execute(array($testToken));
  $row=$stmt->fetch(PDO::FETCH_LAZY);
  $test=$row['test_id'];


  $stmt=$pdo->prepare("SELECT * FROM `questions` WHERE `question_test`=? ORDER BY `question_id` ASC LIMIT 1");
  $stmt->execute(array($test));
  $row=$stmt->fetch(PDO::FETCH_LAZY);
  $questionText=iconv("cp1251","utf-8",$row['question_text']);
  $questionId=intval($row['question_id']);

  $stmt=$pdo->prepare("SELECT * FROM `questions` WHERE `question_id`=?");
  $stmt->execute(array($questionId));
  $row=$stmt->fetch(PDO::FETCH_LAZY);
  $questionType=$row['question_type'];

  $variants=array();

  if ($questionType==1)
  {
    $stmt=$pdo->prepare("SELECT * FROM `single_question_variants` WHERE `single_question_question`=?");
    $stmt->execute(array($questionId));
    while ($row=$stmt->fetch(PDO::FETCH_LAZY))
    {
      $variants[]=$row['single_question_id']."__".$row['single_question_image_link'];
    }
  }
  else
  {
    if ($questionType==2)
    {
      $stmt=$pdo->prepare("SELECT * FROM `multiple_question_variants` WHERE `multiple_question_variants_question`=?");
      $stmt->execute(array($questionId));
      while($row=$stmt->fetch(PDO::FETCH_LAZY))
      {
        $variants[]=$row['multiple_question_variants_id']."__".$row['multiple_question_variants_image_link'];
      }
    }
    else
    {
      $stmt=$pdo->prepare("SELECT `open_question_image_link` FROM `open_questions` WHERE `open_question_question`=?");
      $stmt->execute(array($questionId));
      $row=$stmt->fetch(PDO::FETCH_LAZY);
      $variants=$row['open_question_image_link'];
    }
  }

  //================================================================================================================
  // get answers
    if ($questionType==1)
    {
      $answers=array();
      $nextQuestionTypeText = "Один ответ";
      $stmt=$pdo->prepare("SELECT * FROM `single_question_answers` WHERE `single_question_answers_question`=? AND `single_question_answers_student`=?");
      $stmt->execute(array($questionId,$student));
      while($row=$stmt->fetch(PDO::FETCH_LAZY))
	  {
		  if ($row['single_question_answers_id']>0)
		  {
			$answers[]=$row['single_question_answers_answer'];
		  }
	  }
    }
    else
    {
      if ($questionType==2)
      {
        $answers=array();
        $nextQuestionTypeText = "Несколько вариантов";
        $stmt=$pdo->prepare("SELECT * FROM `multiple_question_answers` WHERE `multiple_question_answers_question`=? AND `multiple_question_answers_student`=?");
        $stmt->execute(array($questionId,$student));
        while($row=$stmt->fetch(PDO::FETCH_LAZY))
        {
          $answers[]=$row['multiple_question_answers_answer'];
        }
      }
      else
      {
        $questionType = "Свободный ответ";
        $stmt=$pdo->prepare("SELECT * FROM `open_questions_answers` WHERE `open_questions_answers_question`=? AND `open_questions_answers_student`=?");
        $stmt->execute(array($questionId,$student));
        $row=$stmt->fetch(PDO::FETCH_LAZY);
        $answers="";
        $answers=iconv("cp1251","utf-8",$row['open_questions_answers_answer']);
      }
    }

  $response=array
  (
    "question_text"=>$questionText,
    "question_id"=>$questionId,
    "question_type"=>intval($questionType),
    "variants"=>$variants,
    "answer"=>$answers
  );

  echo json_encode($response);
?>
