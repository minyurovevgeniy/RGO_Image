<?php
	date_default_timezone_set('Asia/Yekaterinburg');
	include("./connect.php");
	$tests=array();

	$stmt=$pdo->prepare("SELECT * FROM `tests` ORDER BY `test_title` ASC");
	$stmt->execute();
	while($row=$stmt->fetch(PDO::FETCH_LAZY))
	{
		$tests['tests'][]=
		array
		(
			'id'=>$row['test_id'],
			'title'=>iconv("cp1251","utf-8",$row['test_title']),
			'description'=>iconv("cp1251","utf-8",$row['test_description'])
		);
	}

	echo json_encode($tests);
	include("./disconnect.php");

?>
