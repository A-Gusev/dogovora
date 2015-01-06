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
	$delete_sql = "DELETE FROM `admin_arenda`.`contract` WHERE `contract`.`c_id` = '$idset'";

	/* отправляем запрос к БД */
	mysqli_query($link, $delete_sql) or die("Ошибка: " . mysql_error());
	echo '<p>Запись успешно удалена!</p>';

	/* закрываем подключение */
	mysqli_close($link);

	header('Location:dogovora.php');
?>