<?php
  session_start();
  date_default_timezone_set('Asia/Yekaterinburg');

  $test=$_SESSION['currentTest'];
  include("./connect.php");

  $stmt=$pdo->prepare("SELECT `test_duration` FROM `tests` WHERE `test_id`=?");
  $stmt->execute(array($test));
  $row=$stmt->fetch(PDO::FETCH_LAZY);
  $duration=$row['test_duration']/60;

  echo json_encode(array("response"=>$duration));

?>
