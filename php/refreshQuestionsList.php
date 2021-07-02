<?php
  session_start();
  $test=$_SESSION['currentTest'];
  date_default_timezone_set('Asia/Yekaterinburg');
  include("./connect.php");

  $stmt=$pdo->prepare("SELECT * FROM `questions` WHERE `question_test`=?");
  $stmt->execute(array($test));

  $response=array();
  while($row=$stmt->fetch(PDO::FETCH_LAZY))
  {
    $response['questions'][]=array
    (
      'id'=>$row['question_id'],
      'text'=>iconv("cp1251","utf-8",$row['question_text'])
    );
  }

  include("./disconnect.php");

  echo json_encode($response);

 ?>
