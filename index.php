<?php
	/* выключаем кэширование */
	Header("Cache-Control: no-store, no-cache, must-revalidate");
	Header("Pragma: no-cache");
	Header("Last-Modified: " . gmdate("D, d M Y H:i:s") . "GMT");
	Header("Expires: " . date("r"));
?>
<!DOCTYPE html>
<html lang="ru">
    <head>
	    <meta charset="utf-8">
		<meta http-equiv="Cache-Control" content="no-cache">
		<title>Система учёта договоров</title>
		<link rel="stylesheet" href="css/bootstrap.min.css">
		<link rel="stylesheet" href="css/bootstrap-theme.min.css">
	</head>
<body>
	<div class="text-center">
		<a href="config/setting.php">Глобальные настройки</a> :: 
		<a href="contract/dogovora.php">Список договоров</a> :: 
		<a href="company/firms.php">Список контрагентов</a>
	</div>
<h1>Система учёта договоров аренды</h1>
<br /><br /><br />
	<div class="text-center">
		<a href="config/setting.php">Глобальные настройки</a> :: 
		<a href="contract/dogovora.php">Список договоров</a> :: 
		<a href="company/firms.php">Список контрагентов</a>
	</div>
	<script src="../js/bootstrap.min.js"></script>
</body>
</html>