<?php
  date_default_timezone_set('Asia/Yekaterinburg');
  incude("./connect.php");

  $questionId=$_POST['id'];

  $stmt=$pdo->prepare("DELETE FROM `questions` WHERE `question_id`=?");
  $stmt->execute(array($questionId));

  $stmt=$pdo->prepare("DELETE FROM `multiple_question_variants` WHERE `multiple_question_variants_question`=?");
  $stmt->execute(array($questionId));
?>
