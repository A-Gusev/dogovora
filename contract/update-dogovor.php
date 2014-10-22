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
		<meta http-equiv="Refresh" content="1; url=dogovora.php">
		<title>Редактирование договоров</title>
		<link rel="stylesheet" href="../css/bootstrap.min.css">
		<link rel="stylesheet" href="../css/bootstrap-theme.min.css">
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

// id=1&nomer=19&date=2014-09-22&id_type_dog=1&name=1&date-s=2014-10-01&date-po=2014-10-10&date-akt=2014-10-01&number=12&m2=23&prim=%D0%9F%D1%80%D0%B8%D0%BC%D0%B5%D1%87%D0%B0%D0%BD%D0%B8%D0%B5+1&id=1&button=save


	/* забираем данные из формы */
	$idset=htmlentities(trim($_REQUEST['id']));
	$nomer=htmlentities(trim($_REQUEST['nomer']));
	$date=htmlentities(trim($_REQUEST['date']));
	$dogovor_type=htmlentities(trim($_REQUEST['id_type_dog']));
	$name=htmlentities(trim($_REQUEST['name']));
	$date_s=htmlentities(trim($_REQUEST['date-s']));
	$date_po=htmlentities(trim($_REQUEST['date-po']));
	$date_akt=htmlentities(trim($_REQUEST['date-akt']));
	$number=htmlentities(trim($_REQUEST['number']));
	$m2=htmlentities(trim($_REQUEST['m2']));
	$prim=htmlentities(trim($_REQUEST['prim']));
	$button=htmlentities(trim($_REQUEST['button']));

	/* подготавливаем запрос к БД */
	$update_sql = "UPDATE `admin_arenda`.`contract` SET `c_nomer` = '$nomer',
	`c_date` = '$date',
	`c_date-s` = '$date_s',
	`c_date-po` = '$date_po',
	`c_date-akt` = '$date_akt',
	`c_number` = '$number',
	`c_m2` = '$m2',
	`c_id_type_dog` = '$dogovor_type',
	`c_company_id` = '$name',
	`c_prim` = '$prim'
	WHERE `contract`.`c_id` = '$idset'";
	
	/* отправляем запрос к БД */
	mysqli_query($link, $update_sql) or die("Ошибка: " . mysql_error());	

	/* закрываем подключение */
	mysqli_close($link);

	if ($button == "save") {
		header('Location:dogovor.php?id='.$idset);
	}
	else {
		header('Location:dogovora.php');
	}
?>
	<script src="../js/bootstrap.min.js"></script>
</body>
</html>