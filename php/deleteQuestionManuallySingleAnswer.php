<?php
  date_default_timezone_set('Asia/Yekaterinburg');
  include("./connect.php");

  $id=$_POST['id'];

  $stmt=$pdo->prepare("DELETE FROM `questions` WHERE `question_id`=?");
  $stmt->execute(array($id));

  $stmt=$pdo->prepare("DELETE FROM `single_question_variants` WHERE `single_question_question`=?");
  $stmt->execute(array($id));
 ?>
