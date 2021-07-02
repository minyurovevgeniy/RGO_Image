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
		$group=iconv("utf-8","cp1251",$sheet->getCellByColumnAndRow($groupColumn, $rowNumber)->getValue());
		$group=preg_replace('/\s{1,}/gi','',$group);
		$length=strlen($group);
		
		if ($length<1)
		{
			echo 'Строка №'.$rowNumber.'<br>';
			echo '<a href="./loadGroups.php">Загрузить ещё группы</a>';
			die("Введите номер группы");
		}
		
		$stmt=$pdo->prepare("SELECT `group_id` FROM `groups` WHERE `group_title`=?");
		$stmt->execute(array($group));
		$row=$stmt->fetch(PDO::FETCH_LAZY);
		
		if ($row['group_id']<1)
		{
			$stmt=$pdo->prepare("INSERT INTO `groups` SET `group_title`=?");
			$stmt->execute(array($group));
		}
		else
		{
			echo 'Строка №'.$rowNumber.'<br>';
			echo 'Группа '.iconv("cp1251","utf-8",$group).' уже существует<br><br>';
			
		}
	}
	include("./php/disconnect.php");

	echo '<a href="./loadGroups.php">Загрузить ещё группы</a>';
?>
