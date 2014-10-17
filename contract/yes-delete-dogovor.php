<!DOCTYPE html>
<html>
    <head>
	    <meta charset="utf-8">
	    <meta HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
	    <title>Редактирование договоров</title>
		<link rel="stylesheet" href="css/bootstrap.min.css">
		<link rel="stylesheet" href="css/bootstrap-theme.min.css">
		<meta http-equiv="Refresh" content="1; url=dogovora.php">
	</head>
<body>
<?php 
	/* выключаем кэширование */
	Header("Cache-Control: no-store, no-cache, must-revalidate");
	Header("Pragma: no-cache");
	Header("Last-Modified: " . gmdate("D, d M Y H:i:s") . "GMT");
	Header("Expires: " . date("r"));

	require_once '../login.php';
	$link=mysqli_connect($host, $user, $password, $db);
	
	/* проверка подключения */
	if (mysqli_connect_errno()) {
	    printf("Не удалось подключиться: %s\n", mysqli_connect_error());
	    exit();
	}

	/* установка кодировки utf8 */
	if (!$link->set_charset("utf8")) {
	    printf("Ошибка при загрузке набора символов utf8: %s\n", $link->error);
	}

	/* забираем данные из формы */
	$idset = $_REQUEST['id'];

	/* подготавливаем запрос к БД */
	$delete_sql = "DELETE FROM `admin_arenda`.`contract` WHERE `contract`.`id` = '$idset'";

	/* отправляем запрос к БД */
	mysqli_query($link, $delete_sql) or die("Ошибка: " . mysql_error());
	echo '<p>Запись успешно удалена!</p>';
	
	echo '<br /><br /><p><a href="../index.php">Home</a> :: <a href="dogovora.php">Список договоров</a> :: <a href="new-dogovor.php">Создать новый договор</a></p>';	
	
	/* закрываем подключение */
	mysqli_close($link);
?>
 
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
</body>
</html>