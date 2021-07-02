<?php
  date_default_timezone_set('Asia/Yekaterinburg');
  $questionId=$_POST['questionId'];
  $openQuestionCorrect=iconv("utf-8","cp1251",$_POST['openQuestionCorrect']);


  include("./connect.php");

  $stmt=$pdo->prepare("UPDATE `open_questions` SET `open_question_value`=? WHERE `open_question_question`=?");
  $stmt->execute(array($openQuestionCorrect,$questionId));

  echo json_encode(array("ok"=>"ok"));
?>
