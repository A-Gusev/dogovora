<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
		<meta http-equiv="Refresh" content="1; url=dogovora.php">
		<title>Редактирование договоров</title>
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
	$nomer=$_REQUEST['nomer'];
	$date=$_REQUEST['date'];
	$dogovor_type=$_REQUEST['dogovor_type'];
	$company_id=$_REQUEST['company_id'];
	$prim=$_REQUEST['prim'];

	/* подготавливаем запрос к БД */
	$update_sql = "INSERT INTO `admin_arenda`.`contract` (`id`, `nomer`, `date`, `id_type_dog`, `company_id`, `prim`)
	VALUES (NULL, '$nomer', '$date', '$dogovor_type', '$company_id', '$prim')";

	/* отправляем запрос к БД */
	mysqli_query($link, $update_sql) or die("Ошибка: " . mysql_error());

	/* очищаем результаты выборки */
	mysqli_free_result($result);

	/* закрываем подключение */
	mysqli_close($link);

	header('Location:dogovora.php');
?>

	<script src="../js/bootstrap.min.js"></script>
</body>
</html>