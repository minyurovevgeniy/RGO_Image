<?php
  date_default_timezone_set('Asia/Yekaterinburg');
  include("./connect.php");

  $questionId=$_GET['id'];

  $stmt=$pdo->prepare("SELECT * FROM `questions` WHERE `question_id`=?");
  $stmt->execute(array($questionId));
  $row=$stmt->fetch(PDO::FETCH_LAZY);
  $questionType= $row['question_type'];

  $imagesIds=array();

  if ($questionType==1)
  {
    $stmt=$pdo->prepare("SELECT * FROM `single_question_variants` WHERE `single_question_question`=?");
    $stmt->execute(array($questionId));
    while($row=$stmt->fetch(PDO::FETCH_LAZY))
    {
      $imagesIds['image_ids'][]=$row['single_question_id'];
    }
  }
  else
  {
    if ($questionType==2)
    {
      $stmt=$pdo->prepare("SELECT * FROM `multiple_question_variants` WHERE `multiple_question_variants_question`=?");
      $stmt->execute(array($questionId));
      while($row=$stmt->fetch(PDO::FETCH_LAZY))
      {
        $imagesIds['image_ids'][]=$row['multiple_question_variants_id'];
      }
    }
    else
    {
      $stmt=$pdo->prepare("SELECT * FROM `open_questions` WHERE `open_question_question`=?");
      $stmt->execute(array($questionId));
      while($row=$stmt->fetch(PDO::FETCH_LAZY))
      {
        $imagesIds['image_ids'][]=$row['open_question_id'];
      }
    }
  }

 echo json_encode($imagesIds);

?>
