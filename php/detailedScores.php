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
	$numberСellWidth=m2t(2);

	include("./connect.php");

	$stmt=$pdo->prepare("SELECT `test_start_time` FROM `tests` WHERE `test_id`=?");
	$stmt->execute(array($test));
	$row=$stmt->fetch(PDO::FETCH_LAZY);
	$secondsEclapsed=$row['test_start_time'];
	$testTime=date("G:i:s d.m.Y",$secondsEclapsed);

	$stmt=$pdo->prepare("SELECT `test_title` FROM `tests` WHERE `test_id`=?");
	$stmt->execute(array($test));
	$row=$stmt->fetch(PDO::FETCH_LAZY);
	$testTitle=iconv("cp1251","utf-8",$row['test_title']);

	$stmt=$pdo->prepare("SELECT * FROM `tests_and_groups` WHERE `tests_and_groups_test`=?");
	$stmt->execute(array($test));
	while($row=$stmt->fetch(PDO::FETCH_LAZY))
	{
		$groups[]=$row['tests_and_groups_group'];
	}




 foreach($groups as $value)
{
	$section->addText("Тест: ".$testTitle);
	$section->addText("Время и дата: ".$testTime);
	$stmt=$pdo->prepare("SELECT `group_title` FROM `groups` WHERE `group_id`=?");
	$stmt->execute(array($value));
	$row=$stmt->fetch(PDO::FETCH_LAZY);
	$section->addText("Группа: ".iconv("cp1251","utf-8",$row['group_title']));

	$stmtStudent=$pdo->prepare("SELECT * FROM `students` WHERE `student_group`=? ORDER BY `student_surname` ASC, `student_name` ASC, `student_patronymic` ASC");
	$stmtStudent->execute(array($value));
	
	while($rowStudent=$stmtStudent->fetch(PDO::FETCH_LAZY))
	{
		$student=$rowStudent['student_id'];
		$section->addText('Студент: '.iconv("cp1251","utf-8",$rowStudent['student_surname']).' '.iconv("cp1251","utf-8",$rowStudent['student_name']).' '.iconv("cp1251","utf-8",$rowStudent['student_patronymic']));
		$table=$section->addTable();
		$table->addRow($rowHeight);
		$table->addCell($numberСellWidth)->addText("№ п/п");
		$table->addCell($cellWidth)->addText("Вопрос");
		$table->addCell($cellWidth)->addText("Ответ студента");
		$table->addCell($cellWidth)->addText("Правильный ответ");
		$stmtQuestions=$pdo->prepare("SELECT * FROM `questions` WHERE `question_test`=?");
		$stmtQuestions->execute(array($test));
		$rowNumber=0;
		$totalQuestions=0;
		$score=0;
		while($rowQuestions=$stmtQuestions->fetch(PDO::FETCH_LAZY))
			{
				$totalQuestions++;
				$rowNumber++;
			
				$table->addRow($rowHeight);					
				$table->addCell($numberСellWidth)->addText($rowNumber);
				$table->addCell($cellWidth)->addText(iconv("cp1251","utf-8",$rowQuestions['question_text']));
				// ---- Create dictionary - start ---
				$questionDictionary=array();
				if ($rowQuestions['question_type']==1)
				{
					$stmtSingleQuestions=$pdo->prepare("SELECT * FROM `single_question_variants` WHERE `single_question_question`=?");
					$stmtSingleQuestions->execute(array($rowQuestions['question_id']));
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
						while($rowMultipleQuestions=$stmtMultipleQuestions->fetch(PDO::FETCH_LAZY))
						{
							$questionDictionary[$rowMultipleQuestions['multiple_question_variants_id']]=$rowMultipleQuestions['multiple_question_variants_variant'];
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
				
				/* ---- Create dictionary -------------------------- end ------------------- */
				/*
				
				*/
				/* Write answers ---------------------------------- start ------------------- */
			
			
				if ($rowQuestions['question_type']==1)
				{
					$stmtStudentSingleAnswer=$pdo->prepare("SELECT * FROM `single_question_answers` WHERE `single_question_answers_question`=? AND `single_question_answers_student`=?");
					$stmtStudentSingleAnswer->execute(array($rowQuestions['question_id'],$student));
					$rowStudentSingleAnswer=$stmtStudentSingleAnswer->fetch(PDO::FETCH_LAZY);							
					$table->addCell($cellWidth)->addText(iconv("cp1251","utf-8",$questionDictionary[$rowStudentSingleAnswer['single_question_answers_answer']]));
					
					$stmtSingleAnswers=$pdo->prepare("SELECT * FROM `single_question_variants` WHERE `single_question_question`=? AND `single_question_correct`=1");
					$stmtSingleAnswers->execute(array($rowQuestions['question_id']));
					$rowSingleAnswers=$stmtSingleAnswers->fetch(PDO::FETCH_LAZY);
					$table->addCell($cellWidth)->addText(iconv("cp1251","utf-8",$questionDictionary[$rowSingleAnswers['single_question_id']]));
				}
				else
				{
					if ($rowQuestions['question_type']==2)
					{		
						$answersStudentArray=array();
						$stmtStudentMultipleAnswer=$pdo->prepare("SELECT * FROM `multiple_question_answers` WHERE `multiple_question_answers_question`=? AND `multiple_question_answers_student`=?");
						$stmtStudentMultipleAnswer->execute(array($rowQuestions['question_id'],$student));
						while($rowStudentMultipleAnswers=$stmtStudentMultipleAnswer->fetch(PDO::FETCH_LAZY))
						{
							$answersStudentArray[]=$questionDictionary[$rowStudentMultipleAnswers['multiple_question_answers_answer']];
						}						
						
						$table->addCell($cellWidth)->addText(implode(", ",$answersStudentArray));
						
						$answersArray=array();
						$stmtMultipleAnswers=$pdo->prepare("SELECT * FROM `multiple_question_variants` WHERE `multiple_question_variants_question`=? AND `multiple_question_variants_correct`=1");
						$stmtMultipleAnswers->execute(array($rowQuestions['question_id']));
						while($rowMultipleAnswers=$stmtMultipleAnswers->fetch(PDO::FETCH_LAZY))
						{
							$answersArray[]=$questionDictionary[$rowMultipleAnswers['multiple_question_variants_id']];
						}
						
						$table->addCell($cellWidth)->addText(implode(", ",$answersArray));
					}
					else
					{
						$stmtStudentOpenQuestion=$pdo->prepare("SELECT * FROM `open_questions_answers` WHERE `open_questions_answers_question`=? AND `open_questions_answers_student`=?");
						$stmtStudentOpenQuestion->execute(array($rowQuestions['question_id'],$student));
						$rowStudentOpenQuestion=$stmtStudentOpenQuestion->fetch(PDO::FETCH_LAZY);
						$studentOpenQuestionAnswer=$rowStudentOpenQuestion['open_questions_answers_answer'];
						$table->addCell($cellWidth)->addText(iconv("cp1251","utf-8",$studentOpenQuestionAnswer));
						
						$stmtOpenQuestion=$pdo->prepare("SELECT * FROM `open_questions` WHERE `open_question_question`=?");
						$stmtOpenQuestion->execute(array($rowQuestions['question_id']));
						$rowOpenQuestion=$stmtOpenQuestion->fetch(PDO::FETCH_LAZY);
						$openQuestion=$rowOpenQuestion['open_question_value'];
						$table->addCell($cellWidth)->addText(iconv("cp1251","utf-8",$openQuestion));
						
					}
						
				}
				
				// ---- Write answers ---------------------------------- end -------------------
		
				
				if ($rowQuestions['question_type']==1)
				{
					$stmtSingleAnswers=$pdo->prepare("SELECT * FROM `single_question_variants` WHERE `single_question_question`=? AND `single_question_correct`=1");
					$stmtSingleAnswers->execute(array($rowQuestions['question_id']));
					$rowSingleAnswers=$stmtSingleAnswers->fetch(PDO::FETCH_LAZY);
					
					$stmtStudentSingleAnswer=$pdo->prepare("SELECT * FROM `single_question_answers` WHERE `single_question_answers_question`=? AND `single_question_answers_student`=?");
					$stmtStudentSingleAnswer->execute(array($rowQuestions['question_id'],$student));
					$rowStudentSingleAnswer=$stmtStudentSingleAnswer->fetch(PDO::FETCH_LAZY);
					
					if($rowSingleAnswers['single_question_id']==$rowStudentSingleAnswer['single_question_answers_answer'])
					{
						$score++;
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
						
						
						
						$answersStudentArray=array();
						$stmtStudentMultipleAnswer=$pdo->prepare("SELECT * FROM `multiple_question_answers` WHERE `multiple_question_answers_question`=? AND `multiple_question_answers_student`=?");
						$stmtStudentMultipleAnswer->execute(array($rowQuestions['question_id'],$student));
						while($rowStudentMultipleAnswers=$stmtStudentMultipleAnswer->fetch(PDO::FETCH_LAZY))
						{
							$answersStudentArray[]=$rowStudentMultipleAnswers['multiple_question_answers_answer'];
						}
						
						sort($answersStudentArray);
						
						if($answersArray==$answersStudentArray)
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
				$table->addCell($numberСellWidth)->addText("");
				$table->addCell($cellWidth)->addText("");
				$table->addCell($cellWidth)->addText("Итого");
				$table->addCell($cellWidth)->addText($score." (".(100*round($score/$totalQuestions,2))."%)");	
			/**/
			$section->addTextBreak(2);
			}	
	}
	

	$stmt=$pdo->prepare("SELECT `report_full_path` FROM `reports_and_tokens_files` WHERE `test`=?");
	$stmt->execute(array($test));
	$row=$stmt->fetch(PDO::FETCH_LAZY);
	$writer = PHPWord_IOFactory::createWriter($word, 'Word2007');
	$writer->save($row['report_full_path']);

  echo json_encode(array('report_full_path'=>$row['report_full_path']));
 ?>
