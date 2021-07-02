<?php 
	date_default_timezone_set('Asia/Yekaterinburg');
	include("./connect.php");
	$groups=array();
	
	$stmt=$pdo->prepare("SELECT * FROM `groups` ORDER BY `group_title` ASC");
	$stmt->execute();
	while($row=$stmt->fetch(PDO::FETCH_LAZY))
	{
		$groups['groups'][]=
		array
		(
			'id'=>$row['group_id'],
			'title'=>iconv("cp1251","utf-8",$row['group_title'])
		);
	}
	
	echo json_encode($groups);
	include("./disconnect.php");

?>