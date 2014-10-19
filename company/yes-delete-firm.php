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
		<title>Удаление договоров</title>
		<link rel="stylesheet" href="css/bootstrap.min.css">
		<link rel="stylesheet" href="css/bootstrap-theme.min.css">
	</head>
<body>
<?php
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
	$delete_sql = "DELETE FROM `admin_arenda`.`company` WHERE `company`.`id` = '$idset'";
	
	$sql_kol = "SELECT COUNT(`nomer`)
	FROM `contract` JOIN `company` ON `contract`.`company_id` = `company`.`id`
	WHERE `company`.`id`='$idset'";
	$kol = mysqli_query($link, $sql_kol);
	$kol2 = mysqli_fetch_array($kol, MYSQLI_NUM);

	/* отправляем запрос к БД */
	if ($kol2['0']==0) {
	mysqli_query($link, $delete_sql) or die("Ошибка: " . mysql_error());
	echo '<p>Запись успешно удалена!</p>';}

	/* закрываем подключение */
	mysqli_close($link);

	header('Location:firms.php');
?>
	<script src="../js/bootstrap.min.js"></script>
</body>
</html>