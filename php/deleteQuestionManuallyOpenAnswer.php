<?php
  date_default_timezone_set('Asia/Yekaterinburg');
  include("./connect.php");

  $id=$_POST['id'];


  $stmt=$pdo->prepare("DELETE FROM `questions` WHERE `question_id`=?");
  $stmt->execute(array($id));

  $stmt=$pdo->prepare("DELETE FROM `open_questions` WHERE `open_question_id`=?");
  $stmt->execute(array($id));

  echo json_encode(array("ok"=>"ok"));
 ?>
