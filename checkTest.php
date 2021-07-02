<?php
	session_start();
	if ($_SESSION['currentTest']<0)
	{
		echo 'Выберите тест <a href="http://rgo-image.xn--100-5cdnry0bhchmgqi5d.xn--p1ai/testsManagement.php">здесь</a><br>';
		die("Ошибка");
	}
?>