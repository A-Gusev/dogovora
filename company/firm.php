<!DOCTYPE html>
<html>
    <head>
	    <meta charset="utf-8">
	    <meta HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
	    <title>Редактирование данных контрагента</title>
		<link rel="stylesheet" href="../css/bootstrap.min.css">
		<link rel="stylesheet" href="../css/bootstrap-theme.min.css">
	</head>
<body>
<?php
	/* выключаем кэширование 
	Header("Cache-Control: no-store, no-cache, must-revalidate");
	Header("Pragma: no-cache");
	Header("Last-Modified: " . gmdate("D, d M Y H:i:s") . "GMT");
	Header("Expires: " . date("r"));*/

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
	WHERE  `company`.`id`='$idset'";
	$result = mysqli_query($link, $query);

	/* Запрос на получение количества договоров */	
	$sql_kol = "SELECT COUNT(`nomer`)
	FROM `contract` JOIN `company` ON `contract`.`company_id` = `company`.`id`
	WHERE `company`.`id`='$idset'";
	$kol = mysqli_query($link, $sql_kol);

	/* Получение массивов */
	$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
	$kol2 = mysqli_fetch_array($kol, MYSQLI_NUM);	

	/* Запрос на получение типа контрагента */
	$query_type = "SELECT `type_company`.`id` , `type_company`.`type`
	FROM `type_company`";
	$result_type = mysqli_query($link, $query_type);

	/* вывод в форму */
	echo '<form class="form-horizontal" role="form" action="update-firm.php" method="post">
	<legend>Редактирование данных контрагента</legend>	
	<div class="form-group">
		<label class="col-sm-3 control-label">Тип контрагента</label>
		<div class="col-sm-8">
			<select class="form-control" name="company_type">';
				while ($row_type = mysqli_fetch_array($result_type, MYSQLI_ASSOC)) {
					echo '<option value="'.$row_type['id'].'"';
					if ($row_type['id']==$row['id_type']) echo ' selected ';
					echo ' >' . $row_type['type'] . '</option>';
				}
	echo '
			</select>
		</div>
	</div>
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
		<label class="col-sm-3 control-label">количество договоров</label>';
			if ($kol2['0']>0) {
				echo '<div class="col-sm-8"><a href="dogovor-firm.php?id='.$row['id'].'">'.$kol2['0'].'</a></div>';
			}
			else {
				echo '<div class="col-sm-8">'.$kol2['0'].'</div>';
			}
		echo '
	</div>	
	<div class="form-group">
		<div class="col-sm-offset-5">
			<input type="hidden" name="id" value="'.$row['id'].'">
			<button type="submit" class="btn btn-default" name="button" value="save">Сохранить</button>
			<button type="submit" class="btn btn-success" name="button" value="close">Сохранить и закрыть</button>
		</div>
	</div>
</form>';

	echo '<br /><br /><p><a href="../index.php">Home</a> :: <a href="firms.php">Список контрагентов</a> :: <a href="new-firm.php">Создать нового контрагента</a></p>';

	/* очищаем результаты выборки */
	mysqli_free_result($result);
	mysqli_free_result($kol);
	mysqli_free_result($result_type);

	/* закрываем подключение */
	mysqli_close($link);
?>

	<script src="../js/bootstrap.min.js"></script>
</body>
</html>