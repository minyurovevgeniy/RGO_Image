<?php
  session_start();
  date_default_timezone_set('Asia/Yekaterinburg');

  $test=$_SESSION['currentTest'];

//$durationInSeconds=$_GET['duration']*60;
  $durationInSeconds=intval($_POST['duration'])*60;
  
  include("./connect.php");

  $stmt=$pdo->prepare("UPDATE `tests` SET `test_duration`=? WHERE `test_id`=?");
  $stmt->execute(array($durationInSeconds,$test));


  $stmt=$pdo->prepare("SELECT * FROM `tests` WHERE `test_id`=?");
  $stmt->execute(array($test));
  $row=$stmt->fetch(PDO::FETCH_LAZY);

  $response=array
  (
  	"test_duration"=>$row['test_duration']
  );

  echo json_encode($response);
 ?>
