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
		<title>Удаление контрагента</title>
		<link rel="stylesheet" href="../css/bootstrap.min.css">
		<link rel="stylesheet" href="../css/bootstrap-theme.min.css">
	</head>
<body>
<?php
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
	$query = "SELECT * FROM `company`
	WHERE `company`.`id`='$idset'";
	$result = mysqli_query($link, $query);

	/* Получение ассоциативного массива */
	$row = mysqli_fetch_array($result, MYSQLI_ASSOC);

	/* Запрос на получение количества договоров */ 
	$sql_kol = "SELECT COUNT(`nomer`)
	FROM `contract` JOIN `company` ON `contract`.`company_id` = `company`.`id`
	WHERE `company`.`id`='$idset'";
	$kol = mysqli_query($link, $sql_kol);
	$kol2 = mysqli_fetch_array($kol, MYSQLI_NUM);
 
	/* вывод в форму */
	echo '<form class="form-horizontal" role="form" action="yes-delete-firm.php" method="get">
	<legend>Удаление контрагента</legend>
	<div class="form-group">
		<div class="col-sm-3 control-label">Название компании</div>
		<div class="col-sm-8">'.$row['name'].'</div>
	</div>
	<div class="form-group">
		<div class="col-sm-3 control-label">ФИО директора</div>
		<div class="col-sm-8">'.$row['director'].'</div>
	</div>	
	<div class="form-group">
		<div class="col-sm-3 control-label">реквизиты</div>
		<div class="col-sm-8">'.$row['requisites'].'</div>
	</div>	
	<div class="form-group">
		<div class="col-sm-3 control-label">количество договоров</div>
		<div class="col-sm-8">'.$kol2['0'].'</div>
	</div>	
	<div class="form-group">';
	if ($kol2['0']==0) { echo '
		<div class="col-sm-3 control-label"><strong>Удалить контрагента?</strong><br />Это действие нельзя отменить</div>
		<div class="col-sm-8">
			<a href="firms.php"><button type="button" class="btn btn-success">НЕТ</button></a>
			<input type="hidden" name="id" value="'.$row['id'].'">
			<button type="submit" class="btn btn-danger">да</button>';
	}
	else {
		echo '<div class="col-sm-5 control-label"><strong>Нельзя удалять контрагента, к которому привязаны договора</strong></div>';
		}
		
		echo '
		</div>	
	</div>	
</form>';

	echo '<br /><br /><p><a href="../index.php">Home</a> :: <a href="firms.php">Список контрагентов</a> :: <a href="new-firm.php">Создать нового контрагента</a></p>';	

	/* очищаем результаты выборки */
	mysqli_free_result($result);
	mysqli_free_result($result2);

	/* закрываем подключение */
	mysqli_close($link);
?>
	<script src="../js/bootstrap.min.js"></script>
</body>
</html>