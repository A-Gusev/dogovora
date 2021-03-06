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
		<meta name="author" content="Alexey Gusev" />
		<meta name="rights" content="Студия Design4net.ru" />
		<link rel="stylesheet" href="../css/bootstrap.min.css">
		<link rel="stylesheet" href="../css/bootstrap-theme.min.css">		
		<link rel="stylesheet" href="../css/my.css">
		<link rel="stylesheet" href="../css/table.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
		<script type="text/javascript" src="../js/jquery.tablesorter.js"></script> 
		<script type="text/javascript">
			$(document).ready(function() { 
			$("#myTable").tablesorter({widthFixed: true, widgets: ['zebra']}); 
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

	/* забираем данные из формы; Запрос на получение списка договоров */
	if (array_key_exists('ref', $_REQUEST)) {
		$ref=$_REQUEST['ref'];
		if ($ref=='red') {
			$query = "SELECT * FROM `contract` JOIN `firms` ON `contract`.`c_company_id` = `firms`.`f_id` WHERE TO_DAYS(`c_date-po`) - TO_DAYS(NOW()) < 31 AND TO_DAYS(`c_date-po`) - TO_DAYS(NOW()) >= 0 ORDER BY  `contract`.`c_id` ASC";
		}
		elseif ($ref=='yellow') {
			$query = "SELECT * FROM `contract` JOIN `firms` ON `contract`.`c_company_id` = `firms`.`f_id` WHERE TO_DAYS(`c_date-po`) - TO_DAYS(NOW()) < 93 AND TO_DAYS(`c_date-po`) - TO_DAYS(NOW()) > 0 ORDER BY  `contract`.`c_id` ASC";
		}
		elseif ($ref=='green') {
			$query = "SELECT * FROM `contract` JOIN `firms` ON `contract`.`c_company_id` = `firms`.`f_id` WHERE TO_DAYS(`c_date-po`) - TO_DAYS(NOW()) >= 0 ORDER BY  `contract`.`c_id` ASC";
		}
		else {
			$query = "SELECT * FROM `contract` JOIN `firms` ON `contract`.`c_company_id` = `firms`.`f_id` ORDER BY  `contract`.`c_id` ASC";
		}
	}
	else {
		$query = "SELECT * FROM `contract` JOIN `firms` ON `contract`.`c_company_id` = `firms`.`f_id` ORDER BY  `contract`.`c_id` ASC";
	}

	/* Запрос на получение списка договоров */
	$result = mysqli_query($link, $query);
	
	/* Запрос на получение name bank account */
	$query_bank = "SELECT `settings`.`s_name_bank_account-1`, `settings`.`s_name_bank_account-2`
	FROM `settings`
	WHERE `settings`.`s_id`=1";
	$result_bank = mysqli_query($link, $query_bank);
	$row_bank = mysqli_fetch_array($result_bank, MYSQLI_ASSOC);

	/* Узнаем какое сегодня число */
	$today = date("Y-m-d");
	/* 1 месяца вперёд (31 день) */
	$m1 = date("Y-m-d" ,time()+60*60*24*31);
	/* 3 месяца вперёд (93 дня) */
	$m3 = date("Y-m-d" ,time()+60*60*24*31*3);

	/* Вывод меню */
	$page='contract';
	require_once ('../nav.php');

	echo '<table id="myTable" class="tablesorter table table-hover">
	<caption>';
	if (array_key_exists('ref', $_REQUEST)) {
		$ref=$_REQUEST['ref'];
		if ($ref=='red') {
			echo '<h3>Список договоров, заканчивающихся в ближайшие 30 дней</h3>';
		}
		elseif ($ref=='yellow') {
			echo '<h3>Список договоров, заканчивающихся в ближайшие 3 месяца</h3>';
		}
		elseif ($ref=='green') {
			echo 'Список действующих договоров';
		}
		else {
			echo 'Список договоров';
		}
	}
	else {
		echo 'Список договоров';
	}
	echo '</caption>
	<thead>
		<tr>
			<th>№ договора</th>
			<th>Тип</th>
			<th>Название</th>
			<th>С</th>
			<th>По</th>
			<th>Д. акта</th>
			<th>Счёт</th>
			<th>Цена в месяц</th>
			<th>№ помещения</th>
			<th>Ред.</th>
			<th>Trash</th>
		</tr>
	</thead>
	<tbody>';
	
	/* ассоциативный массив */
	while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
	
	/* Запрос на получение типа договора */
	$query_type = "SELECT `type_contract`.`tc_id` , `type_contract`.`tc_type`
	FROM `type_contract`
	WHERE `type_contract`.`tc_id`=".$row['c_id_type_dog'];
	$result_type = mysqli_query($link, $query_type);
	$row_type = mysqli_fetch_array($result_type, MYSQLI_ASSOC);

	echo '
		<tr';
			if ($row['c_date-po']>=$today && $row['c_date-po']<$m1) {
				echo ' class="danger"';
			}
			elseif ($row['c_date-po']<$m3 && $row['c_date-po']>=$m1) {
				echo ' class="warning"';
			}
			elseif ($row['c_date-po']>=$m3) {
				echo ' class="success"';
			}
			else {
				echo ' class="s"';
			}
				
	echo '> 
			<td><a href="odogovore.php?id='.$row['c_id'].'">Договор №'.$row['c_nomer'].' от '.$row['c_date'].'</a></td>
			<td>'.$row_type['tc_type'].'</td>
			<td><a href="../company/dogovor-firm.php?id='.$row['f_id'].'">'.$row['f_name'].'</a></td>
			<td>'.$row['c_date-s'].'</td>
			<td>'.$row['c_date-po'].'</td>
			<td>'.$row['c_date-akt'].'</td>
			<td>';
			if ($row['c_bank']==2) {echo $row_bank['s_name_bank_account-2'];}
						else {echo $row_bank['s_name_bank_account-1'];}
			echo '</td>
			<td>'.number_format($row['c_price'], 0, ',', ' ').' <span class="glyphicon glyphicon-rub" aria-hidden="true"></span></td>
			<td>'.$row['c_number'].'</td>
			<td>
				<form class="form-inline" role="form" action="dogovor.php" method="get">
					<input type="hidden" name="id" value="'.$row['c_id'].'"><button type="submits" class="btn btn-default"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></button>
				</form>
			</td>
			<td>
				<form class="form-inline" role="form" action="delete-dogovor.php" method="get">
					<input type="hidden" name="id" value="'.$row['c_id'].'"><button type="submits" class="btn btn-danger"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></button>
				</form>
			</td>
		</tr>';

	}

	echo '
	</tbody>
</table>
	<br />';

	if ($menu_kol_c1['0'] > 0) {
		echo '<span class="label label-danger">Красным цветом выделены строки с договорами, истекающими в ближайшие 30 дней</span><br />';
	}
	if ($menu_kol_c3['0'] > 0) {
		echo '<span class="label label-warning">Жёлтым цветом выделены строки с договорами, истекающие в ближайшие 3 месяца</span><br />';
	}
	if ($menu_kol_c4['0'] > 0) {
		echo '<span class="label label-success">Зелёным цветом выделены строки с действующими договорами (закончатся больше чем через 3 месяца)</span><br />';
	}
	if ($menu_kol_c6['0'] > 0) {
		echo '<span><s>Зачёркнутые</s> строки - закончившиеся договора</span><br />';
	}
echo '<br /><br /><br /><br />';
	/* очищаем результаты выборки */
	mysqli_free_result($result);
	mysqli_free_result($result_bank);
	mysqli_free_result($result_type);

	/* закрываем подключение */
	mysqli_close($link);
?>
	<script src="../js/bootstrap.min.js"></script>
</body>
</html>