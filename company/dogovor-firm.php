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
		<meta name="author" content="Alexey Gusev" />
		<meta name="rights" content="Студия Design4net.ru" />
		<link rel="stylesheet" href="../css/bootstrap.min.css">
		<link rel="stylesheet" href="../css/bootstrap-theme.min.css">
		<link rel="stylesheet" href="../css/my.css">
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

	/* Узнаем какое сегодня число */
	$today = date("Y-m-d");
	/* 1 месяца вперёд (31 день) */
	$m1 = date("Y-m-d" ,time()+60*60*24*31);
	/* 3 месяца вперёд (93 дня) */
	$m3 = date("Y-m-d" ,time()+60*60*24*31*3);

	/* забираем данные из формы */
	$idset=$_REQUEST['id'];

	/* подготавливаем запрос к БД */
	$query = "SELECT * FROM `firms`
	WHERE  `firms`.`f_id`='$idset'";
	$result = mysqli_query($link, $query);

	/* Получение ассоциативного массива */
	$row = mysqli_fetch_array($result, MYSQLI_ASSOC);

	/* Запрос на получение количества договоров */ 
	$sql_kol = "SELECT COUNT(`c_nomer`)
	FROM `contract` JOIN `firms` ON `contract`.`c_company_id` = `firms`.`f_id`
	WHERE `firms`.`f_id`='$idset'";
	$kol = mysqli_query($link, $sql_kol);
	$kol2 = mysqli_fetch_array($kol, MYSQLI_NUM);
	
	/* Запрос на получение типа контрагента */
	$query_type = "SELECT `type_firm`.`tf_id` , `type_firm`.`tf_type`
	FROM `type_firm`
	WHERE `type_firm`.`tf_id`='$idset'";
	$result_type = mysqli_query($link, $query_type);
	$row_type = mysqli_fetch_array($result_type, MYSQLI_ASSOC);

	/* Вывод меню */
	$page='company';
	require_once ('../nav.php');

	/* вывод в 2 колонки если есть договора */
	if ($kol2['0']>0) {echo '	<div class="row">
		<div class="col-md-5">';} echo '		  
			<div class="form-horizontal">
				<legend>Информация о контрагенте</legend>
				<div class="form-group">
					<div class="col-sm-3 text-right">ID</div>
					<div class="col-sm-8">'.$row['f_id'].'</div>
				</div>
				<div class="form-group">
					<div class="col-sm-3 text-right">Тип</div>
					<div class="col-sm-8">'.$row_type['tf_type'].'</div>
				</div>
				<div class="form-group">
					<div class="col-sm-3 text-right">Название компании</div>
					<div class="col-sm-8"><strong>'.$row['f_name'].'</strong></div>
				</div>
				<div class="form-group">
					<div class="col-sm-3 text-right">ИНН</div>
					<div class="col-sm-8">'.$row['f_inn'].'</div>
				</div>
				<div class="form-group">
					<div class="col-sm-3 text-right">КПП</div>
					<div class="col-sm-8">'.$row['f_kpp'].'</div>
				</div>
				<div class="form-group">
					<div class="col-sm-3 text-right">ОГРН</div>
					<div class="col-sm-8">'.$row['f_ogrn'].'</div>
				</div>
				<div class="form-group">
					<div class="col-sm-3 text-right">Адрес регистрации</div>
					<div class="col-sm-8">'.$row['f_address'].'</div>
				</div>
				<div class="form-group">
					<div class="col-sm-3 text-right">Должность руководителя</div>
					<div class="col-sm-8">'.$row['f_doljnost'].'</div>
				</div>		
				<div class="form-group">
					<div class="col-sm-3 text-right">Руководитель</div>
					<div class="col-sm-8">'.$row['f_director'].'</div>
				</div>
				<div class="form-group">
					<div class="col-sm-3 text-right">Руководитель (инициалы)</div>
					<div class="col-sm-8">'.$row['f_director_io'].'</div>
				</div>
				<div class="form-group">
					<div class="col-sm-3 text-right">Руководитель (р.п.)</div>
					<div class="col-sm-8">'.$row['f_director_r'].'</div>
				</div>
				<div class="form-group">
					<div class="col-sm-3 text-right">Паспорт руководителя</div>
					<div class="col-sm-8">'.$row['f_passport'].'</div>
				</div>
				<div class="form-group">
					<div class="col-sm-3 text-right">Телефон</div>
					<div class="col-sm-8">'.$row['f_tel'].'</div>
				</div>
				<div class="form-group">
					<div class="col-sm-3 text-right">E-mail</div>
					<div class="col-sm-8">'.$row['f_mail'].'</div>
				</div>
				<div class="form-group">
					<div class="col-sm-3 text-right">Факс О_о</div>
					<div class="col-sm-8">'.$row['f_fax'].'</div>
				</div>
				<div class="form-group">
					<div class="col-sm-3 text-right">Реквизиты</div>
					<div class="col-sm-8">'.$row['f_requisites'].'</div>
				</div>
				<div class="form-group">
					<div class="col-sm-3 text-right">Особые договорённости</div>
					<div class="col-sm-8">'.$row['f_agreement'].'</div>
				</div>
				<div class="form-group">
					<div class="col-sm-3 text-right">Заметки</div>
					<div class="col-sm-8">'.$row['f_notes'].'</div>
				</div>
					<div class="form-group">
					<div class="col-sm-3 text-right">На личном контроле?</div>
					<div class="col-sm-8">';if ($row['f_problem']==1) {echo '<span class="label label-danger">Да</span>';} else {echo 'нет';} echo '</div>
				</div>
				<br />
				<div class="form-group">
					<div class="col-sm-3 text-right">Заключён договор на почту?</div>
					<div class="col-sm-8">';if ($row['f_mail_s']==1) {echo '<span class="label label-success">Да</span>';} else {echo 'нет';} echo '</div>
				</div>
				<div class="form-group">
					<div class="col-sm-3 text-right">Дата начала договора на почту</div>
					<div class="col-sm-8">'.$row['f_mail_s_s'].'</div>
				</div>
				<div class="form-group">
					<div class="col-sm-3 text-right">Дата окончания договора на почту</div>
					<div class="col-sm-8">'.$row['f_mail_s_e'];
						if ($row['f_mail_s_e']<$today && $row['f_mail_s']==1) {echo ' <span class="label label-danger">ПРОСРОЧЕН!</span>';}
						elseif ($row['f_mail_s_e']<$m3 && $row['f_mail_s']==1) {echo ' <span class="label label-warning">Заканчивается</span>';}
					echo '</div>
				</div>
				<div class="form-group">
					<div class="col-sm-3 text-right">Договор на почту проверен?</div>
					<div class="col-sm-8">'; if ($row['f_mail_s_checked']==1) {echo '<span class="label label-success">Да</span>';} else {echo '<span class="label label-danger">НЕТ</span>';} echo '</div>
				</div>
				<br />
				<div class="form-group">
					<div class="col-sm-3 text-right">количество договоров</div>
					<div class="col-sm-8"><span class="badge">'.$kol2['0'].'</span></div>
				</div>
				<div class="form-group">
					<div class="col-sm-3 text-right">Удалить контрагента?<br /><em>Это действие нельзя отменить</em></div>
					<div class="col-sm-8">';
						if ($kol2['0']>0 && $row['f_mail_s']==1 && $row['f_mail_s_e']>$today) { echo '
							Нельзя удалять контрагента, к которому привязаны договора, а так же присутствует действующий договор на почту';
						}
						elseif ($kol2['0']>0) { echo '
							Нельзя удалять контрагента, к которому привязаны договора.';
						}
						elseif ($kol2['0']>0 && $row['f_mail_s']==1 && $row['f_mail_s_e']>$today) { echo '
							Нельзя удалять контрагента, у которого есть действующий договор на почту';
						}
						else {
							echo '
							<form class="form-horizontal" role="form" action="yes-delete-firm.php" method="get">
								<a href="firms.php"><button type="button" class="btn btn-success">НЕТ</button></a>
								<input type="hidden" name="id" value="'.$row['f_id'].'">
								<button type="submit" class="btn btn-danger">да</button>
							</form>';
						}
					echo '
					</div>	
				</div>
				<div class="form-group">
					<div class="col-sm-3 text-right">редактировать данные контрагента</div>
					<div class="col-sm-8">
						<form class="form-inline" role="form" action="firm.php" method="get">
							<input type="hidden" name="id" value="'.$row['f_id'].'">
							<button type="submits" class="btn btn-default">Редактировать</button>
						</form>
					</div>
				</div>';
				
	if ($kol2['0']>0) {echo '</div></div>';}
	
	if ($kol2['0']>0) { 
		$sql_dogovora = "SELECT * FROM  `contract` WHERE `c_company_id` ='$idset' ORDER BY  `contract`.`c_id` ASC ";
		$result_dogovora = mysqli_query($link, $sql_dogovora);

		echo '
		<div class="col-md-7">
			<table id="myTable" class="tablesorter table table-hover">
			<legend>Список всех договоров контрагента <strong>'.$row['f_name'].'</strong></legend>
			<thead>
				<tr>
					<th>ID</th>
					<th>Номер и дата договора</th>
					<th>Действует с</th>
					<th>Действует по</th>
					<th>редактировать</th>
					<th>удалить</th>
				</tr>
			</thead>
			<tbody>';
		while ($row_dog = mysqli_fetch_array($result_dogovora, MYSQLI_ASSOC)) {
		echo '
				<tr';
			if ($row_dog['c_date-po']>=$today && $row_dog['c_date-po']<$m1)
				{echo ' class="danger"';}
			elseif ($row_dog['c_date-po']<$m3 && $row_dog['c_date-po']>=$m1)
				{echo ' class="warning"';}
			elseif ($row_dog['c_date-po']>=$m3)
				{echo ' class="success"';}
	echo '>
					<td>'.$row_dog['c_id'].'</td>
					<td><a href="../contract/odogovore.php?id='.$row_dog['c_id'].'">Договор №'.$row_dog['c_nomer'].' от '.$row_dog['c_date'].'</a></td>
					<td>'.$row_dog['c_date-s'].'</td>
					<td>'.$row_dog['c_date-po'].'</td>
					<td>
						<form class="form-inline" role="form" action="../contract/dogovor.php" method="get">
							<input type="hidden" name="id" value="'.$row_dog['c_id'].'"><button type="submits" class="btn btn-default">Редактировать</button>
						</form>
					</td>
					<td>
						<form class="form-inline" role="form" action="../contract/delete-dogovor.php" method="get">
							<input type="hidden" name="id" value="'.$row_dog['c_id'].'"><button type="submits" class="btn btn-danger">Удалить</button>
						</form>
					</td>
				</tr>';
		}
		echo '
				</tbody>
			</table>
		</div>
	<br />
	<span class="label label-danger">Красным цветом выделены строки с договорами, истекающими в этом месяце;</span><br />
	<span class="label label-warning">жёлтым цветов - истекающие в ближайшие 3 месяца;</span><br />
	<span class="label label-success">зелёным цветом - действующие договора;</span><br />
	<span>белым цветом - закончившиеся договора.</span><br />';
	}

echo '
	</div>';

	/* очищаем результаты выборки */
	mysqli_free_result($result);
	mysqli_free_result($kol);
	mysqli_free_result($result_type);
	if ($kol2['0']>0) {mysqli_free_result($result_dogovora);}

	/* закрываем подключение */
	mysqli_close($link);
?>
	<script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
	<script>
		$(document).ready(function(){
		  $('a[href*=#]').bind("click", function(e){
		      var anchor = $(this);
		      $('html, body').stop().animate({
		        scrollTop: $(anchor.attr('href')).offset().top
		      }, 1000);
		      e.preventDefault();
		  });
		  return false;
		});
    </script>
	<script src="../js/bootstrap.min.js"></script>
</body>
</html>