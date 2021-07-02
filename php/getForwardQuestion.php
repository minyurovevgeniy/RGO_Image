<?php
  date_default_timezone_set('Asia/Yekaterinburg');
  include("./connect.php");

/*
  $testToken=3;
  $studentToken="R";
  $answer=1;
  $questionType=1;
  $currentQuestionOrder=1;
*/

  $testToken=$_POST['test'];
  $studentToken=$_POST['student'];
  $answer=$_POST['student_answer'];
  $questionType=intval($_POST['question_type']);
  $currentQuestionOrder=intval($_POST['current_question']);
  $currentQuestionId=0;


  $stmt=$pdo->prepare("SELECT `test_id` FROM `tests` WHERE `test_token`=?");
  $stmt->execute(array(iconv("utf-8","cp1251",$testToken)));
  $row=$stmt->fetch(PDO::FETCH_LAZY);
  $test=$row['test_id'];

  $stmt=$pdo->prepare("SELECT `student_id` FROM `students` WHERE `student_token`=?");
  $stmt->execute(array(iconv("utf-8","cp1251",$studentToken)));
  $row=$stmt->fetch(PDO::FETCH_LAZY);
  $student=$row['student_id'];

  $questionOrder=1;

  $stmt=$pdo->prepare("SELECT * FROM `questions` WHERE `question_test`=? ORDER BY `question_id` ASC");
  $stmt->execute(array($test));
  while($row=$stmt->fetch(PDO::FETCH_LAZY))
  {
    if ($currentQuestionOrder==$questionOrder)
    {
      $currentQuestionId=$row['question_id'];
      break;
    }
    else
    {
      $questionOrder++;
    }

  }

  if ($questionType==1)
  {
    $stmt=$pdo->prepare("DELETE FROM `single_question_answers` WHERE `single_question_answers_question`=? AND `single_question_answers_student`=?");
    $stmt->execute(array($currentQuestionId,$student));

    $stmt=$pdo->prepare("INSERT INTO `single_question_answers` SET `single_question_answers_question`=?, `single_question_answers_student`=?, `single_question_answers_answer`=?");
    $stmt->execute(array($currentQuestionId,$student,$answer));
  }
  else
  {
    if ($questionType==2)
    {
      $stmt=$pdo->prepare("DELETE FROM `multiple_question_answers` WHERE `multiple_question_answers_question`=? AND `multiple_question_answers_student`=?");
      $stmt->execute(array($currentQuestionId,$student));

      $multipleAnswers=explode("_",$answer);
      $answersCount=count($multipleAnswers);
      unset($multipleAnswers[$answersCount-1]);

      foreach($multipleAnswers as $value)
      {
        $stmt=$pdo->prepare("INSERT INTO `multiple_question_answers` SET `multiple_question_answers_question`=?, `multiple_question_answers_student`=?, `multiple_question_answers_answer`=?");
        $stmt->execute(array($currentQuestionId,$student,$value));
      }
    }
    else
    {
      $stmt=$pdo->prepare("DELETE FROM `open_questions_answers` WHERE `open_questions_answers_question`=? AND `open_questions_answers_student`=?");
      $stmt->execute(array($currentQuestionId,$student));

      $stmt=$pdo->prepare("INSERT INTO `open_questions_answers` SET  `open_questions_answers_question`=?, `open_questions_answers_student`=?, `open_questions_answers_answer`=?");
      $stmt->execute(array($currentQuestionId,$student,iconv("utf-8","cp1251",$answer)));
    }
  }
