<?php
  date_default_timezone_set('Asia/Yekaterinburg');
  include("./connect.php");

  $stmt=$pdo->prepare("SELECT `test_id` FROM `tests` WHERE `test_token`=?");
  $stmt->execute(array(iconv("utf-8","cp1251",$testToken)));
  $row=$stmt->fetch(PDO::FETCH_LAZY);
  $test=$row['test_id'];

  $stmt=$pdo->prepare("SELECT * FROM `tests` WHERE `test_id`=?");
  $stmt->execute(array($test));
  $row=$stmt->fetch(PDO::FETCH_LAZY);
  $timeEclapsed = $row['test_start_time']+$row['test_duration'];

  $response=array
  (
    "timeEclapsed"=>$timeEclapsed
  );
?>
