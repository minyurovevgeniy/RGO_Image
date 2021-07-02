<?php
  date_default_timezone_set('Asia/Yekaterinburg');
  include("./connect.php");

  $testToken=$_POST['test'];
  $studentToken=$_POST['student'];
  $answerId=$_POST['answerId'];
  $answerVariant=$_POST['answerVariant'];
  $questionNumber=$_POST['questionNumber']-1;

/*
  $testToken=3;
  $studentToken="R";
  $answerId=131;
  $answerVariant=12;
*/
  $stmt=$pdo->prepare("SELECT `test_id` FROM `tests` WHERE `test_token`=?");
  $stmt->execute(array(iconv("utf-8","cp1251",$testToken)));
  $row=$stmt->fetch(PDO::FETCH_LAZY);
  $test=$row['test_id'];

  $stmt=$pdo->prepare("SELECT * FROM `tests` WHERE `test_id`=?");
  $stmt->execute(array($test));
  $row=$stmt->fetch(PDO::FETCH_LAZY);
  $timeEclapsed = $row['test_start_time']+$row['test_duration'];
  //if ($row['test_start_time']+$row['test_duration']>=time())
  //{
    $stmt=$pdo->prepare("UPDATE `answers` SET `answer`=? WHERE `answer_id`=?");
    $stmt->execute(array($answerVariant,$answerId));
  //}

  $stmt=$pdo->prepare("SELECT `student` FROM `students_tokens` WHERE `student_token`=?");
  $stmt->execute(array(iconv("utf-8","cp1251",$studentToken)));
  $row=$stmt->fetch(PDO::FETCH_LAZY);
  $student=$row['student'];

  $stmt=$pdo->prepare("SELECT COUNT(*) FROM `answers` WHERE `test`=? AND `student`=?");
  $stmt->execute(array($test,$student));
  $row=$stmt->fetch(PDO::FETCH_LAZY);
  $questionCount=$row['COUNT(*)'];

  if ($questionNumber>$questionCount-1 or $questionNumber<0)
  {
    $questionNumber=0;
  }

  $currentRowLine=0;

  $stmt=$pdo->prepare("SELECT * FROM `answers` WHERE `test`=? AND `student`=?");
  $stmt->execute(array($test,$student));
  while($row=$stmt->fetch(PDO::FETCH_LAZY))
  {
    if ($currentRowLine==$questionNumber)
    {
      $answerId=$row['answer_id'];
      $questionId=$row['question'];
      break;
    }
    else
    {
      $currentRowLine++;
    }
  }

  $stmt=$pdo->prepare("SELECT * FROM `questions` WHERE `question_id`=?");
  $stmt->execute(array($questionId));
  $row=$stmt->fetch(PDO::FETCH_LAZY);

  $variantsArray=array();
  $stmtVariants=$pdo->prepare("SELECT * FROM `answer_variants` WHERE `question`=? AND `test`=? ORDER BY `variant_id` ASC");
  $stmtVariants->execute(array($questionId,$test));
  while($rowVariants=$stmtVariants->fetch(PDO::FETCH_LAZY))
  {
    $variantsArray[]=array
    (
      'variant_text'=>iconv("cp1251","utf-8",$rowVariants['variant']),
      'variant_id'=>$rowVariants['variant_id']
    );
  }

  $stmt=$pdo->prepare("SELECT `answer` FROM `answers` WHERE `answer_id`=?");
  $stmt->execute(array($answerId));
  $rowSecond=$stmt->fetch(PDO::FETCH_LAZY);
  $chosenAnswer=$rowSecond['answer'];

  $response=array
  (
    "innerShift"=>$questionNumber,
    "answerId"=>$answerId,
    "question_text"=>iconv("cp1251","utf-8",$row['question_text']),
    "variants"=>$variantsArray,
    "chosenAnswer"=>$chosenAnswer,
    "questionCount"=>$questionCount,
    "timeEclapsed"=>$timeEclapsed
  );

  echo json_encode($response);
?>
