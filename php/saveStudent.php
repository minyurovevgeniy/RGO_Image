<?php
	date_default_timezone_set('Asia/Yekaterinburg');
	include("./connect.php");

	$response="ФИО студента изменено";
	


	$id=$_POST['id'];
	$newSurname=iconv("utf-8","cp1251",$_POST['new_surname']);
	$newName=iconv("utf-8","cp1251",$_POST['new_name']);
	$newPatronymic=iconv("utf-8","cp1251",$_POST['new_patronymic']);
	
		
		/*
	$id=$_GET['id'];
	$newSurname=iconv("utf-8","cp1251",$_GET['new_surname']);
	$newName=iconv("utf-8","cp1251",$_GET['new_name']);
	$newPatronymic=iconv("utf-8","cp1251",$_GET['new_patronymic']);
	*/
	$stmt=$pdo->prepare("SELECT COUNT(*) FROM `students` WHERE `student_surname`=? AND `student_name`=? AND `student_patronymic`=? AND `student_id`<>?");
	$stmt->execute(array($newSurname,$newName,$newPatronymic,$id));
	$row=$stmt->fetch(PDO::FETCH_LAZY);
	if ($row['COUNT(*)']<1)
	{
		$stmt=$pdo->prepare("UPDATE `students` SET `student_surname`=?, `student_name`=?, `student_patronymic`=? WHERE `student_id`=?");
		$stmt->execute(array($newSurname,$newName,$newPatronymic,$id));
	}
	else
	{
		$response="Такой студент уже существует";
	}
	
	echo json_encode(array("response"=>$response));
?>