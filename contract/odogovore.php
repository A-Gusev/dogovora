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
		<title>О договоре</title>
		<meta name="author" content="Alexey Gusev" />
		<meta name="rights" content="Студия Design4net.ru" />
		<link rel="stylesheet" href="../css/bootstrap.min.css">
		<link rel="stylesheet" href="../css/bootstrap-theme.min.css">
		<link rel="stylesheet" href="../css/my.css">
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

	/* Узнаем какое сегодня число */
	$today = date("Y-m-d");
	/* 1 месяца вперёд (31 день) */
	$m1 = date("Y-m-d" ,time()+60*60*24*31);
	/* 3 месяца вперёд (93 дня) */
	$m3 = date("Y-m-d" ,time()+60*60*24*31*3);

	/* забираем данные из формы */
	$idset=$_REQUEST['id'];

	/* подготавливаем запрос к БД */
	$query = "SELECT *
	FROM `contract` JOIN `firms` ON `contract`.`c_company_id` = `firms`.`f_id`
	WHERE  `contract`.`c_id`='$idset'";
	$result = mysqli_query($link, $query);

	/* Получение ассоциативного массива */
	$row = mysqli_fetch_array($result, MYSQLI_ASSOC);

	/* Запрос на получение типа договора */
	$query_type = "SELECT `type_contract`.`tc_id` , `type_contract`.`tc_type`
	FROM `type_contract`
	WHERE `type_contract`.`tc_id`=".$row['c_id_type_dog'];
	$result_type = mysqli_query($link, $query_type);
	$row_type = mysqli_fetch_array($result_type, MYSQLI_ASSOC);

	/* Вывод меню */
	$page='contract';
	require_once ('../nav.php');

	echo '
			<form class="form-horizontal" role="form" action="yes-delete-dogovor.php" method="get">
				<legend>Информация о договоре</legend>
				<div class="form-group">
					<div class="col-sm-3 text-right">ID</div>
					<div class="col-sm-8">'.$row['c_id'].'</div>
				</div>
				<div class="form-group">
					<div class="col-sm-3 text-right">Номер и дата договора</div>
					<div class="col-sm-8">Договор №'.$row['c_nomer'].' от '.$row['c_date'].'</div>
				</div>
				<div class="form-group">
					<div class="col-sm-3 text-right">Тип договора</div>
					<div class="col-sm-8">'.$row_type['tc_type'].'</div>
				</div>
				<div class="form-group">
					<div class="col-sm-3 text-right">Название компании</div>
					<div class="col-sm-8"><a href="../company/dogovor-firm.php?id='.$row['f_id'].'">'.$row['f_name'].'</a></div>
				</div>
				<div class="form-group">
					<div class="col-sm-3 text-right">Договор с...</div>
					<div class="col-sm-8">'.$row['c_date-s'].'</div>
				</div>
				<div class="form-group">
					<div class="col-sm-3 text-right">Договор по...</div>
					<div class="col-sm-8">'.$row['c_date-po'];
						if ($row['c_date-po']>=$today && $row['c_date-po']<$m1)
							{echo ' <span class="label label-danger">договор закончится в ближайшие 30 дней</span>';}
						elseif ($row['c_date-po']<$m3 && $row['c_date-po']>=$m1)
							{echo ' <span class="label label-warning">договор закончится в ближайшие 3 месяца</span>';}
						elseif ($row['c_date-po']>=$m3)
							{echo ' <span class="label label-success">действующий договор</span>';}
						else {echo ' договор закончился';}
				echo '
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-3 text-right">Дата акта</div>
					<div class="col-sm-8">'.$row['c_date-akt'].'</div>
				</div>
				<div class="form-group">
					<div class="col-sm-3 text-right">Номер помещения</div>
					<div class="col-sm-8">'.$row['c_number'].'</div>
				</div>
				<div class="form-group">
					<div class="col-sm-3 text-right">Площадь помещения</div>
					<div class="col-sm-8">'.$row['c_m2'].' м<sup>2</sup></div>
				</div>
				<div class="form-group">
					<div class="col-sm-3 text-right">Примечания</div>
					<div class="col-sm-8">'.$row['c_prim'].'</div>
				</div>
				<div class="form-group">
					<div class="col-sm-3 control-label"><strong>Удалить догвоор?</strong><br /><em>Это действие нельзя отменить</em></div>
					<div class="col-sm-8">
						<a href="dogovora.php"><button type="button" class="btn btn-success">НЕТ</button></a>
						<input type="hidden" name="id" value="'.$row['c_id'].'">
						<button type="submit" class="btn btn-danger">да</button>
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-3 control-label">Редактировать договор?</div>
					<div class="col-sm-8">
						<a href="dogovor.php?id='.$row['c_id'].'"><button type="button" class="btn btn-default">Редактировать</button></a>
					</div>
				</div>
			</form>';

	/* очищаем результаты выборки */
	mysqli_free_result($result);
	mysqli_free_result($result_type);

	/* закрываем подключение */
	mysqli_close($link);
?>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
	<script src="../js/bootstrap.min.js"></script>
</body>
</html>