/*----------------------------------------------------------------------------------------------------------------------*/
// get variants
  $stmt=$pdo->prepare("SELECT COUNT(*) FROM `questions` WHERE `question_test`=?");
  $stmt->execute(array($test));
  $row=$stmt->fetch(PDO::FETCH_LAZY);
  $questionsCount=$row['COUNT(*)'];

  $questionOrder=1;
  $currentQuestionOrder++;
  if ($currentQuestionOrder>$questionsCount)
  {
    $currentQuestionOrder=1;
  }

  $stmt=$pdo->prepare("SELECT * FROM `questions` WHERE `question_test`=?");
  $stmt->execute(array($test));
  while($row=$stmt->fetch(PDO::FETCH_LAZY))
  {
    if ($currentQuestionOrder==$questionOrder)
    {
      $nextQuestionId=$row['question_id'];
      break;
    }
    else
    {
      $questionOrder++;
    }
  }


  $stmt=$pdo->prepare("SELECT * FROM `questions` WHERE `question_id`=?");
  $stmt->execute(array($nextQuestionId));
  $row=$stmt->fetch(PDO::FETCH_LAZY);
  $nextQuestionText=$row['question_text'];
  $nextQuestionType=intval($row['question_type']);

  $variants=array();

  if ($nextQuestionType==1)
  {
    $variants=array();
    $stmt=$pdo->prepare("SELECT * FROM `single_question_variants` WHERE `single_question_question`=?");
    $stmt->execute(array($nextQuestionId));
    while ($row=$stmt->fetch(PDO::FETCH_LAZY))
    {
      $variants[]=$row['single_question_id']."__".$row['single_question_image_link'];
    }
  }
  else
  {
    if ($nextQuestionType==2)
    {
      $variants=array();
      $stmt=$pdo->prepare("SELECT * FROM `multiple_question_variants` WHERE `multiple_question_variants_question`=?");
      $stmt->execute(array($nextQuestionId));
      while($row=$stmt->fetch(PDO::FETCH_LAZY))
      {
        $variants[]=$row['multiple_question_variants_id']."__".$row['multiple_question_variants_image_link'];
      }

    }
    else
    {
      $stmt=$pdo->prepare("SELECT `open_question_image_link` FROM `open_questions` WHERE `open_question_question`=?");
      $stmt->execute(array($nextQuestionId));
      $row=$stmt->fetch(PDO::FETCH_LAZY);
      $variants=$row['open_question_image_link'];
    }
  }
/*----------------------------------------------------------------------------------------------------------------------*/
// get answers
  $nextAnswers=array();
  $questionTypeText="";

  if ($nextQuestionType==1)
  {
    $nextQuestionTypeText = "Один ответ";
    $stmt=$pdo->prepare("SELECT * FROM `single_question_answers` WHERE `single_question_answers_question`=? AND `single_question_answers_student`=?");
    $stmt->execute(array($nextQuestionId,$student));
    while($row=$stmt->fetch(PDO::FETCH_LAZY))
    if ($row['single_question_answers_id']>0)
    {
      $nextAnswers[]=$row['single_question_answers_answer'];
    }
  }
  else
  {
    if ($nextQuestionType==2)
    {
      $nextQuestionTypeText = "Несколько вариантов";
      $nextAnswers=array();
      $stmt=$pdo->prepare("SELECT * FROM `multiple_question_answers` WHERE `multiple_question_answers_question`=? AND `multiple_question_answers_student`=?");
      $stmt->execute(array($nextQuestionId,$student));
      while($row=$stmt->fetch(PDO::FETCH_LAZY))
      {
        $nextAnswers[]=$row['multiple_question_answers_answer'];
      }
    }
    else
    {
      $nextQuestionTypeText = "Свободный ответ";
      $stmt=$pdo->prepare("SELECT * FROM `open_questions_answers` WHERE `open_questions_answers_question`=? AND `open_questions_answers_student`=?");
      $stmt->execute(array($nextQuestionId,$student));
      $row=$stmt->fetch(PDO::FETCH_LAZY);
      $nextAnswers=iconv("cp1251","utf-8",$row['open_questions_answers_answer']);
    }
  }


  $response=array
  (
    "question_text"=>iconv("cp1251","utf-8",$nextQuestionText),
    "question_order"=>$currentQuestionOrder,
    "question_type"=>$nextQuestionType,
    "question_type_text"=>$nextQuestionTypeText,
    "answer"=>$nextAnswers,
    "variants"=>$variants
  );

  echo json_encode($response);
?>
