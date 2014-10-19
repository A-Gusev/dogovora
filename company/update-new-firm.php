<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
		<meta http-equiv="Refresh" content="3; url=firms.php">
		<title>Создание нового контрагента</title>
		<link rel="stylesheet" href="../css/bootstrap.min.css">
		<link rel="stylesheet" href="../css/bootstrap-theme.min.css">
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
	$type=$_REQUEST['company_type'];
	$name=$_REQUEST['name'];
	$director=$_REQUEST['director'];
	$requisites=$_REQUEST['requisites'];
	$button=$_REQUEST['button'];

	/* подготавливаем запрос к БД */
	$update_sql = "INSERT INTO `admin_arenda`.`company` (`id`, `id_type`, `name`, `director`, `requisites`)
	VALUES (NULL, '$type', '$name', '$director', '$requisites')";

	/* отправляем запрос к БД */
	mysqli_query($link, $update_sql) or die("Ошибка: " . mysql_error());

	$max_sql = "SELECT MAX(`id`) AS `id` FROM `company`";
	$max_mas = mysqli_query($link, $max_sql);
	$id_max = mysqli_fetch_array($max_mas, MYSQLI_NUM);

	/* закрываем подключение */
	mysqli_close($link);

	if ($button == "save") {
		header('Location:firm.php?id='.$id_max['0']);
	}
	else {
		header('Location:firms.php');
	}
?>

	<script src="../js/bootstrap.min.js"></script>
</body>
</html>