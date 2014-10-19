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
		<meta http-equiv="Refresh" content="1; url=setting.php">
		<title>Редактирование глобальных настроек</title>
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
	$update_sql = "UPDATE `admin_arenda`.`gl_settings` SET `company_name` = '$company_name', `job_title` = '$job_title', `job_title_r` = '$job_title_r', `director` = '$director', `director_io` = '$director_io', `director_r` = '$director_r', `bank_account-1` = '$bank_account_1', `bank_account-2` = '$bank_account_2' WHERE `gl_settings`.`id` = '$idset';";

	/* отправляем запрос к БД */
	mysqli_query($link, $update_sql) or die("Ошибка: " . mysql_error());
	echo '<p>Запись успешно обновлена!</p>';

	echo '<p><a href="setting.php">Назад</a><br /><br />';
	echo '<a href="../index.php">Home</a> :: <a href="setting.php">Глобальные настройки</a></p>';

	/* очищаем результаты выборки */
	mysqli_free_result($result);

	/* закрываем подключение */
	mysqli_close($link);

	header('Location:setting.php');
?>
	<script src="../js/bootstrap.min.js"></script>
</body>
</html>