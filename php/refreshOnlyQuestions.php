<?php 
	session_start();
	date_default_timezone_set('Asia/Yekaterinburg');
	include("./connect.php");
	$questions=array();
	$test=$_SESSION['currentTest'];
	
	$stmt=$pdo->prepare("SELECT * FROM `questions` WHERE `question_test`=?  AND `question_type`=1 ORDER BY `question_text` ASC");
	$stmt->execute(array($test));
	while($row=$stmt->fetch(PDO::FETCH_LAZY))
	{
		$correctAnswerPosition=1;
		
		$stmtSecond=$pdo->prepare("SELECT * FROM `answer_variants` WHERE `test`=? AND `question`=? ORDER BY `variant_id` ASC");
		$stmtSecond->execute(array($test,$row['question_id']));
		$variants=array();
		while($rowSecond=$stmtSecond->fetch(PDO::FETCH_LAZY))
		{
			$variants[]=$rowSecond['variant'];
		}
		$variantsString=implode(";",$variants);

		
		$stmtSecond=$pdo->prepare("SELECT * FROM `answer_variants` WHERE `test`=? AND `question`=? AND `is_correct`=?");
		$stmtSecond->execute(array($test,$row['question_id'],iconv("utf-8","cp1251","correct")));
		$row2=$stmtSecond->fetch(PDO::FETCH_LAZY);
		$correctAnswerPosition=$row2['variant'];

		$questions['questions'][]=
		array
		(
			'id'=>$row['question_id'],
			'text'=>iconv("cp1251","utf-8",$row['question_text']),
			'answer'=>iconv("cp1251","utf-8",$correctAnswerPosition),
			'variants'=>iconv("cp1251","utf-8",$variantsString)
		);
		
	}
	
	echo json_encode($questions);
?>