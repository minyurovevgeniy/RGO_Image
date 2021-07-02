<?php
	session_start();
	date_default_timezone_set('Asia/Yekaterinburg');
	require_once './PHPExcel/Classes/PHPExcel.php';
	$test=$_SESSION['currentTest'];
	
	// Create new PHPExcel object
	$objPHPExcel = new PHPExcel();
	
	include("./connect.php");

	
	
	$rowNumber=1;
	$sheet=$objPHPExcel->getActiveSheet();
	
	$stmt=$pdo->prepare("SELECT * FROM `tests` WHERE `test_id`=?");
	$stmt->execute(array($test));
	$row=$stmt->fetch(PDO::FETCH_LAZY);
	$testTitle=$row['test_title'];
	$sheet->setCellValueByColumnAndRow(1, $rowNumber, iconv("cp1251","utf-8",$testTitle));
	
	$rowNumber++;
	$sheet->setCellValueByColumnAndRow(1, $rowNumber, "Вопрос");
	$sheet->setCellValueByColumnAndRow(2, $rowNumber, "Варианты ответа");
	$sheet->setCellValueByColumnAndRow(3, $rowNumber, "Правильный вариант");
	
	$stmt=$pdo->prepare("SELECT * FROM `questions` WHERE `question_test`=?");
	$stmt->execute(array($test));
	
	$rowNumber++;
	while($row=$stmt->fetch(PDO::FETCH_LAZY))
	{
		$sheet->setCellValueByColumnAndRow(1, $rowNumber, iconv("cp1251","utf-8",$row['question_text']));
		$stmtVariants=$pdo->prepare("SELECT * FROM `answer_variants` WHERE `test`=? AND `question`=? ORDER BY `variant_id`");
		$stmtVariants->execute(array($test,$row['question_id']));
		
		$variants=array();
		while($rowVariants=$stmtVariants->fetch(PDO::FETCH_LAZY))
		{
			$variants[]=iconv("cp1251","utf-8",$rowVariants['variant']);
		}
		
		/*
		echo '<pre>';
		print_r($variants);
		echo '</pre>';
		*/
		unset($variants[0]);
		$variantsString=implode(";",$variants);
		$sheet->setCellValueByColumnAndRow(2,$rowNumber,$variantsString);
		
		$stmtRightAnswer=$pdo->prepare("SELECT `variant` FROM `answer_variants` WHERE `is_correct`=? AND `question`=? AND `test`=?");
		$stmtRightAnswer->execute(array(iconv("utf-8","cp1251","correct"),$row['question_id'],$test));		
		$stmtRightAnswerRow=$stmtRightAnswer->fetch(PDO::FETCH_LAZY);
				
		$answerOrder=1;
		$answerNumber=1;
		foreach($variants as $value)
		{
			if ($value==$stmtRightAnswerRow['variant'])
			{
				$answerOrder=$answerNumber;
				break;
			}
			$answerNumber++;
		}
		
		//$sheet->setCellValueByColumnAndRow(3, $rowNumber, iconv("cp1251","utf-8",$stmtRightAnswerRow['variant']));
		$sheet->setCellValueByColumnAndRow(3, $rowNumber, $answerOrder);
		$rowNumber++;
	}
	
	$stmt=$pdo->prepare("SELECT `questions_file_path` FROM `reports_and_tokens_files` WHERE `test`=?");
	$stmt->execute(array($test));
	$row=$stmt->fetch(PDO::FETCH_LAZY);
	
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	//$objWriter->save($row['questions_file_path']);
	$objWriter->save('../reports/report_questions_'.$test.'.xls');
	
	echo json_encode(array("path"=>'../reports/report_questions_'.$test.'.xls'));
	
?>