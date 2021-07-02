<?php
	date_default_timezone_set('Asia/Yekaterinburg');
	
	include("./connect.php");

  $questionId=$_POST['id'];
  
  // single
  $stmt=$pdo->prepare("DELETE FROM `questions` WHERE `question_id`=?");
  $stmt->execute(array($questionId));
  
  $singleVariantsFiles=array();
  $stmt=$pdo->prepare("SELECT `single_question_image_link_to_delete` FROM `single_question_variants` WHERE `single_question_question`=?");
  $stmt->execute(array($questionId));
  while($row=$stmt->fetch(PDO::FETCH_LAZY))
  {
	  $singleVariantsFiles[]=$row['single_question_image_link_to_delete'];
  }
  foreach($singleVariantsFiles as $fileName)
  {
	  unlink($fileName);
  }
  $stmt=$pdo->prepare("DELETE FROM `single_question_variants` WHERE `single_question_question`=?");
  $stmt->execute(array($questionId));
  
  
  // multiple
  $multipleVariantsFiles[]
  $stmt=$pdo->prepare("SELECT `multiple_question_image_link_to_delete` FROM `multiple_question_variants` WHERE `multiple_question_variants_question`=?");
  $stmt->execute(array($questionId));
  while($row=$stmt->fetch(PDO::FETCH_LAZY))
  {
	  $multipleVariantsFiles[]=$row['multiple_question_image_link_to_delete'];
  }
  foreach($multipleVariantsFiles as $fileName)
  {
	  unlink($fileName);
  }
  $stmt=$pdo->prepare("DELETE FROM `multiple_question_variants` WHERE `multiple_question_variants_question`=?");
  $stmt->execute(array($questionId));
  
  
  // open
  $openFile="";
  
  $stmt=$pdo->prepare("SELECT `open_question_image_link_to_delete` FROM `open_questions` WHERE `open_question_question`=?");
  $stmt->execute(array($questionId));
  
  $stmt=$pdo->prepare("DELETE FROM `open_questions` WHERE `open_question_id`=?");
  $stmt->execute(array($id));

  echo json_encode(array("ok"=>"ok"));
?>