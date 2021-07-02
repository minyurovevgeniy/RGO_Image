<html>
	<head>
		<link rel="stylesheet" href="./css/bootstrap.min.css">
		<link rel="stylesheet" href="./css/common.css">
		<link rel="stylesheet" href="./css/studentsManagement.css">
		<script src="./js/jquery.js"></script>
		<script src="./js/studentsManagement.js"></script>
	</head>
	<body>
	<div id="header"><?php include("./header.php"); ?></div>	
	<div id="content">
		<h1>Студенты</h1>
		
		<div class="row">
			<div class="col-sm-3 col-md-3 col-sm-3 col-xs-3">Фамилия</div>
			<div class="col-sm-3 col-md-3 col-sm-3 col-xs-3">Имя</div>
			<div class="col-sm-3 col-md-3 col-sm-3 col-xs-3">Отчество</div>
		</div>
		<div class="row">
			<div class="col-sm-3 col-md-3 col-sm-3 col-xs-3"><input type="text" id="newSurname"></div>
			<div class="col-sm-3 col-md-3 col-sm-3 col-xs-3"><input type="text" id="newName"></div>
			<div class="col-sm-3 col-md-3 col-sm-3 col-xs-3"><input type="text" id="newPatronymic"></div>
			<div class="col-sm-2 col-md-2 col-sm-2 col-xs-2"><input type="button" id="addStudentManually" value="Добавить"></div>
		</div>
		
		<div id="students">
		
			<table>
				<tr><td>Пароль для изменения</td><td><input type="password" id="save_password"></td></tr>
				<tr><td>Пароль для удаления</td><td><input type="password" id="delete_password"></td></tr>
				<tr><td>Ручное обновление</td><td><input type="checkbox" id="manual_mode"></td></tr>
			</table>
			<div id="students-header">
				<input type="button" id="refreshStudents" value="Обновить список групп">
				<br>
				<input type="button" id="deleteAllGroups" value="Удалить всех студентов">
				<div class="row">
					<div class="col-sm-1 col-md-1 col-sm-1 col-xs-1">№</div>
					<div class="col-sm-2 col-md-2 col-sm-2 col-xs-2">Фамилия</div>
					<div class="col-sm-2 col-md-2 col-sm-2 col-xs-2">Имя</div>
					<div class="col-sm-2 col-md-2 col-sm-2 col-xs-2">Отчество</div>
					<div class="col-sm-4 col-md-4 col-sm-4 col-xs-4">Действия</div>
				</div>
			</div>
			<div id="students-list"></div>
		</div>
	</div>
	<div id="footer"></div>
	</body>
</html>