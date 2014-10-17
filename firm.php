<!DOCTYPE html>
<html>
    <head>
	    <meta charset="utf-8">
	    <meta HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
	    <title>Редактирование данных контрагента</title>
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

	/* забираем данные из формы */
	$idset=$_REQUEST['id'];

	/* подготавливаем запрос к БД */
	$query = "SELECT * FROM `company`
	WHERE  `company`.`id`='$idset'";
	$result = mysqli_query($link, $query);

	/* Получение ассоциативного массива */
	$row = mysqli_fetch_array($result, MYSQLI_ASSOC);

	/* вывод в форму */
	echo '<form class="form-horizontal" role="form" action="update-firm.php" method="post">
	<legend>Редактирование данных контрагента</legend>

	<div class="form-group">
		<label class="col-sm-3 control-label">Название компании</label>
		<div class="col-sm-8">
			<input class="form-control" type="text" name="name" value="'.$row['name'].'">
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label">ФИО директора</label>
		<div class="col-sm-8">
			<input class="form-control" type="text" name="director" value="'.$row['director'].'">
		</div>
	</div>	
	<div class="form-group">
		<label class="col-sm-3 control-label">Реквизиты</label>
		<div class="col-sm-8">
			<textarea class="form-control" rows="8" name="requisites">'.$row['requisites'].'</textarea>
		</div>
	</div>
	<div class="form-group">
		<div class="col-sm-offset-9">
			<input type="hidden" name="id" value="'.$row['id'].'">
			<button type="submit" class="btn btn-default">Редактировать договор</button>
		</div>
	</div>
</form>';

	echo '<br /><br /><p><a href="index.php">Home</a> :: <a href="dogovora.php">Список договоров</a> :: <a href="firms.php">Список контрагентов</a> :: <a href="new-dogovor.php">Создать нового контрагента</a></p>';

	/* очищаем результаты выборки */
	mysqli_free_result($result);
	mysqli_free_result($result2);

	/* закрываем подключение */
	mysqli_close($link);
?>

	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
</body>
</html>