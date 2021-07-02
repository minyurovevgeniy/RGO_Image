<?php
  $test=$_SESSION['currentTest'];
  date_default_timezone_set('Asia/Yekaterinburg');

  $questionId=intval($_POST['question']);
  $oldImageId=intval($_POST['selected_image_id']);

  $newHeight=$_POST['new_height'];

  $changeSize=$_POST['change_size'];

  $image=$_FILES["new_image"]["tmp_name"];
  $currentWidth = getimagesize($image)[0];
  $currentHeight =  getimagesize($image)[1];

  /*
  if ($currentHeight>$currentWidth)
  {
	  echo 'Высота картинки не должна быть больше её ширины<br>';
	  echo '<a href="./changeImage.php">Загрузить ещё картинку</a><br>';
	  die("Ошибка");
  }
  */


  include("./php/connect.php");

/*
	echo '<pre>';
	print_r($_FILES);
	echo '</pre>';

  */

  $stmt=$pdo->prepare("SELECT * FROM `questions` WHERE `question_id`=?");
  $stmt->execute(array($questionId));
  $questionType=$stmt->fetch(PDO::FETCH_LAZY)['question_type'];

  // ===============================
  //delete image
  if ($questionType==1)
  {
    $stmt=$pdo->prepare("SELECT * FROM `single_question_variants` WHERE `single_question_id`=?");
    $stmt->execute(array($oldImageId));
    $row=$stmt->fetch(PDO::FETCH_LAZY);
    $link = ltrim($row['single_question_image_link_to_delete'],".");
    unlink(".".$link);
  }
  else
  {
    if ($questionType==2)
    {
      $stmt=$pdo->prepare("SELECT * FROM `multiple_question_variants` WHERE `multiple_question_variants_id`=?");
      $stmt->execute(array($oldImageId));
      $row=$stmt->fetch(PDO::FETCH_LAZY);
      $link = ltrim($row['multiple_question_image_link_to_delete'],".");
      unlink(".".$link);
    }
    else
    {
      $stmt=$pdo->prepare("SELECT * FROM `open_questions` WHERE `open_question_question`=?");
      $stmt->execute(array($questionId));
      $row=$stmt->fetch(PDO::FETCH_LAZY);
      $link = ltrim($row['open_question_image_link_to_delete'],".");
      unlink(".".$link);
    }
  }
  //=================================================================
  // save new image
    $urlImagePrefix="./images/";
    $tmp_name = $_FILES["new_image"]["tmp_name"];
    $name = "image_".time()."_".rand().'.jpeg';
    $urlImageUpload="http://rgo-image.xn--100-5cdnry0bhchmgqi5d.xn--p1ai/images/".$name;

    $finalName=$urlImagePrefix.''.$name;
    move_uploaded_file($tmp_name, $finalName);
    
    // открыть мзображение
    $image = imagecreatefromjpeg($finalName);

    // получить размеры изображения
    $widthAndHeight=getimagesize($finalName);

    //width = 0 	height = 1
    $widthDividedByHeightCoefficient=$widthAndHeight[0]/$widthAndHeight[1];
    if ($widthAndHeight[1]>=$newHeight)
		{
      $newWidth = $widthDividedByHeightCoefficient * $newHeight;

      // новое изображение
      $newImage=imagecreatetruecolor($newWidth,$newHeight);


      // изменение размера
      /*
      imagecopyresized ( resource $dst_image , resource $src_image ,
                int $dst_x , int $dst_y , int $src_x , int $src_y ,
                int $dst_w , int $dst_h , int $src_w , int $src_h )
      */

      imagecopyresized($newImage,$image,
              0,0,0,0,
              $newWidth,$newHeight,$widthAndHeight[0],$widthAndHeight[1]);


    // сохранить
    imagejpeg($newImage, $finalName);
    }

    //=======================================================================================================================
    // save link to database
    if ($questionType==1)
		{
  		$stmt=$pdo->prepare("UPDATE `single_question_variants` SET `single_question_image_link`=?, `single_question_image_link_to_delete`=? WHERE `single_question_id`=?");
  		$stmt->execute(array($urlImageUpload,'.'.$finalName,$oldImageId));
		}
		else
		{
			if ($questionType==2)
			{
				$stmt=$pdo->prepare("UPDATE `multiple_question_variants` SET `multiple_question_variants_image_link`=?, `multiple_question_image_link_to_delete`=? WHERE `multiple_question_variants_id`=?");
				$stmt->execute(array($urlImageUpload,'.'.$finalName,$oldImageId));
			}
			else
			{
				$stmt=$pdo->prepare("UPDATE `open_questions` SET `open_question_image_link`=?,`open_question_image_link_to_delete`=? WHERE `open_question_question`=?");
				$stmt->execute(array($urlImageUpload,'.'.$finalName,$questionId));
			}
		}
    echo '<a href="./changeImage.php">Изменить еще картинку</a>';
?>
