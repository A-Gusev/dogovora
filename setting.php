<!DOCTYPE html>
<html>
    <head>
	    <meta charset="utf-8">
	    <meta HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
	    <title>Редактирование глобальных настроек</title>
		<link rel="stylesheet" href="css/bootstrap.min.css">
		<link rel="stylesheet" href="css/bootstrap-theme.min.css">
	</head>
<body>
<?php
	/* выключаем кэширование */
	Header("Cache-Control: no-store, no-cache, must-revalidate");
	Header("Pragma: no-cache");
	Header("Last-Modified: " . gmdate("D, d M Y H:i:s") . "GMT");
	Header("Expires: " . date("r"));
	
	require_once 'login.php';
	$link = mysqli_connect($host, $user, $password, $db);
	
	/* проверка подключения */
	if (mysqli_connect_errno()) {
	    echo 'Не удалось подключиться: '. mysqli_connect_error();
	    exit();
	}

	/* установка кодировки utf8 */	
	if (!$link->set_charset("utf8")) {
	    echo 'Ошибка при загрузке набора символов utf8: '.$link->error;
	}
	
	$query = "SELECT * FROM `gl_settings` WHERE id=1";
	$result = mysqli_query($link, $query);
	
	/* Получение ассоциативного массива */
	$row = mysqli_fetch_array($result, MYSQLI_ASSOC);

	/* вывод в форму */	
	echo '<form action="update-setting.php" method="post" name="u-setting">';
	echo '<legend>Редактирование глобальных настроек</legend>';
	echo '<input type="hidden" name="id" value="'.$row['id'].'">';
	echo '<p>Название компании:<br /><input type="text" name="company_name" size="30" value="'.$row['company_name'].'"></p>';
	echo '<p>Должность директора:<br /><input type="text" name="job_title" size="30" value="'.$row['job_title'].'"></p>';
	echo '<p>Должность директора (родительный):<br /><input type="text" name="job_title_r" size="30" value="'.$row['job_title_r'].'"></p>';
	echo '<p>ФИО директора<br /><input type="text" name="director" size="30" value="'.$row['director'].'"></p>';
	echo '<p>ФИО директора (инициалы)<br /><input type="text" name="director_io" size="30" value="'.$row['director_io'].'"></p>';
	echo '<p>ФИО директора (родительный)<br /><input type="text" name="director_r" size="30" value="'.$row['director_r'].'"></p>';	
	echo '<p>Реквизиты №1<br /><textarea rows="5" cols="35" name="bank_account-1">'.$row['bank_account-1'].'</textarea></p>';
	echo '<p>Реквизиты №2<br /><textarea rows="5" cols="35" name="bank_account-2">'.$row['bank_account-2'].'</textarea></p>';
	echo '<br /><input id="submit" type="submit" value="Редактировать настройки"></form>';
	
	echo '<br /><p><a href="/">Назад</a><br /><br />';
	echo '<a href="index.php">Home</a>';	
	
	/* очищаем результаты выборки */
	mysqli_free_result($result);
	
	/* закрываем подключение */
	mysqli_close($link);
?>
 
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
</body>
</html>