<?php
	date_default_timezone_set('Asia/Yekaterinburg');

	$studentUTF8=$_POST['student'];

	$student=iconv("utf-8","cp1251",$studentUTF8);

	include("./randomStudentTokenString.php");

	include("./connect.php");


	if (mb_strlen($studentUTF8)>0)
	{
		if (preg_match('/[^а-яА-ЯЁёa-zA-Z0-9]/u', $studentUTF8)>0)
		{
			$response="Должны быть только латиница, кириллица и цифры";
		}
		else
		{
			$stmt=$pdo->prepare("SELECT COUNT(*) FROM `students` WHERE `student_nickname`=?");
			$stmt->execute(array($student));
			$row=$stmt->fetch(PDO::FETCH_LAZY);
			$studentsCount=$row['COUNT(*)'];
			
			if ($studentsCount>0)
			{
				$response="Псевдоним уже используется";
			}
			else
			{
				$stmt=$pdo->prepare("INSERT INTO `students` SET `student_nickname`=?, `student_token`=?");
				$stmt->execute(array($student,iconv("utf-8","cp1251",randomStudentToken())));
				$response="Псевдоним создан";
			}
		}
	}
	else
	{
			$response="Псевдоним должен быть непустым";
	}

	echo json_encode(array("response"=>$response));
?>
