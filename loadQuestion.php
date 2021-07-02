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
		<script type="text/javascript" src="./js/loadQuestion.js"></script>
	</head>
	<body>
		<div id="header"></div>
		<div id="content">
			<form id="upload" method="POST" action="./loadQuestion_result.php" enctype="multipart/form-data">
				<table>
					<tr>
						<td>Тип вопроса</td>
						<td>
							<select id="question_type" name="question_type">
								<option value="1">Единственный ответ</option>
								<option value="2">Несколько ответов</option>
								<option value="3">Свободный ответ</option>
							</select>
						</td>
					</tr>
					<tr>
						<td>Текст вопроса</td><td><input type="text" id="question_text" name="question_text"></td>
					</tr>
					<tr id="image_minimum_width_container">
						<td>Новая высота</td><td><input type="text" id="new_height" value="250" name="max_height"></td>
					</tr>
					<tr id="image_count_container">
						<td>Количество изображений</td><td><input type="text" id="images_count" name="images_count"></td>
					</tr>
					<tr id="right_image_container">
						<td>Правильное изображение</td><td><input type="text" id="right_image" name="right_image"></td>
					</tr>
					<tr id="right_answer_container">
						<td>Правильный ответ</td><td><input type="text" id="right_answer" name="right_answer"></td>
					</tr>
				</table>
				<div id="images"></div>
				<table>
					<tr>
						<td>Очистить таблицу (Да/Нет)</td><td><input type="text" name="shouldEmpty"></td>
					</tr>
					<tr>
						<td>Пароль для ввода</td><td><input type="password" name="input_password"></td>
					</tr>
				</table>
				<input type="submit" value="Загрузить" id="upload-files">
			</form>
		</div>
		<div id="footer"></div>
	</body>
</html>
