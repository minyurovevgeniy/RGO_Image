<?php
  session_start();
  date_default_timezone_set('Asia/Yekaterinburg');

  $test=$_SESSION['currentTest'];
	$groups=array();

  include("./wordUtils.php");
  include("./PHPWord.php");
  $word = new PHPWord();
  $word->setDefaultFontName('Times New Roman');
  $word->setDefaultFontSize(14);
  $section = $word->createSection();

  $rowHeight=m2t(10);
  $cellWidth=m2t(50);

  include("./connect.php");

  $stmt=$pdo->prepare("SELECT `test_start_time` FROM `tests` WHERE `test_id`=?");
  $stmt->execute(array($test));
  $row=$stmt->fetch(PDO::FETCH_LAZY);
  $secondsEclapsed=$row['test_start_time'];
  $testTime=date("G:i:s d.m.Y",$secondsEclapsed);

  $stmt=$pdo->prepare("SELECT * FROM `tests_and_groups` WHERE `tests_and_groups_test`=?");
	$stmt->execute(array($test));
	while($row=$stmt->fetch(PDO::FETCH_LAZY))
  {
		$groups[]=$row['tests_and_groups_group'];
	}

  $stmt=$pdo->prepare("SELECT `test_title` FROM `tests` WHERE `test_id`=?");
  $stmt->execute(array($test));
  $row=$stmt->fetch(PDO::FETCH_LAZY);
  $testTitle=iconv("cp1251","utf-8",$row['test_title']);

  $score=0;

	foreach($groups as $value)
	{
 	$rowNumber=1;
    $section->addText("Тест: ".$testTitle);
    $section->addText("Время и дата: ".$testTime);
    $stmt=$pdo->prepare("SELECT `group_title` FROM `groups` WHERE `group_id`=?");
  	$stmt->execute(array($value));
  	$row=$stmt->fetch(PDO::FETCH_LAZY);
    $section->addText("Группа: ".iconv("cp1251","utf-8",$row['group_title']));

	$table=$section->addTable();
  	$table->addRow($rowHeight);
  	$table->addCell($cellWidth)->addText("№ п/п");
  	$table->addCell($cellWidth)->addText("ФИО");
  	$table->addCell($cellWidth)->addText("Балл");
	$table->addCell($cellWidth)->addText("Процент верных ответов");


	$stmt=$pdo->prepare("SELECT * FROM `students` WHERE `student_group`=? ORDER BY `student_surname` ASC, `student_name` ASC, `student_patronymic` ASC");
	$stmt->execute(array($value));
	while($row=$stmt->fetch(PDO::FETCH_LAZY))
	{	
		$totalQuestions=0;
		$score=0;
		$student=$row['student_id'];
		$stmtQuestions=$pdo->prepare("SELECT * FROM `questions` WHERE `question_test`=?");
		$stmtQuestions->execute(array($test));
		while($rowQuestions=$stmtQuestions->fetch(PDO::FETCH_LAZY))
		{
			$totalQuestions++;
			/*
			$questionDictionary=array();
			if ($rowQuestions['question_type']==1)
			{
				$stmtSingleQuestions=$pdo->prepare("SELECT * FROM `single_question_variants` WHERE `single_question_id`=?");
				$stmtSingleQuestions->execute(array($rowQuestions['question_id']));
				$rowSingleQuestions=$stmtSingleQuestions->fetch(PDO::FETCH_LAZY);
				$questionDictionary[$rowSingleQuestions['single_question_id']]=$rowSingleQuestions['single_question_variant'];
				while($rowSingleQuestions=$stmtSingleQuestions->fetch(PDO::FETCH_LAZY))
				{
					$questionDictionary[$rowSingleQuestions['single_question_id']]=$rowSingleQuestions['single_question_variant'];
				}
			}
			else
			{
				if ($rowQuestions['question_type']==2)
				{
					$stmtMultipleQuestions=$pdo->prepare("SELECT * FROM `multiple_question_variants` WHERE `multiple_question_variants_question`=?");
					$stmtMultipleQuestions->execute(array($rowQuestions['question_id']));
					$rowMultipleQuestions=$stmtMultipleQuestions->fetch(PDO::FETCH_LAZY);
					while($rowMultipleQuestions=$stmtMultipleQuestions->fetch(PDO::FETCH_LAZY))
					{
						$questionDictionary[$rowMultipleQuestions['multiple_question_id']]=$rowMultipleQuestions['multiple_question_variants_variant'];
					}
				}
				else
				{
					$stmtOpenQuestion=$pdo->prepare("SELECT * FROM `open_questions` WHERE `open_question_question`=?");
					$stmtOpenQuestion->execute(array($rowQuestions['open_question_value']));
					$rowOpenQuestion=$stmtOpenQuestion->fetch(PDO::FETCH_LAZY);
					$answer=$rowOpenQuestion['open_question_value'];
				}
			}
			*/
			if ($rowQuestions['question_type']==1)
			{
				$stmtStudentSingleAnswer=$pdo->prepare("SELECT * FROM `single_question_answers` WHERE `single_question_answers_question`=? AND `single_question_answers_student`=?");
				$stmtStudentSingleAnswer->execute(array($rowQuestions['question_id'],$student));
				$rowStudentSingleAnswer=$stmtStudentSingleAnswer->fetch(PDO::FETCH_LAZY);
				
				$stmtSingleAnswers=$pdo->prepare("SELECT * FROM `single_question_variants` WHERE `single_question_question`=? AND `single_question_correct`=1");
				$stmtSingleAnswers->execute(array($rowQuestions['question_id']));
				while($rowSingleAnswers=$stmtSingleAnswers->fetch(PDO::FETCH_LAZY))
				{
					if ($rowSingleAnswers['single_question_id']==$rowStudentSingleAnswer['single_question_answers_answer'])
					{
						$score++;
					}
				}
			}
			else
			{
				if ($rowQuestions['question_type']==2)
				{					
					$answersArray=array();
					$stmtMultipleAnswers=$pdo->prepare("SELECT * FROM `multiple_question_variants` WHERE `multiple_question_variants_question`=? AND `multiple_question_variants_correct`=1");
					$stmtMultipleAnswers->execute(array($rowQuestions['question_id']));
					while($rowMultipleAnswers=$stmtMultipleAnswers->fetch(PDO::FETCH_LAZY))
					{
						$answersArray[]=$rowMultipleAnswers['multiple_question_variants_id'];
					}
					
					sort($answersArray);
					
					/*
					echo '<pre>';
					print_r($answersArray);
					echo '</pre><br>';
					*/
					$answersStudentArray=array();
					$stmtStudentMultipleAnswer=$pdo->prepare("SELECT * FROM `multiple_question_answers` WHERE `multiple_question_answers_question`=? AND `multiple_question_answers_student`=?");
					$stmtStudentMultipleAnswer->execute(array($rowQuestions['question_id'],$student));
					while($rowStudentMultipleAnswers=$stmtStudentMultipleAnswer->fetch(PDO::FETCH_LAZY))
					{
						$answersStudentArray[]=$rowStudentMultipleAnswers['multiple_question_answers_answer'];
					}
					
					sort($answersStudentArray);
					
					/*
					echo '<pre>';
					print_r(answersStudentArray);
					echo '</pre><br>';
					*/
					if ($answersArray==$answersStudentArray)
					{
						$score++;
					}
					
				}
				else
				{
					$stmtStudentOpenQuestion=$pdo->prepare("SELECT * FROM `open_questions_answers` WHERE `open_questions_answers_question`=? AND `open_questions_answers_student`=?");
					$stmtStudentOpenQuestion->execute(array($rowQuestions['question_id'],$student));
					$rowStudentOpenQuestion=$stmtStudentOpenQuestion->fetch(PDO::FETCH_LAZY);
					
					$studentOpenQuestionAnswer=$rowStudentOpenQuestion['open_questions_answers_answer'];
					
					$stmtOpenQuestion=$pdo->prepare("SELECT * FROM `open_questions` WHERE `open_question_question`=?");
					$stmtOpenQuestion->execute(array($rowQuestions['question_id']));
					$rowOpenQuestion=$stmtOpenQuestion->fetch(PDO::FETCH_LAZY);
					$openQuestion=$rowOpenQuestion['open_question_value'];
					
					if ($studentOpenQuestionAnswer==$openQuestion)
					{
						$score++;
					}
				}
			}
			
		}
		
		$table->addRow($rowHeight);
		$table->addCell(1)->addText($rowNumber);
		$table->addCell($cellWidth)->addText(iconv("cp1251","utf-8",$row['student_surname']).' '.iconv("cp1251","utf-8",$row['student_name']).' '.iconv("cp1251","utf-8",$row['student_patronymic']));
		$table->addCell($cellWidth)->addText($score);
		$table->addCell($cellWidth)->addText(round(100*$score/$totalQuestions."%",2));
		$rowNumber++;
	}
    $section->addTextBreak(2);
	}

  $stmt=$pdo->prepare("SELECT `report_short_path` FROM `reports_and_tokens_files` WHERE `test`=?");
  $stmt->execute(array($test));
  $row=$stmt->fetch(PDO::FETCH_LAZY);
  $writer = PHPWord_IOFactory::createWriter($word, 'Word2007');
	$writer->save($row['report_short_path']);

  echo json_encode(array("report_short_path"=>$row['report_short_path']));
  
 ?>
