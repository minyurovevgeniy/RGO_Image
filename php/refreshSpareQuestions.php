<?php 
	session_start();
	date_default_timezone_set('Asia/Yekaterinburg');
	include("./connect.php");
	$questions=array();
	$test=$_SESSION['currentTest'];
	$answer="";
	$stmt=$pdo->prepare("SELECT * FROM `questions` WHERE `question_test`=?  AND `question_type`=3 ORDER BY `question_text` ASC");
	$stmt->execute(array($test));
	while($row=$stmt->fetch(PDO::FETCH_LAZY))
	{
		$answer="";
		
		$stmtSecond=$pdo->prepare("SELECT * FROM `answer_variants` WHERE `test`=? AND `question`=? ORDER BY `variant_id` ASC");
		$stmtSecond->execute(array($test,$row['question_id']));
		$variants=array();
		while($rowSecond=$stmtSecond->fetch(PDO::FETCH_LAZY))
		{
			$answer=$rowSecond['variant'];			
		}

		$questions['questions'][]=
		array
		(
			'id'=>$row['question_id'],
			'text'=>iconv("cp1251","utf-8",$row['question_text']),
			'variant'=>iconv("cp1251","utf-8",$answer)
		);
		
	}
	
	echo json_encode($questions);
	
?>