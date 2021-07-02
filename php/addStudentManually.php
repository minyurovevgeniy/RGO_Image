<?php
	date_default_timezone_set('Asia/Yekaterinburg');
	include("./connect.php");
	
	$newName=iconv("utf-8","cp1251",$_POST['name']);
	$newSurname=iconv("utf-8","cp1251",$_POST['surname']);
	$newPatronymic=iconv("utf-8","cp1251",$_POST['patronymic']);
	
	$response="Студент добавлен";
	
	$stmt=$pdo->prepare("SELECT COUNT(*) FROM `students` WHERE `student_surname`=? AND `student_name`=? AND `student_patronymic`=?");
	$stmt->execute(array($newSurname,$newName,$newPatronymic));
	$row=$stmt->fetch(PDO::FETCH_LAZY);
	if ($row['COUNT(*)']<1)
	{
		$stmt=$pdo->prepare("INSERT INTO `students` SET `student_surname`=?, `student_name`=?, `student_patronymic`=?");
		$stmt->execute(array($newSurname,$newName,$newPatronymic));
	}
	else
	{
		$response="Такой студент уже существует";
	}
	
	echo json_encode(array("response"=>$response));
?>