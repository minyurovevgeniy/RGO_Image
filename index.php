<?php
	header("Content-Type: text/html; charset=utf-8");
?>
<html>
	<head>
		<link type="text/css" rel="stylesheet" href="./css/common.css">
		<link type="text/css" rel="stylesheet" href="./css/index.css">
		<script type="text/javascript" src="./js/jquery.js"></script>
		<script type="text/javascript" src="./js/auth.js"></script>
	</head>
	<body>
		<div id="wrapper">
			<div id="header"></div>
			<div id="content">
				<div id="enter-form">
					<table border="0" cellspacing="0" cellpadding="0">
						<tr><td colspan="2" align="center">Вход</td></tr>
						<tr><td>Логин</td><td><input type="text" id="login"></td></tr>
						<tr><td>Пароль</td><td><input type="password" id="password"></td></tr>
						<tr><td colspan="2" align="center"><input type="button" id="submit" value="Вход"></td></tr>
					</table>
				</div>
			</div>
			<div id="footer"></div>
		</div>
	</body>
</html>