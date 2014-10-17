<!DOCTYPE html>
<html>
    <head>
	    <meta charset="utf-8">
	    <meta HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
	    <title>Создание нового договора</title>
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

	/* подготавливаем запрос к БД */
	$query = "SELECT `company`.`id` , `company`.`name`
	FROM `company`";
	$result = mysqli_query($link, $query);

	/* форма */
	echo '<form class="form-horizontal" role="form" action="update-new-dogovor.php" method="post" name="dogovor">
	<legend>Создание нового договора</legend>

	<div class="form-group">
		<label class="col-sm-3 control-label">Номер договора</label>
		<div class="col-sm-8">
			<input class="form-control" type="text" name="nomer">
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label">Дата договора</label>
		<div class="col-sm-8">
			<input class="form-control" type="date" min="2000-01-01" name="date" value="'.date("Y-m-d").'">
		</div>
	</div>
<div class="form-group">
		<label class="col-sm-3 control-label">Название компании</label>
		<div class="col-sm-8">
			<select class="form-control" name="company_id">';
			while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
		echo '<option value="'.$row['id'].'" >'.$row['name'].'</option>';
			}
	echo '
			</select>
		</div>
	</div>			
	<div class="form-group">
		<label class="col-sm-3 control-label">Примечания</label>
		<div class="col-sm-8">
			<input class="form-control" type="text" name="prim">
		</div>
	</div>
	<div class="form-group">
		<div class="col-sm-offset-9">
			<button type="submit" class="btn btn-default">Создать договор</button>
		</div>
	</div>
</form>';
	
	echo '<br /><br /><p><a href="index.php">Home</a> :: <a href="dogovora.php">Список договоров</a></p>';	
	
	/* очищаем результаты выборки */
	mysqli_free_result($result);
	mysqli_free_result($result2);
	
	/* закрываем подключение */
	mysqli_close($link);
?>
 
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
</body>
</html>