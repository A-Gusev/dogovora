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
		<title>Список договоров</title>
		<link rel="stylesheet" href="../css/bootstrap.min.css">
		<link rel="stylesheet" href="../css/bootstrap-theme.min.css">
		<link rel="stylesheet" href="../css/table.css">
		<script type="text/javascript" src="../js/jquery-latest.js"></script> 
		<script type="text/javascript" src="../js/jquery.tablesorter.js"></script> 
		<script type="text/javascript">
			$(document).ready(function() { 
			$("#myTable") 
			.tablesorter({widthFixed: true, widgets: ['zebra']}); 
			});
		</script>
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
	
	/* Запрос на получение списка договоров */
	$query = "SELECT *
	FROM `contract` JOIN `firms` ON `contract`.`c_company_id` = `firms`.`f_id`
	ORDER BY  `contract`.`c_id` ASC";
	$result = mysqli_query($link, $query);

	/* Узнаем какое сегодня число */
	$today = date("Y-m-d");
	/* 1 месяца вперёд (31 день) */
	$m1 = date("Y-m-d" ,time()+60*60*24*31);
	/* 3 месяца вперёд (93 дня) */
	$m3 = date("Y-m-d" ,time()+60*60*24*31*3);
	
	echo '<table id="myTable" class="tablesorter table table-hover">
	<caption>Список договоров</caption>
	<thead>
		<tr>
			<th>Номер и дата договора</th>
			<th>Тип договора</th>
			<th>Название компании</th>
			<th>Договор с...</th>
			<th>Договор по...</th>
			<th>Дата акта</th>
			<th>Номер помещения</th>
			<th>редактировать</th>
			<th>удалить</th>
		</tr>
	</thead>
	<tbody>';
	
	/* ассоциативный массив */
	while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
	
	/* Запрос на получение типа договора */
	$query_type = "SELECT `type_contract`.`tc_id` , `type_contract`.`tc_type`
	FROM `type_contract`
	WHERE `type_contract`.`tc_id`=".$row['c_id'];
	$result_type = mysqli_query($link, $query_type);
	$row_type = mysqli_fetch_array($result_type, MYSQLI_ASSOC);

	echo '
		<tr';
			if ($row['c_date-po']>=$today && $row['c_date-po']<$m1)
				{echo ' class="danger"';}
			elseif ($row['c_date-po']<$m3 && $row['c_date-po']>=$m1)
				{echo ' class="warning"';}
			elseif ($row['c_date-po']>=$m3)
				{echo ' class="success"';}
	echo '> 
			<td><a href="odogovore.php?id='.$row['c_id'].'">Договор №'.$row['c_nomer'].' от '.$row['c_date'].'</a></td>
			<td>'.$row_type['tc_type'].'</td>
			<td><a href="../company/dogovor-firm.php?id='.$row['f_id'].'">'.$row['f_name'].'</a></td>
			<td>'.$row['c_date-s'].'</td>
			<td>'.$row['c_date-po'].'</td>
			<td>'.$row['c_date-akt'].'</td>
			<td>'.$row['c_number'].'</td>
			<td>
				<form class="form-inline" role="form" action="dogovor.php" method="get">
					<input type="hidden" name="id" value="'.$row['c_id'].'"><button type="submits" class="btn btn-default">Редактировать</button>
				</form>
			</td>
			<td>
				<form class="form-inline" role="form" action="delete-dogovor.php" method="get">
					<input type="hidden" name="id" value="'.$row['c_id'].'"><button type="submits" class="btn btn-danger">Удалить</button>
				</form>
			</td>
		</tr>';

	}

	echo '
	</tbody>
</table>
	<br />';

	echo '
	<span class="label label-danger">Красным цветом выделены строки с договорами, истекающими в этом месяце;</span><br />
	<span class="label label-warning">жёлтым цветов - истекающие в ближайшие 3 месяца;</span><br />
	<span class="label label-success">зелёным цветом - действующие договора;</span><br />
	<span>белым цветом - закончившиеся договора.</span><br />
	<br /><div class="text-center"><a href="../index.php">Home</a> :: <a href="dogovora.php">Список договоров</a> :: <a href="new-dogovor.php">Создать новый договор</a>	</div>
';	

	/* очищаем результаты выборки */
	mysqli_free_result($result);
	mysqli_free_result($result_type);

	/* закрываем подключение */
	mysqli_close($link);
?>
	<script src="../js/bootstrap.min.js"></script>
</body>
</html>