<?php
date_default_timezone_set('Asia/Yekaterinburg');
  $response=array();

  $nickname=$_POST['nickname'];

  $nicknameToken="";

  include("./connect.php");

  $stmt=$pdo->prepare("SELECT `student_token` FROM `students` WHERE `student_nickname`=?");
  $stmt->execute(array(iconv("utf-8","cp1251",$nickname)));
  $row=$stmt->fetch(PDO::FETCH_LAZY);

  $response = array
  (
    "nicknameToken"=>iconv("cp1251","utf-8",$row['student_token'])
  );

  echo json_encode($response);
?>
