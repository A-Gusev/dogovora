<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
		<title>Список договоров</title>
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

	$query = "SELECT `contract`.`id`, `contract`.`nomer`, `contract`.`date`, `company`.`name`, `contract`.`prim`
	FROM `contract` JOIN `company` ON `contract`.`company_id` = `company`.`id`
	ORDER BY  `contract`.`id` ASC";

	$result = mysqli_query($link, $query);

	echo '<table class="table table-hover">
	<caption>Список договоров</caption>
	<thead>
		<tr>
			<th>Номер и дата договолра</th>
			<th>название компании</th>
			<th>примечание</th>
			<th>редактировать</th>
			<th>удалить</th>
		</tr>
	</thead>
	<tbody>';

	/* ассоциативный массив */
	while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {

	echo '
		<tr> 
			<td>Договор №'.$row['nomer'].' от '.$row['date'].'</td>
			<td>'.$row['name'].'</td>
			<td>'.$row['prim'].'</td>
			<td>
				<form class="form-inline" role="form" action="dogovor.php" method="get">
					<input type="hidden" name="id" value="'.$row['id'].'"><button type="submits" class="btn btn-default">Редактировать</button>
			</form>
			</td>
			<td>
				<form class="form-inline" role="form" action="delete-dogovor.php" method="get">
					<input type="hidden" name="id" value="'.$row['id'].'"><button type="submits" class="btn btn-danger">Удалить</button>
			</form>
			</td>
		</tr>';

	}

	echo '
	</tbody>
</table>';

	echo '<br /><br /><p><a href="../index.php">Home</a> :: <a href="dogovora.php">Список договоров</a> :: <a href="new-dogovor.php">Создать новый договор</a></p>';	

	/* очищаем результаты выборки */
	mysqli_free_result($result);

	/* закрываем подключение */
	mysqli_close($link);
?>

	<script src="../js/bootstrap.min.js"></script>
</body>
</html>