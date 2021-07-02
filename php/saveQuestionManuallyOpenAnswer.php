<?php
  date_default_timezone_set('Asia/Yekaterinburg');
  include("./connect.php");

  $id=$_POST['id'];
  $question=iconv("utf-8","cp1251",$_POST['question']);
  $correctValue=iconv("utf-8","cp1251",$_POST['openQuestionCorrect']);

/*
$id=$_GET['id'];
$question=iconv("utf-8","cp1251",$_GET['question']);
$correctValue=iconv("utf-8","cp1251",$_GET['openQuestionCorrect']);
*/
  $stmt=$pdo->prepare("UPDATE `open_questions` SET `open_question_value`=?, `open_question_question`=? WHERE `open_question_id`=?");
  $stmt->execute(array($correctValue,$question,$id));

  echo json_encode(array("ok"=>"ok"));
?>
