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
		<title>Список договоров контрагента</title>
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
	echo '<div class="form-horizontal">
	<legend>Список договоров контрагента</legend>
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

	<div class="form-group">
		<div class="col-sm-3 control-label">редактировать данные контрагента</div>
		<div class="col-sm-8">
			<form class="form-inline" role="form" action="firm.php" method="get">
				<input type="hidden" name="id" value="'.$row['id'].'">
				<button type="submits" class="btn btn-default">Редактировать</button>
			</form>
		</div>
	</div>

	<div class="form-group">
		<div class="col-sm-3 control-label"><strong>Удалить контрагента?</strong><br />Это действие нельзя отменить</div>
		<div class="col-sm-8">';
			if ($kol2['0']==0) { echo '
				<form class="form-horizontal" role="form" action="yes-delete-firm.php" method="get">
					<a href="firms.php"><button type="button" class="btn btn-success">НЕТ</button></a>
					<input type="hidden" name="id" value="'.$row['id'].'">
					<button type="submit" class="btn btn-danger">да</button>
				</form>';
			}
			else {
				echo 'Нельзя удалять контрагента, к которому привязаны договора';
			}
		echo '
		</div>	
	</div>
<div><br />';

	if ($kol2['0']>0) { 
	
	$sql_dogovora = "SELECT * FROM  `contract` 
	WHERE  `company_id` ='$idset'
	ORDER BY  `contract`.`id` ASC ";

	$result_dogovora = mysqli_query($link, $sql_dogovora);

	echo '<table class="table table-hover">
	<caption>Список договоров контрагента <strong>'.$row['name'].'</strong></caption>
	<thead>
		<tr>
			<th>Номер и дата договора</th>
			<th>примечание</th>
			<th>редактировать</th>
			<th>удалить</th>
		</tr>
	</thead>
	<tbody>';

	/* ассоциативный массив */
	while ($row_dog = mysqli_fetch_array($result_dogovora, MYSQLI_ASSOC)) {

	echo '
		<tr> 
			<td>Договор №'.$row_dog['nomer'].' от '.$row_dog['date'].'</td>
			<td>'.$row_dog['prim'].'</td>
			<td>
				<form class="form-inline" role="form" action="../contract/dogovor.php" method="get">
					<input type="hidden" name="id" value="'.$row_dog['id'].'"><button type="submits" class="btn btn-default">Редактировать</button>
			</form>
			</td>
			<td>
				<form class="form-inline" role="form" action="../contract/delete-dogovor.php" method="get">
					<input type="hidden" name="id" value="'.$row_dog['id'].'"><button type="submits" class="btn btn-danger">Удалить</button>
			</form>
			</td>
		</tr>';

	}

	echo '
	</tbody>
</table>';

}

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