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
		<title>Список контрагентов</title>
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

	$query = "SELECT * FROM `company`
	ORDER BY  `company`.`id` ASC";
	$result = mysqli_query($link, $query);

	echo '<table class="table table-hover">
	<caption>Список контрагентов</caption>
	<thead>
		<tr>
			<th>Название компании</th>
			<th>ФИО директора</th>
			<th>реквизиты</th>
			<th>количество договоров</th>
			<th>редактировать</th>
			<th>удалить</th>
		</tr>
	</thead>
	<tbody>';

	/* ассоциативный массив */
	while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {

	$sql_kol = "SELECT COUNT(`nomer`)
	FROM `contract` JOIN `company` ON `contract`.`company_id` = `company`.`id`
	WHERE `company`.`id`=".$row['id'];
	$kol = mysqli_query($link, $sql_kol);
	$kol2 = mysqli_fetch_array($kol, MYSQLI_NUM);

	echo '
		<tr> 
			<td><a href="dogovor-firm.php?id='.$row['id'].'">'.$row['name'].'</a></td>
			<td>'.$row['director'].'</td>
			<td>'.$row['requisites'].'</td>';
			if ($kol2['0']>0) {
				echo '<td><a href="dogovor-firm.php?id='.$row['id'].'">'.$kol2['0'].'</a></td>';
			}
			else {
				echo '<td>'.$kol2['0'].'</td>';
			}
			echo '<td>
				<form class="form-inline" role="form" action="firm.php" method="get">
					<input type="hidden" name="id" value="'.$row['id'].'">
					<button type="submits" class="btn btn-default">Редактировать</button>
				</form>
			</td>
			<td>';
				if ($kol2['0']==0) { echo '
				<form class="form-inline" role="form" action="delete-firm.php" method="get">
					<input type="hidden" name="id" value="'.$row['id'].'">
					<button type="submits" class="btn btn-danger">Удалить</button>
				</form>';
			}
			echo '</td>
		</tr>';
	}

	echo '
	</tbody>
</table>';

	echo '<br /><br /><p><a href="../index.php">Home</a> :: <a href="firms.php">Список контрагентов</a> :: <a href="new-firm.php">Создать нового контрагента</a></p>';

	/* очищаем результаты выборки */
	mysqli_free_result($result);
	mysqli_free_result($kol);

	/* закрываем подключение */
	mysqli_close($link);
?>
	<script src="../js/bootstrap.min.js"></script>
</body>
</html>