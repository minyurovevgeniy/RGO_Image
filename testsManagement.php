<?php
	session_start();
	if ($_SESSION['login']!="true")
	{
		die("OK");
	}
?>
<html>
	<head>
		<link rel="stylesheet" href="./css/bootstrap.min.css">
		<link rel="stylesheet" href="./css/common.css">
		<link rel="stylesheet" href="./css/groupsManagement.css">
		<script src="./js/jquery.js"></script>
		<script src="./js/testsManagement.js"></script>
	</head>
	<body>
	<div id="header"><?php include("./header.php"); ?></div>
	<div id="content">
		<h1>Тесты</h1>
		<div class="row">
			<div class="col-sm-2 col-md-2 col-sm-2 col-xs-2">Новый тест</div>
			<div class="col-sm-8 col-md-8 col-sm-8 col-xs-8"><input type="text" id="newTest"></div>
		</div>
		<div class="row">
			<div class="col-sm-2 col-md-2 col-sm-2 col-xs-2">Описание теста</div>
			<div class="col-sm-8 col-md-8 col-sm-8 col-xs-8"><input type="text" id="newTestDescription"></div>
		</div>
		<div class="row">
			<div class="col-sm-2 col-md-2 col-sm-2 col-xs-2"><input type="button" id="addTestManually" value="Добавить"></div>
		</div>

		<div id="tests">
			<div class="row">
				<div class="col-sm-2 col-md-2 col-sm-2 col-xs-2">Тесты</div>
				<div class="col-sm-5 col-md-5 col-sm-5 col-xs-5"><select id="testsToChoose"></select></div>
				<div class="col-sm-3 col-md-3 col-sm-3 col-xs-3"><input type="button" id="chooseTest" value="Выбрать"></div>
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
			<div id="tests-header">
				<input type="button" id="refreshTests" value="Обновить список тестов">
				<br>
				<input type="button" id="deleteAllTests" value="Удалить все тесты">
				<div class="row">
					<div class="col-sm-1 col-md-1 col-sm-1 col-xs-1">№</div>
					<div class="col-sm-3 col-md-3 col-sm-3 col-xs-3">Название</div>
					<div class="col-sm-4 col-md-4 col-sm-4 col-xs-4">Описание</div>
					<div class="col-sm-4 col-md-4 col-sm-4 col-xs-4">Действия</div>
				</div>
			</div>
			<div id="tests-list"></div>
		</div>
	</div>
	<div id="footer"></div>
	</body>
</html>
