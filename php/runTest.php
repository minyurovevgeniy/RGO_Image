<?php
  session_start();
  date_default_timezone_set('Asia/Yekaterinburg');
  
  $test=$_SESSION['currentTest'];
  include("./connect.php");

  $stmt=$pdo->prepare("UPDATE `tests` SET `test_start_time`=? WHERE `test_id`=?");
  $stmt->execute(array(time(),$test));

  echo json_encode(array("ok"=>"ok"));
?>
