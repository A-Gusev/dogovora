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
echo '<form class="form-horizontal" role="form" action="update-setting.php" method="post" name="setting">
	<legend>Редактирование глобальных настроек</legend>
	<div class="form-group">
		<label class="col-sm-3 control-label">Название компании</label>
		<div class="col-sm-8">
			<input class="form-control" type="text" name="company_name" value="'.$row['company_name'].'">
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label">Должность директора</label>
		<div class="col-sm-8">
			<input class="form-control" type="text" name="job_title" value="'.$row['job_title'].'">
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label">Должность директора (родительный)</label>
		<div class="col-sm-8">
			<input class="form-control" type="text" name="job_title_r" value="'.$row['job_title_r'].'">
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label">ФИО директора</label>
		<div class="col-sm-8">
			<input class="form-control" type="text" name="director" value="'.$row['director'].'">
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label">ФИО директора (инициалы)</label>
		<div class="col-sm-8">
			<input class="form-control" type="text" name="director_io" value="'.$row['director_io'].'">
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label">ФИО директора (родительный)</label>
		<div class="col-sm-8">
			<input class="form-control" type="text" name="director_r" value="'.$row['director_r'].'">
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label">Реквизиты №1</label>
		<div class="col-sm-8">
			<textarea class="form-control" rows="8" name="bank_account-1">'.$row['bank_account-1'].'</textarea>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label">Реквизиты №2</label>
		<div class="col-sm-8">
			<textarea class="form-control" rows="8" name="bank_account-2">'.$row['bank_account-2'].'</textarea>
		</div>
	</div>
	<div class="form-group">
		<div class="col-sm-offset-9">
			<input type="hidden" name="id" value="'.$row['id'].'">
			<button type="submit" class="btn btn-default">Редактировать настройки</button>
		</div>
	</div>
</form>';

	echo '<br /><p><a href="index.php">Home</a></p>';	
	
	/* очищаем результаты выборки */
	mysqli_free_result($result);
	
	/* закрываем подключение */
	mysqli_close($link);
?>
 
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
</body>
</html>