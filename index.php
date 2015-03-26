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
		<title>Система учёта договоров</title>
		<meta name="author" content="Alexey Gusev" />
		<meta name="rights" content="Студия Design4net.ru" />
		<link rel="stylesheet" href="css/bootstrap.min.css">
		<link rel="stylesheet" href="css/bootstrap-theme.min.css">
		<link rel="stylesheet" href="css/my.css">
	</head>
<body>
<?php
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

	/* Узнаем какое сегодня число */
	$today = date("Y-m-d");

	$page='home';
	require_once 'nav.php';
?>

<h1>Система учёта договоров аренды</h1>

<?php
	$query = "SELECT * FROM `contract` JOIN `firms` ON `contract`.`c_company_id` = `firms`.`f_id` ORDER BY  `contract`.`c_id` ASC";

	/* Запрос на получение name bank account */
	$query_bank = "SELECT `settings`.`s_name_bank_account-1`, `settings`.`s_name_bank_account-2`
	FROM `settings`
	WHERE `settings`.`s_id`=1";
	$result_bank = mysqli_query($link, $query_bank);
	$row_bank = mysqli_fetch_array($result_bank, MYSQLI_ASSOC);
	
# Кержаков?
function kerzhakov($date_s, $date_po, $month) {
		$n = $month.'-01';
		$k = $month.'-'.date("t", strtotime($month));
/*		// Определение количества дней в месяце
		$kol_d = date_diff(date_create($n),date_create($k)); 			
		$kol_d=$kol_d->format("%a");
		$kol_d++;
*/		
		$n = date_create($n)->format('Y-m-d');
		$k = date_create($k)->format('Y-m-d');
		$date_s = date_create($date_s)->format('Y-m-d');
		$date_po = date_create($date_po)->format('Y-m-d');

		$n = strtotime ($n);
		$k = strtotime ($k);
		$date_po = strtotime ($date_po);
		$date_s = strtotime ($date_s);
				
		if ( (($date_s <= $n) && ($date_po >= $k)) || 
		(($date_s >= $n) && ($date_po <= $k)) ||
		(($date_s <= $n) && ($date_po <= $k) && ($date_po >= $n)) ||
		(($date_s >= $n) && ($date_po >= $k) && ($date_s <= $k)) ) {
			$popal = 'TRUE';		
		}
		else {
			$popal = 'FALSE';
		}
return $popal;
}		

	$g = 2015; // номер года
	$sum_price1 = 0;
	$sum_price2 = 0;
	
echo '
<table class="table table-striped money">
	<tr>
		<th>'.$g.'</th>
		<th>'.$row_bank['s_name_bank_account-1'].'</th>
		<th>'.$row_bank['s_name_bank_account-2'].'</th>
		<th>Сумма</th>
	</tr>';

	
	

for ($m = 1; $m <= 12; $m++) {
	$result = mysqli_query($link, $query);
	$sum_price1 = 0;
	$sum_price2 = 0;
	while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
		$summ = 0;
		$popal=kerzhakov($row['c_date-s'], $row['c_date-po'], date_create($g.'-'.$m)->format('Y-m'));
	//echo 'id='.$row['c_id'].' цена='.$row['c_price'].' ';
		if ($popal === 'TRUE') {
			$month = date_create($g.'-'.$m)->format('Y-m');
			$n = date_create($g.'-'.$m)->format('Y-m');
			$na = $month.'-01';
			$ko = $month.'-'.date("t", strtotime($month));
			$n = date_create($na)->format('Y-m-d');
			$k = date_create($ko)->format('Y-m-d');
			$date_s = date_create($row['c_date-s'])->format('Y-m-d');
			$date_po = date_create($row['c_date-po'])->format('Y-m-d');

			$n = strtotime ($n);
			$k = strtotime ($k);
			$date_po = strtotime ($date_po);
			$date_s = strtotime ($date_s);
			
			if ($date_s > $n) {$mount_min = $date_s;}
			else {$mount_min = $n;}
		//echo $mount_min.' ';
			
			if ($date_po > $k) {$mount_max = $k;}
			else {$mount_max = $date_po;}
		//echo $mount_max.' ';
			
			$money_dat = 1 + ($mount_max - $mount_min)/(60*60*24);
		//echo $money_dat.' ';;
						
			// Определение количества дней в месяце
			$kol_d = date_diff(date_create($na),date_create($ko)); 			
			$kol_d=$kol_d->format("%a");
			$kol_d++;
		//echo $kol_d.' ';
			
			$summ = ($row['c_price']*$money_dat)/$kol_d;
		//echo round($summ, 2).' ';
			
			if ($row['c_bank'] == 2) {
				$sum_price2 = $sum_price2 + $summ;
			}
			else {
				$sum_price1 = $sum_price1 + $summ;
			}
		}
	}	
	//echo '<br />';
	setlocale(LC_TIME, "ru_RU");
	$mes = $m.'/1/2015';
//	$mes2= $mes;
//	echo $mes2.' '.$today.' ';
	
	$mes = strftime("%b",strtotime($mes));
	$todaynow = getdate();
	if (($m==$todaynow['mon']) && $g==$todaynow['year']) {
		echo '<tr class="info"><td>'.$mes.' '.'</td>';
	}
	else {
		echo '<tr><td>'.$mes.' '.'</td>';
	}
	$sum_price=$sum_price1+$sum_price2;
	setlocale(LC_MONETARY, 'ru_RU');
	echo '
		<td>'.money_format("%i", $sum_price1).'</td>
		<td>'.money_format("%i", $sum_price2).'</td>
		<td>'.money_format("%i", $sum_price).'</td>
	</tr>';	
}		
echo '</table><br /><br /><br />';
	/* закрываем подключение */
	mysqli_close($link);
?>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
	<script src="../js/bootstrap.min.js"></script>
</body>
</html>