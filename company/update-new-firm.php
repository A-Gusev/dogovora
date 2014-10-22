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
<meta http-equiv="Refresh" content="3; url=firms.php">
				<title>Создание нового контрагента</title>
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
	$type=htmlentities(trim($_REQUEST['company_type']));
	$inn=htmlentities(trim($_REQUEST['inn']));
	$kpp=htmlentities(trim($_REQUEST['kpp']));
	$ogrn=htmlentities(trim($_REQUEST['ogrn']));
	$name=htmlentities(trim($_REQUEST['name']));
	$address=htmlentities(trim($_REQUEST['address']));
	$director=htmlentities(trim($_REQUEST['director']));
	$director_io=htmlentities(trim($_REQUEST['director_io']));
	$director_r=htmlentities(trim($_REQUEST['director_r']));
	$passport=htmlentities(trim($_REQUEST['passport']));
	$tel=htmlentities(trim($_REQUEST['tel']));
	$mail=htmlentities(trim($_REQUEST['mail']));
	$fax=htmlentities(trim($_REQUEST['fax']));
	$requisites=htmlentities(trim($_REQUEST['requisites']));
	$agreement=htmlentities(trim($_REQUEST['agreement']));
	$notes=htmlentities(trim($_REQUEST['notes']));
	$mail_s_s=htmlentities(trim($_REQUEST['mail_s_s']));
	$mail_s_e=htmlentities(trim($_REQUEST['mail_s_e']));
	$button=htmlentities(trim($_REQUEST['button']));
	if (array_key_exists('problem', $_REQUEST)) {$problem=1;} else {$problem=0;}
	if (array_key_exists('mail_s', $_REQUEST)) {$mail_s=1;} else {$mail_s=0;}
	if (array_key_exists('mail_s_checked', $_REQUEST)) {$mail_s_checked=1;} else {$mail_s_checked=0;}


	/* подготавливаем запрос к БД */
	$update_sql = "INSERT INTO `admin_arenda`.`firms` (`f_id`, `f_id_type`, `f_inn`, `f_kpp`, `f_ogrn`, `f_name`, `f_address`, `f_director`, `f_director_io`, `f_director_r`, `f_passport`, `f_tel`, `f_mail`, `f_fax`, `f_requisites`, `f_agreement`, `f_notes`, `f_problem`, `f_mail_s`, `f_mail_s_s`, `f_mail_s_e`, `f_mail_s_checked`)
	VALUES (NULL, '$type', '$inn', '$kpp', '$ogrn', '$name', '$address', '$director', '$director_io', '$director_r', '$passport', '$tel', '$mail', '$fax', '$requisites', '$agreement', '$notes', '$problem', '$mail_s', '$mail_s_s', '$mail_s_e', '$mail_s_checked')";

	/* отправляем запрос к БД */
	mysqli_query($link, $update_sql) or die("Ошибка: " . mysql_error());

	$max_sql = "SELECT MAX(`f_id`) AS `f_id` FROM `firms`";
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