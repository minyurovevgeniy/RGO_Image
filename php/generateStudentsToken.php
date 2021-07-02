<?php
	session_start();
	date_default_timezone_set('Asia/Yekaterinburg');
	$test=$_SESSION['currentTest'];
		
	$groups=array();
	$studentIds=array();
	
	include("./connect.php");
	
	include("./wordUtils.php");
	include("./PHPWord.php");
	$word = new PHPWord();
	$word->setDefaultFontName('Times New Roman');
	$word->setDefaultFontSize(14);
	$section = $word->createSection();
	
		$rowHeight=m2t(10);
	$cellWidth=m2t(50);
	$numberСellWidth=m2t(3);
	
	$section->addText("Ключи для входа в тест");
	
	$stmt=$pdo->prepare("SELECT * FROM `tests` WHERE `test_id`=?");
	$stmt->execute(array($test));
	$row=$stmt->fetch(PDO::FETCH_LAZY);
	$testToken=iconv("cp1251","utf-8",$row['test_token']);
	
	$stmt=$pdo->prepare("SELECT * FROM `tests_and_groups` WHERE `tests_and_groups_test`=?");
	$stmt->execute(array($test));
	while($row=$stmt->fetch(PDO::FETCH_LAZY))
	{
		$groups[]=$row['tests_and_groups_group'];
	}
	

	
	include("./randomStudentTokenString.php");
	
	foreach($groups as $value)
	{
		$rowNumber=0;
		
		$stmt=$pdo->prepare("SELECT `group_title` FROM `groups` WHERE `group_id`=?");
		$stmt->execute(array($value));
		$row=$stmt->fetch(PDO::FETCH_LAZY);
		
		$section->addText("Группа: ".iconv("cp1251","utf-8",$row['group_title']));
		
		$table=$section->addTable();
		$table->addRow($rowHeight);
		$table->addCell($numberСellWidth)->addText("№ п/п");
		$table->addCell($cellWidth)->addText("ФИО");
		$table->addCell($cellWidth)->addText("Ключ студента");
		$table->addCell($cellWidth)->addText("Ключ теста");
		
		$stmt3=$pdo->prepare("SELECT * FROM `students` WHERE `student_group`=?");
		$stmt3->execute(array($value));
		while($rowSecond=$stmt3->fetch(PDO::FETCH_LAZY))
		{
			$token = randomStudentToken();
			$studentToken = iconv("utf-8","cp1251",$token);
			
			$stmt4=$pdo->prepare("SELECT COUNT(*) FROM `students_tokens` WHERE `student`=?");
			$stmt4->execute(array($rowSecond['student_id']));
			$rowThird=$stmt4->fetch(PDO::FETCH_LAZY);
			
			if ($rowThird['COUNT(*)']<1)
			{
				$stmt2=$pdo->prepare("INSERT INTO `students_tokens` SET `student_token`=?, `student`=?");
				$stmt2->execute(array($studentToken,$rowSecond['student_id']));
			}
			else
			{
				$stmt2=$pdo->prepare("UPDATE `students_tokens` SET `student_token`=? WHERE `student`=?");
				$stmt2->execute(array($studentToken,$rowSecond['student_id']));
			}
			
			
			$rowNumber++;
			$table->addRow($rowHeight);
			$table->addCell($numberСellWidth)->addText($rowNumber);
			$table->addCell($cellWidth)->addText(iconv("cp1251","utf-8",$rowSecond['student_surname']).' '.iconv("cp1251","utf-8",$rowSecond['student_name']).' '.iconv("cp1251","utf-8",$rowSecond['student_patronymic']));
			$table->addCell($cellWidth)->addText($token);
			$table->addCell($cellWidth)->addText($testToken);
		}
	}
	
	$stmt=$pdo->prepare("SELECT `tokens_file_path` FROM `reports_and_tokens_files` WHERE `test`=?");
	$stmt->execute(array($test));
	$row=$stmt->fetch(PDO::FETCH_LAZY);
	
	//unlink($row['tokens_file_path']);
	
	$writer = PHPWord_IOFactory::createWriter($word, 'Word2007');
	$writer->save($row['tokens_file_path']);
	
	echo json_encode(array('tokens_file_path'=>$row['tokens_file_path']));
	
	
?>