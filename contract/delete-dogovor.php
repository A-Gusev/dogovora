<!DOCTYPE html>
<html>
    <head>
	    <meta charset="utf-8">
	    <meta HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
	    <title>Удаление договора</title>
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

	/* Получение ассоциативного массива */
	$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
 
	/* вывод в форму */
	echo '<form class="form-horizontal" role="form" action="yes-delete-dogovor.php" method="get">
	<legend>Удаление договора</legend>
	<div class="form-group">
		<div class="col-sm-3 control-label">Номер договора</div>
		<div class="col-sm-8">'.$row['nomer'].'</div>
	</div>
	<div class="form-group">
		<div class="col-sm-3 control-label">Дата договора</div>
		<div class="col-sm-8">'.$row['date'].'</div>
	</div>	
	<div class="form-group">
		<div class="col-sm-3 control-label">Название компании</div>
		<div class="col-sm-8">'.$row['name'].'</div>
	</div>	
	<div class="form-group">
		<div class="col-sm-3 control-label">Примечания</div>
		<div class="col-sm-8">'.$row['prim'].'</div>
	</div>
	<div class="form-group">
		<div class="col-sm-3 control-label"><strong>Удалить догвоор?</strong><br />Это действие нельзя отменить</div>
		<div class="col-sm-8">
			<a href="dogovora.php"><button type="button" class="btn btn-success">НЕТ</button></a>
			<input type="hidden" name="id" value="'.$row['id'].'">
			<button type="submit" class="btn btn-danger">да</button>
		</div>
	</div>	
</form>';

	echo '<br /><br /><p><a href="../index.php">Home</a> :: <a href="dogovora.php">Список договоров</a> :: <a href="new-dogovor.php">Создать новый договор</a></p>';	

	/* очищаем результаты выборки */
	mysqli_free_result($result);
	mysqli_free_result($result2);

	/* закрываем подключение */
	mysqli_close($link);
?>

	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
</body>
</html>