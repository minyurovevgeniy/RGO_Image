<?php
	session_start();
	//if ($_SESSION['mdf843hrk52']<=0 or !isset($_SESSION['mdf843hrk52'])) die("OK");
	header("Content-Type: text/html; charset=utf-8");
?>
<html>
	<head>
		<link rel="stylesheet" type="type/css" href="./css/bootstrap.css">
		<link rel="stylesheet" type="type/css" href="./css/common.css">
		<script type="text/javascript" src="./js/jquery.js"></script>
		<script type="text/javascript" src="./js/loadQuestions.js"></script>
	</head>
	<body>
		<div id="header"></div>
		<div id="content">
			<form action="./loadGroups_result.php" method="post" enctype="multipart/form-data">
					<table>
						<tr>
							<td>Номер листа</td><td><input type="text" name="worksheet_number"></td>
						</tr>
						<tr>
							<td>Номер первой строки</td><td><input type="text" name="min_row"></td>
						</tr>
						<tr>
							<td>Номер последней строки</td><td><input type="text" name="max_row"></td>
						</tr>
						<tr>
							<td>Номер колонки с название группы</td><td><input type="text" name="group_column"></td>
						</tr>
						<tr>
							<td>Очистить таблицу (Да/Нет)</td><td><input type="text" name="shouldEmpty"></td>
						</tr>
						<tr>
							<td>Пароль для ввода</td><td><input type="password" name="input_password"></td>
						</tr>
					</table>
					<input type="file" name="profiles"><br>
					<input type="submit" value="Загрузить" id="upload-files">
				</form>
				<span id="message"></span>
		</div>
		<div id="footer"></div>
	</body>
</html>