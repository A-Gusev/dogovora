<!DOCTYPE html>
<html>
    <head>
	    <meta charset="utf-8">
	    <meta HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
	    <title>Редактирование договора</title>
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
	$query = "SELECT `contract`.`id`, `contract`.`nomer`, `contract`.`date`, `contract`.`company_id`, `company`.`name`, `contract`.`prim`
	FROM `contract` JOIN `company` ON `contract`.`company_id` = `company`.`id`
	WHERE  `contract`.`id`='$idset'";
	$result = mysqli_query($link, $query);

	$query2 = "SELECT `company`.`id` , `company`.`name`
	FROM `company`";
	$result2 = mysqli_query($link, $query2);

	/* Получение ассоциативного массива */
	$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
 
	/* вывод в форму */
	echo '<form class="form-horizontal" role="form" action="update-dogovor.php" method="post" name="dogovor">
	<legend>Редактирование договора</legend>
	
	<div class="form-group">
		<label class="col-sm-3 control-label">Номер договора</label>
		<div class="col-sm-8">
			<input class="form-control" type="text" name="nomer" value="'.$row['nomer'].'">
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label">Дата договора</label>
		<div class="col-sm-8">
			<input class="form-control" type="date" min="2000-01-01" name="date" value="'.$row['date'].'">
		</div>
	</div>	
	<div class="form-group">
		<label class="col-sm-3 control-label">Название компании</label>
		<div class="col-sm-8">
			<select class="form-control" name="company_id">';
			while ($row2 = mysqli_fetch_array($result2, MYSQLI_ASSOC)) {
		echo '<option value="'.$row2['id'].'"';
		if ($row2['id']==$row['company_id']) echo ' selected ';
		echo ' >' . $row2['name'] . '</option>';
	}
	echo '
			</select>
		</div>
	</div>	
	<div class="form-group">
		<label class="col-sm-3 control-label">Примечания</label>
		<div class="col-sm-8">
			<input class="form-control" type="text" name="prim" value="'.$row['prim'].'">
		</div>
	</div>
	<div class="form-group">
		<div class="col-sm-offset-9">
			<input type="hidden" name="id" value="'.$row['id'].'">
			<button type="submit" class="btn btn-default">Редактировать договор</button>
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