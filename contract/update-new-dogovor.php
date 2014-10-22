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

	/* забираем данные из формы */
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
	$update_sql = "INSERT INTO `admin_arenda`.`contract` (`c_id`, `c_nomer`, `c_date`, `c_date-s`, `c_date-po`, `c_date-akt`, `c_number`, `c_m2`, `c_id_type_dog`, `c_company_id`, `c_prim`)
	VALUES (NULL, '$nomer', '$date', '$date_s', '$date_po', '$date_akt', '$number', '$m2', '$dogovor_type', '$name', '$prim')";

	/* отправляем запрос к БД */
	mysqli_query($link, $update_sql) or die("Ошибка: " . mysql_error());

	$max_sql = "SELECT MAX(`c_id`) AS `c_id` FROM `contract`";
	$max_mas = mysqli_query($link, $max_sql);
	$id_max = mysqli_fetch_array($max_mas, MYSQLI_NUM);

	/* закрываем подключение */
	mysqli_close($link);

	if ($button == "save") {
		header('Location:dogovor.php?id='.$id_max['0']);
	}
	else {
		header('Location:dogovora.php');
	}
?>
	<script src="../js/bootstrap.min.js"></script>
</body>
</html>