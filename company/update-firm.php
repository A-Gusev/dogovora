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

	if (isset($_COOKIE['u-id']) and isset($_COOKIE['hash'])) {
		# Вытаскиваем из БД запись, у которой id равняеться id из кук
		$cook_id=$_COOKIE['u-id']; 
		$sql_u_id = "SELECT * FROM `users` WHERE `u_id`='$cook_id'";
		$result_check = mysqli_query($link, $sql_u_id);
		$row_chek = mysqli_fetch_array($result_check, MYSQLI_ASSOC);

	    if(($row_chek['u_hash'] !== $_COOKIE['hash']) or ($row_chek['u_id'] !== $_COOKIE['u-id'])) {
	        setcookie("u-id", "", time() - 3600*24*30*12, "/");
	        setcookie("hash", "", time() - 3600*24*30*12, "/");
	        print "Кажется что-то пошло не так";
	    }
	    else {
	        $rights=$row_chek['u_rights'];
	    }
	}
	else {
	    //print "Включите печеньки!";
	    $rights=-1;
	}


	/* забираем данные из формы */
	$idset=htmlentities(trim($_REQUEST['id']));
	$type=htmlentities(trim($_REQUEST['company_type']));
	$inn=htmlentities(trim($_REQUEST['inn']));
	$kpp=htmlentities(trim($_REQUEST['kpp']));
	$ogrn=htmlentities(trim($_REQUEST['ogrn']));
	$name=htmlentities(trim($_REQUEST['name']));
	$address=htmlentities(trim($_REQUEST['address']));
	$doljnost=htmlentities(trim($_REQUEST['doljnost']));	
	$director=htmlentities(trim($_REQUEST['director']));
	$director_io=htmlentities(trim($_REQUEST['director_io']));
	$director_r=htmlentities(trim($_REQUEST['director_r']));
	$passport=htmlentities(trim($_REQUEST['passport']));
	$tel=htmlentities(trim($_REQUEST['tel']));
	$mail=htmlentities(trim($_REQUEST['mail']));
	$fax=htmlentities(trim($_REQUEST['fax']));
	$requisites=htmlentities(trim($_REQUEST['requisites']));
	if ($rights == 2) {
		$agreement=htmlentities(trim($_REQUEST['agreement']));
	}
	$notes=htmlentities(trim($_REQUEST['notes']));
	$mail_s_s=htmlentities(trim($_REQUEST['mail_s_s']));
	$mail_s_e=htmlentities(trim($_REQUEST['mail_s_e']));
	$button=htmlentities(trim($_REQUEST['button']));
	if (array_key_exists('problem', $_REQUEST)) {$problem=1;} else {$problem=0;}
	if (array_key_exists('mail_s', $_REQUEST)) {$mail_s=1;} else {$mail_s=0;}
	if (array_key_exists('mail_s_checked', $_REQUEST)) {$mail_s_checked=1;} else {$mail_s_checked=0;}

	/* подготавливаем запрос к БД */
	if ($rights == 2) {
		$update_sql = "UPDATE `admin_arenda`.`firms` SET `f_id_type` = '$type',
		`f_inn` = '$inn',
		`f_kpp` = '$kpp',
		`f_ogrn` = '$ogrn',
		`f_name` = '$name',
		`f_address` = '$address',
		`f_doljnost` = '$doljnost',
		`f_director` = '$director',
		`f_director_io` = '$director_io',
		`f_director_r` = '$director_r',
		`f_passport` = '$passport',
		`f_tel` = '$tel',
		`f_mail` = '$mail',
		`f_fax` = '$fax',
		`f_requisites` = '$requisites',
		`f_agreement` = '$agreement',
		`f_notes` = '$notes',
		`f_problem` = '$problem',
		`f_mail_s` = '$mail_s',
		`f_mail_s_s` = '$mail_s_s',
		`f_mail_s_e` = '$mail_s_e',
		`f_mail_s_checked` = '$mail_s_checked'
		 WHERE `firms`.`f_id` = '$idset'";
	}
	else {
		$update_sql = "UPDATE `admin_arenda`.`firms` SET `f_id_type` = '$type',
		`f_inn` = '$inn',
		`f_kpp` = '$kpp',
		`f_ogrn` = '$ogrn',
		`f_name` = '$name',
		`f_address` = '$address',
		`f_doljnost` = '$doljnost',
		`f_director` = '$director',
		`f_director_io` = '$director_io',
		`f_director_r` = '$director_r',
		`f_passport` = '$passport',
		`f_tel` = '$tel',
		`f_mail` = '$mail',
		`f_fax` = '$fax',
		`f_requisites` = '$requisites',
		`f_notes` = '$notes',
		`f_problem` = '$problem',
		`f_mail_s` = '$mail_s',
		`f_mail_s_s` = '$mail_s_s',
		`f_mail_s_e` = '$mail_s_e',
		`f_mail_s_checked` = '$mail_s_checked'
		 WHERE `firms`.`f_id` = '$idset'";
	}	 

	/* отправляем запрос к БД */
	mysqli_query($link, $update_sql) or die("Ошибка: " . mysql_error());

	/* закрываем подключение */
	mysqli_close($link);

	if ($button == "save") {
		header('Location:firm.php?id='.$idset);
	}
	else {
		header('Location:firms.php');
	}
?>