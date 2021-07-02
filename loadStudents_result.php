<?php
	session_start();
	//if ($_SESSION['mdf843hrk52']<=0 or !isset($_SESSION['mdf843hrk52'])) die("OK");

	date_default_timezone_set('Asia/Yekaterinburg');

	/*
	if ($_POST['input_password']!="UMoatTvRLZwyPLT")
	{
		echo '<a href="./loadBachelorProfiles.php">Попробовать ещё раз</a>';
		die('Неверный пароль');
	}
	*/

	$test=$_SESSION['currentTest'];

	include("./php/connect.php");
	require_once './php/PHPExcel/Classes/PHPExcel.php';

	//include("./php/loadData.php");

	$fileName=$_FILES['profiles']['tmp_name'];

	if ($_POST['worksheet_number']<1)
	{
		echo '<a href="./loadQuestions.php">Загрузить ещё вопросы</a>';
		die("Укажите корректный номер листа");
	}

	$objPHPExcel = PHPExcel_IOFactory::load($fileName);

	$worksheet=$_POST['worksheet_number']-1;
	$surnameColumn=$_POST['surname_column']-1;
	$nameColumn=$_POST['name_column']-1;
	$patromymicColumn=$_POST['patronymic_column']-1;
	$groupColumn=$_POST['group_column']-1;

	$MIN_ROW=$_POST['min_row'];
	$MAX_ROW=$_POST['max_row'];
	$shouldEmpty=$_POST['shouldEmpty'];

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
	$sheet=$objPHPExcel->getSheet($worksheet);
	for($rowNumber=$MIN_ROW;$rowNumber<=$MAX_ROW;$rowNumber++)
	{
		$surname=iconv("utf-8","cp1251",$sheet->getCellByColumnAndRow($surnameColumn, $rowNumber)->getValue());
		$name=iconv("utf-8","cp1251",$sheet->getCellByColumnAndRow($nameColumn, $rowNumber)->getValue());
		$patronymic=iconv("utf-8","cp1251",$sheet->getCellByColumnAndRow($patromymicColumn, $rowNumber)->getValue());
		$group=iconv("utf-8","cp1251",$sheet->getCellByColumnAndRow($groupColumn, $rowNumber)->getValue());

		$surname=preg_replace('/\s{1,}/gi','',$surname);
		$name=preg_replace('/\s{1,}/gi','',$name);
		$patronymic=preg_replace('/\s{1,}/gi','',$patronymic);
		if (strlen($surname)<1)
		{
			echo 'Строка №'.$rowNumber.'<br>';
			echo '<a href="./loadQuestions.php">Попробовать ещё раз</a>';
			die("Введите фамилию");
		}
		else
		{
			if (strlen($name)<1)
			{
				echo 'Строка №'.$rowNumber.'<br>';
				echo '<a href="./loadQuestions.php">Попробовать ещё раз</a>';
				die("Введите имя");
			}
			else
			{
				if (strlen($patronymic)<1)
				{
					echo 'Строка №'.$rowNumber.'<br>';
					echo '<a href="./loadQuestions.php">Попробовать ещё раз</a>';
					die("Введите отчество");
				}
			}
		}

		$stmt=$pdo->prepare("SELECT * FROM `groups` WHERE `group_title`=?");
		$stmt->execute(array($group));
		$row=$stmt->fetch(PDO::FETCH_LAZY);
		$groupId=$row['group_id'];

		if ($groupId<1)
		{
			die("Группа не найдена");
		}

		$stmt=$pdo->prepare("SELECT `student_id` FROM `students` WHERE `student_name`=? AND `student_surname`=? AND `student_patronymic`=? AND `student_group`=?");
		$stmt->execute(array($surname,$name,$patronymic,$groupId));
		$row=$stmt->fetch(PDO::FETCH_LAZY);

		if ($row['student_id']<1)
		{
				$stmt=$pdo->prepare("INSERT INTO `students` SET `student_name`=?, `student_surname`=?, `student_patronymic`=?, `student_group`=?");
				$stmt->execute(array($surname,$name,$patronymic,$groupId));
		}
	}
	include("./php/disconnect.php");

	echo '<a href="./loadQuestions.php">Загрузить ещё студентов</a>';
?>
