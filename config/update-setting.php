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
	$company_name=$_REQUEST['company_name'];
	$job_title=$_REQUEST['job_title'];
	$job_title_r=$_REQUEST['job_title_r'];
	$director=$_REQUEST['director'];
	$director_io=$_REQUEST['director_io'];
	$director_r=$_REQUEST['director_r'];
	$bank_account_1=$_REQUEST['bank_account-1'];
	$bank_account_2=$_REQUEST['bank_account-2'];

	/* подготавливаем запрос к БД */
	$update_sql = "UPDATE `admin_arenda`.`settings` SET `s_company_name` = '$company_name', `s_job_title` = '$job_title', `s_job_title_r` = '$job_title_r', `s_director` = '$director', `s_director_io` = '$director_io', `s_director_r` = '$director_r', `s_bank_account-1` = '$bank_account_1', `s_bank_account-2` = '$bank_account_2' WHERE `settings`.`s_id` = '$idset';";

	/* отправляем запрос к БД */
	mysqli_query($link, $update_sql) or die("Ошибка: " . mysql_error());
//	echo '<p>Запись успешно обновлена!</p>';

//	echo '<p><a href="setting.php">Назад</a><br /><br />';
//	echo '<a href="../index.php">Home</a> :: <a href="setting.php">Глобальные настройки</a></p>';

	/* закрываем подключение */
	mysqli_close($link);

	header('Location: setting.php');
?>