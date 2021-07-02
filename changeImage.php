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
		<script type="text/javascript" src="./js/changeImage.js"></script>
	</head>
	<body>
		<div id="header"></div>
		<div id="content">
			<form id="change" method="POST" action="./changeImage_result.php" enctype="multipart/form-data">
				<table>
					<tr>
						<td>Вопрос</td>
						<td>
							<select id="questions" name="question"></select>
						</td>
					</tr>
					<tr>
						<?//<td>Номер заменяемого изображения</td><td><input type="text" value="1" id="old_image" name="old_image"></td>?>
						<td>Номер заменяемого изображения</td><td><select id="old_images" name="selected_image_id"></select></td>
					</tr>
					<tr>
						<td>Новое изображение</td><td><input type="file" name="new_image"></td>
					</tr>
					<tr>
						<td>Новая высота</td><td><input type="text" id="new_height" value="250" name="new_height"></td>
					</tr>
					<tr>
						<td>Очистить таблицу (Да/Нет)</td><td><input type="text" name="shouldEmpty"></td>
					</tr>
					<tr>
						<td>Пароль для ввода</td><td><input type="password" name="input_password"></td>
					</tr>
				</table>
				<input type="submit" value="Изменить" id="upload-image">
			</form>
			<span id="message"></span>
		</div>
		<div id="footer"></div>
	</body>
</html>
