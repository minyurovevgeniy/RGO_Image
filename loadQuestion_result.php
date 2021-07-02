<?php
	session_start();
	//if ($_SESSION['mdf843hrk52']<=0 or !isset($_SESSION['mdf843hrk52'])) die("OK");
	$test=$_SESSION['currentTest'];
	date_default_timezone_set('Asia/Yekaterinburg');

	$newHeight=$_POST['new_height'];
	/*
	echo '<pre>';
	print_r($_FILES);
	echo '</pre>';
	*/

	/*
	if ($_POST['input_password']!="UMoatTvRLZwyPLT")
	{
		echo '<a href="./loadBachelorProfiles.php">Попробовать ещё раз</a>';
		die('Неверный пароль');
	}
	*/

	/*
	if (strtolower($shouldEmpty)=="да")
	{
		$stmt=$pdo->prepare("TRUNCATE TABLE bachelor_profiles");
		$stmt->execute();
		$stmt=$pdo->prepare("TRUNCATE TABLE bachelor_profile_description");
		$stmt->execute();
	}
	*/

	//include("./php/loadData.php");

	// load (!!!) questions (!!!) to database

	include("./php/connect.php");

	$urlImagePrefix="./images/";

	$questionText=$_POST['question_text'];
	$questionType=$_POST['question_type'];
	$questionCorrectAnswers=$_POST['right_image'];
	$questionType=(int)$_POST['question_type'];

	$variantOpen=$_POST['right_answer'];
	$shouldEmpty=$_POST['shouldEmpty'];

	$filesCount = count($_FILES['name']);
	$questionText = preg_replace("/\s{2,}/",' ',$questionText);

	$questionTextWithoutSpaces = preg_replace("/\s{1,}/",'',$questionText);

	if (strlen($questionTextWithoutSpaces)<1)
	{
		echo 'Строка №'.$rowNumber.'<br>';
		echo '<a href="./loadQuestion.php">Попробовать еще раз</a><br>';
		die("Вопрос не должен быть пустым");
	}

	$questionText=iconv("utf-8","cp1251",$questionText);


	$stmt=$pdo->prepare("SELECT * FROM `questions` WHERE `question_text`=? AND `question_test`=?");
	$stmt->execute(array($questionText,$test));
	$row=$stmt->fetch(PDO::FETCH_LAZY);
	if ($row['question_id']>0)
	{
		echo '<a href="./loadQuestion.php">Попробовать еще раз</a>';
		die("Такой вопрос уже существует");
	}
	else
	{
		$stmt=$pdo->prepare("INSERT INTO `questions` SET `question_text`=?, `question_test`=?, `question_type`=?");
		$stmt->execute(array($questionText,$test,$questionType));
	}

	$stmt=$pdo->prepare("SELECT `question_id` FROM `questions` WHERE `question_text`=? AND `question_test`=? AND `question_type`=?");
	$stmt->execute(array($questionText,$test,$questionType));
	$row=$stmt->fetch(PDO::FETCH_LAZY);
	$questionId=$row['question_id'];

	foreach ($_FILES["questions"]["error"] as $key => $error)
	{
		$trueOrder = $key+1;
		$stmt=$pdo->prepare("SELECT * FROM `questions`  WHERE `question_text`=? AND `question_test`=? AND `question_type`=?");
		$stmt->execute(array($questionText,$test,$questionType));
		$row=$stmt->fetch(PDO::FETCH_LAZY);
		$questionId=$row['question_id'];
		$tmp_name = $_FILES["questions"]["tmp_name"][$key];
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
			//imagecopyresized ( resource $dst_image , resource $src_image ,
			//					int $dst_x , int $dst_y , int $src_x , int $src_y ,
			//					int $dst_w , int $dst_h , int $src_w , int $src_h )


			imagecopyresized($newImage,$image,
							0,0,0,0,
							$newWidth,$newHeight,$widthAndHeight[0],$widthAndHeight[1]);

			// сохранить
			imagejpeg($newImage, $finalName);
		}

		if ($questionType==1)
		{
			if ($questionCorrectAnswers==$trueOrder)
			{
				$stmt=$pdo->prepare("INSERT INTO `single_question_variants` SET `single_question_correct`=?, `single_question_question`=?, `single_question_image_link`=?, `single_question_image_link_to_delete`=?");
				$stmt->execute(array(1,$questionId,$urlImageUpload,'.'.$finalName));
			}
			else
			{
				$stmt=$pdo->prepare("INSERT INTO `single_question_variants` SET `single_question_correct`=?, `single_question_question`=?, `single_question_image_link`=?, `single_question_image_link_to_delete`=?");
				$stmt->execute(array(0,$questionId,$urlImageUpload,'.'.$finalName));
			}
		}
		else
		{
			if ($questionType==2)
			{
				$correctVariantsArray=explode(";",$questionCorrectAnswers);

				if (in_array($trueOrder,$correctVariantsArray))
				{
					$stmt=$pdo->prepare("INSERT INTO `multiple_question_variants` SET `multiple_question_variants_correct`=?, `multiple_question_variants_question`=?, `multiple_question_variants_image_link`=?, `multiple_question_image_link_to_delete`=?");
					$stmt->execute(array(1,$questionId,$urlImageUpload,'.'.$finalName));
				}
				else
				{
					$stmt=$pdo->prepare("INSERT INTO `multiple_question_variants` SET `multiple_question_variants_correct`=?, `multiple_question_variants_question`=?, `multiple_question_variants_image_link`=?, `multiple_question_image_link_to_delete`=?");
					$stmt->execute(array(0,$questionId,$urlImageUpload,'.'.$finalName));
				}
			}
			else
			{
				$stmt=$pdo->prepare("INSERT INTO `open_questions` SET `open_question_value`=?, `open_question_question`=?, `open_question_image_link`=?,`open_question_image_link_to_delete`=?");
				$stmt->execute(array(iconv("utf-8","cp1251",$variantOpen),$questionId,$urlImageUpload,'.'.$finalName));
			}
		}
	}
	include("./php/disconnect.php");

	echo '<a href="./loadQuestion.php">Загрузить ещё вопрос</a>';
?>
