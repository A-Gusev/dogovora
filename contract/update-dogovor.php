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
	$idset=$_REQUEST['id'];
	$nomer=htmlentities(trim($_REQUEST['nomer']));
	$date=htmlentities(trim($_REQUEST['date']));
	$dogovor_type=$_REQUEST['id_type_dog'];
	$name=htmlentities(trim($_REQUEST['name']));
	$date_s=htmlentities(trim($_REQUEST['date-s']));
	$date_po=htmlentities(trim($_REQUEST['date-po']));
	$date_akt=htmlentities(trim($_REQUEST['date-akt']));
	$bank=$_REQUEST['bank'];
	$price=htmlentities(trim($_REQUEST['price']));
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
	`c_bank` = '$bank',
	`c_price` = '$price',
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