<?php
	include("./checkTest.php");
?>
<html>
	<head>
		<link rel="stylesheet" href="./css/bootstrap.min.css">
		<link rel="stylesheet" href="./css/common.css">
		<link rel="stylesheet" href="./css/groupsManagement.css">
		<script src="./js/jquery.js"></script>
		<script src="./js/testRunManagement.js"></script>
	</head>
	<body>
	<div id="header"><?php include("./header.php"); ?></div>
	<div id="content">
		<h1>Выполнение тестов</h1>
		<div id="tests">
			<div class="row">
				<div class="col-sm-2 col-md-2 col-sm-2 col-xs-2">Выбранный тест</div>
				<div class="col-sm-5 col-md-5 col-sm-5 col-xs-5"><div id="chosenTest">Выберите тест</div></div>
			</div>
			<table>
				<tr><td>Пароль для изменения</td><td><input type="password" id="save_password"></td></tr>
				<tr><td>Пароль для удаления</td><td><input type="password" id="delete_password"></td></tr>
			</table>
			<div>
				<table>
					<tr>
						<td>Текущий ключ теста</td>
						<td><div id="testToken"></div></td>
						<td><div id="testsToken"><input type="button" id="generateTestToken" value="Сгенерировать"></div></td>
					</tr>
					<tr>
						<td>Текущие ключи студентов (файл)</td>
						<td><div id="studentToken"><a id="studentsTokenFile" href="javascript:;">Скачать</a></div></td>
						<td><div id="studentsToken"><input type="button" id="generateStudentsToken" value="Сгенерировать"></div></td>
					</tr>
					<tr>
						<td>Длительность теста (в минутах)</td>
						<td><input type="text" id="duration"></td>
						<td><input type="button" id="saveDuration" value="Сохранить"></td>
					</tr>
					<tr>
						<td></td>
						<td></td>
						<td><div id="runTestButton"><input type="button" id="runTest" value="Запустить"></div></td>
					</tr>
					<tr>
						<td>Общий отчет</td>
						<td><a id="studentsReportShortFile" href="javascript:;">Скачать</a></td>
						<td><input type="button" id="generateShortReport" value="Сгенерировать"></td>
					</tr>
					<tr>
						<td>Детализированный отчет</td>
						<td><a id="studentsReportFullFile" href="javascript:;">Скачать</a></td>
						<td><input type="button" id="generateFullReport" value="Сгенерировать"></td>
					</tr>
					
				</table>
			</div>
			<div>
				<div id="tokens-header">
				<input type="button" id="refreshTokens" value="Обновить список ключей">
				<div class="row">
					<div class="col-sm-1 col-md-1 col-sm-1 col-xs-1">№</div>
					<div class="col-sm-5 col-md-5 col-sm-5 col-xs-5">ФИО</div>
					<div class="col-sm-3 col-md-3 col-sm-3 col-xs-3">Ключ</div>
				</div>
			</div>
			<div id="tokens-list"></div>
			</div>
		</div>
	</div>
	<div id="footer"></div>
	</body>
</html>
