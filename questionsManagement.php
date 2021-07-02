<?php
	include("./checkTest.php");
?>
<html>
	<head>
		<link rel="stylesheet" href="./css/bootstrap.min.css">
		<link rel="stylesheet" href="./css/common.css">
		<link rel="stylesheet" href="./css/index.css">
		<link rel="stylesheet" href="./css/questionsManagement.css">
		<script src="./js/jquery.js"></script>
		<script src="./js/questionsManagement.js"></script>
	</head>
	<body>
	<div id="header"><?php include("./header.php"); ?></div>
	<div id="content">
		<h1>Вопросы</h1>

		<div id="questions">
			<div class="row">
				<div class="col-sm-6 col-md-6 col-sm-6 col-xs-6">
					<iframe src="./loadQuestion.php"></iframe>
				</div>
				<div class="col-sm-6 col-md-6 col-sm-6 col-xs-6">
					<iframe src="./changeImage.php"></iframe>
				</div>
			</div>

			<div class="row">
				<div class="col-sm-2 col-md-2 col-sm-2 col-xs-2">Выбранный тест</div>
				<div class="col-sm-5 col-md-5 col-sm-5 col-xs-5"><div id="chosenTest">Выберите тест</div></div>
			</div>
			<table>
				<tr><td>Пароль для изменения</td><td><input type="password" id="save_password"></td></tr>
				<tr><td>Пароль для удаления</td><td><input type="password" id="delete_password"></td></tr>
				<tr><td>Ручное обновление</td><td><input type="checkbox" id="manual_mode"></td></tr>
			</table>

			<h2>Вопрос</h2>
			<input type="button" id="refreshQuestionsList" value="Обновить список вопросов">
			<div class="row">
				<div class="col-sm-10 col-md-10 col-sm-10 col-xs-10">
					<select id="question"></select>
				</div>
				<div class="col-sm-2 col-md-2 col-sm-2 col-xs-2">
					<input type="button" class="btn" id="choose-question" value="Выбрать">
				</div>
			</div>

			<span id="question-type-name"></span><br>
			<div class="row">
				<div class="col-sm-10 col-md-10 col-sm-10 col-xs-10">
					<input type="text" id="question-text">
				</div>
				<div class="col-sm-2 col-md-2 col-sm-2 col-xs-2">
					<input type="button" class="btn" id="save-question-text" value="Сохранить">
				</div>
			</div>

			<div id="images"></div>
			<div id="open-question-answer-container"></div>

		</div>
		<div id="controls"></div>
	</div>
	<div id="footer"></div>
	</body>
</html>
