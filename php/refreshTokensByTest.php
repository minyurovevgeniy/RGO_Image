<?php
	session_start();
	date_default_timezone_set('Asia/Yekaterinburg');
	include("./connect.php");
	
	$groups=array();
	$students=array();
	$response=array();
	
	$test=$_SESSION['currentTest'];
	$stmt=$pdo->prepare("SELECT * FROM `tests_and_groups` WHERE `tests_and_groups_test`=?");
	$stmt->execute(array($test));
	while($row=$stmt->fetch(PDO::FETCH_LAZY))
	{
		$groups[]=$row['tests_and_groups_group'];
	}
	
	foreach($groups as $value)
	{
		$stmt=$pdo->prepare("SELECT * FROM `students` WHERE `student_group`=?");
		$stmt->execute(array($value));
		while($row=$stmt->fetch(PDO::FETCH_LAZY))
		{
			$students[$row['student_name'].' '.$row['student_surname'].' '.$row['student_patronymic']]=$row['student_id'];
		}
	}
	
	foreach($students as $key=>$value)
	{
		$stmt=$pdo->prepare("SELECT * FROM `students_tokens` WHERE `student`=?");
		$stmt->execute(array($value));
		while($row=$stmt->fetch(PDO::FETCH_LAZY))
		{
			$response['tokens'][]=array
			(
				'id'=>$row['students_token_id'],
				'name'=>iconv("cp1251","utf-8",$key),
				'token'=>iconv("cp1251","utf-8",$row['student_token'])
			);
		}
	}
	
	echo json_encode($response);
?>