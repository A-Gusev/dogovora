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

	//
	/* Запросы для меню */
	//
	
	/* Запрос на получение количества контрагентов на особом контроле */
	$sql_menu_ok = "SELECT COUNT(`f_problem`)
	FROM `firms`
	WHERE `firms`.`f_problem`= '1'";
	$menu_ok = mysqli_query($link, $sql_menu_ok);
	$menu_kol_ok = mysqli_fetch_array($menu_ok, MYSQLI_NUM);

	/* Запрос на получение количества контрагентов */
	$sql_menu_k = "SELECT COUNT(`f_id`)
	FROM `firms`";
	$menu_k = mysqli_query($link, $sql_menu_k);
	$menu_kol_k = mysqli_fetch_array($menu_k, MYSQLI_NUM);

	/* Запрос на получение количества договоров */
	$sql_menu_c = "SELECT COUNT(`c_id`)
	FROM `contract`";
	$menu_c = mysqli_query($link, $sql_menu_c);
	$menu_kol_c = mysqli_fetch_array($menu_c, MYSQLI_NUM);

	/* Запрос на получение количества действующих договоров */
	$sql_menu_c0 = "SELECT COUNT(`c_date-po`)
	FROM `contract`
	WHERE TO_DAYS(`c_date-po`) - TO_DAYS(NOW()) >= 0";
	$menu_c0 = mysqli_query($link, $sql_menu_c0);
	$menu_kol_c0 = mysqli_fetch_array($menu_c0, MYSQLI_NUM);

	/* Запрос на получение количества договоров, которые закончатся больше чем через 3 месяца */
	$sql_menu_c4 = "SELECT COUNT(`c_date-po`)
	FROM `contract`
	WHERE TO_DAYS(`c_date-po`) - TO_DAYS(NOW()) >= 93";
	$menu_c4 = mysqli_query($link, $sql_menu_c4);
	$menu_kol_c4 = mysqli_fetch_array($menu_c4, MYSQLI_NUM);

	/* Запрос на получение количества договоров, которые закончатся в ближайшие 3 месяца */
	$sql_menu_c3 = "SELECT COUNT(`c_date-po`)
	FROM `contract`
	WHERE TO_DAYS(`c_date-po`) - TO_DAYS(NOW()) < 93 AND TO_DAYS(`c_date-po`) - TO_DAYS(NOW()) > 0";
	$menu_c3 = mysqli_query($link, $sql_menu_c3);
	$menu_kol_c3 = mysqli_fetch_array($menu_c3, MYSQLI_NUM);

	/* Запрос на получение количества договоров, которые закончатся в ближайшие 30 дней */
	$sql_menu_c1 = "SELECT COUNT(`c_date-po`)
	FROM `contract`
	WHERE TO_DAYS(`c_date-po`) - TO_DAYS(NOW()) < 31 AND TO_DAYS(`c_date-po`) - TO_DAYS(NOW()) > 0";
	$menu_c1 = mysqli_query($link, $sql_menu_c1);
	$menu_kol_c1 = mysqli_fetch_array($menu_c1, MYSQLI_NUM);

	/* Запрос на получение количества просроченных договоров на почту */
	$sql_menu_mail_pr = "SELECT COUNT(`f_mail_s_e`)
	FROM `firms`
	WHERE TO_DAYS(`f_mail_s_e`) - TO_DAYS(NOW()) <= 0 AND `f_mail_s` > 0";
	$menu_mail_pr = mysqli_query($link, $sql_menu_mail_pr);
	$menu_mail_pr = mysqli_fetch_array($menu_mail_pr, MYSQLI_NUM);

	/* Запрос на получение количества заканчивающихся договоров на почту (3 месяца) */
	$sql_menu_mail_pr3 = "SELECT COUNT(`f_mail_s_e`)
	FROM `firms`
	WHERE TO_DAYS(`f_mail_s_e`) - TO_DAYS(NOW()) < 93 AND TO_DAYS(`f_mail_s_e`) - TO_DAYS(NOW()) > 0  AND`f_mail_s` > 0";
	$menu_mail_pr3 = mysqli_query($link, $sql_menu_mail_pr3);
	$menu_mail_pr3 = mysqli_fetch_array($menu_mail_pr3, MYSQLI_NUM);

	/* Запрос на получение количества не проверенных договоров */
	$sql_menu_mail_ch = "SELECT COUNT(`f_mail_s_e`)
	FROM `firms`
	WHERE `f_mail_s_checked` = 0 AND`f_mail_s` > 0";
	$menu_mail_ch = mysqli_query($link, $sql_menu_mail_ch);
	$menu_mail_ch = mysqli_fetch_array($menu_mail_ch, MYSQLI_NUM);

	/* Запрос на получение количества не заключённых договоров */
	$sql_menu_mail_s = "SELECT COUNT(`f_mail_s_e`)
	FROM `firms`
	WHERE `f_mail_s` = 0";
	$menu_mail_s = mysqli_query($link, $sql_menu_mail_s);
	$menu_mail_s = mysqli_fetch_array($menu_mail_s, MYSQLI_NUM);

	$mail_red = $menu_mail_pr['0'] + $menu_mail_pr3['0'];

	//
	/* /Запросы для меню закончились :) */
	//


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


?>
<nav class="navbar navbar-default" role="navigation">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" class="active" href="/">Система учёта договоров аренды</a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <li class="dropdown active">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">Контрагенты <span class="caret"></span><?php if ($menu_kol_ok['0']>0) {echo ' <span class="badge red">'.$menu_kol_ok['0'].'</span>';}?></a>
          <ul class="dropdown-menu" role="menu">
            <li><a href="firms.php?ref=all">Полный список контрагентов <span class="badge pull-right"><?=$menu_kol_k['0']?></span></a></li>
            <li><a href="firms.php?ref=ok">Контрагенты на особом контроле <span class="badge red"><?=$menu_kol_ok['0']?></span></a></li>
            <li class="divider"></li>
            <li><a href="new-firm.php">Создать нового контрагента</a></li>
          </ul>
        </li>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">Договора <span class="caret"></span><?php if ($menu_kol_c3['0']>0) {echo ' <span class="badge red">'.$menu_kol_c3['0'].'</span>';}?></a>
          <ul class="dropdown-menu" role="menu">
            <li><a href="../contract/dogovora.php?ref=all">Полный список договоров <span class="badge pull-right"><?=$menu_kol_c['0']?></span></a></li>
            <li><a href="../contract/dogovora.php?ref=red">Закончатся в ближайшие 30 дней <span class="badge pull-right red"><?=$menu_kol_c1['0']?></span></a></li>
            <li><a href="../contract/dogovora.php?ref=yellow">Закончатся в ближайшие 3 месяца <span class="badge pull-right yellow"><?=$menu_kol_c3['0']?></span></a></li>
            <li><a href="../contract/dogovora.php?ref=green">Действующие договора <span class="badge pull-right"><?=$menu_kol_c0['0']?></span><br />(закончатся больше чем через 3 месяца) <span class="badge pull-right green"><?=$menu_kol_c4['0']?></span></a></li>
            <li><a href="#">Поиск договоров, действующих в определённый период</a></li>
            <li class="divider"></li>
            <li><a href="../contract/new-dogovor.php">Создать новый договор</a></li>
          </ul>
        </li>
      </ul>
      <ul class="nav navbar-nav navbar-right">
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">Договора на почту <span class="caret"></span><?php if ($mail_red > 0) {echo ' <span class="badge red">'.$mail_red.'</span> ';} if ($menu_mail_ch['0'] > 0) {echo ' <span class="badge yellow">'.$menu_mail_ch['0'].'</span>';} ?></a>
          <ul class="dropdown-menu" role="menu">
            <li><a href="firms.php?ref=mail-pr">Договор просрочен <span class="badge pull-right red"><?=$menu_mail_pr['0']?></span></a></li>
            <li><a href="firms.php?ref=mail-pr3">Договор заканчивается (3 месяца) <span class="badge orange"><?=$menu_mail_pr3['0']?></span></a></li>
            <li><a href="firms.php?ref=mail-ch">Договор не проверен <span class="badge pull-right yellow"><?=$menu_mail_ch['0']?></span></a></li>
            <li><a href="firms.php?ref=mail-s">Договор не заключён <span class="badge pull-right"><?=$menu_mail_s['0']?></span></a></li>
          </ul>
        </li>
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>

<?php

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
					<div class="col-sm-3 text-right">Дирктор</div>
					<div class="col-sm-8">'.$row['f_director'].'</div>
				</div>
				<div class="form-group">
					<div class="col-sm-3 text-right">Директор (инициалы)</div>
					<div class="col-sm-8">'.$row['f_director_io'].'</div>
				</div>
				<div class="form-group">
					<div class="col-sm-3 text-right">Директор (р.п.)</div>
					<div class="col-sm-8">'.$row['f_director_r'].'</div>
				</div>
				<div class="form-group">
					<div class="col-sm-3 text-right">Паспорт директора</div>
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
	<nav class="navbar navbar-default navbar-fixed-bottom" role="navigation">
	  <div class="container-fluid">
	    <!-- Brand and toggle get grouped for better mobile display -->
	    <div class="navbar-header">
	      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
	        <span class="sr-only">Toggle navigation</span>
	        <span class="icon-bar"></span>
	        <span class="icon-bar"></span>
	        <span class="icon-bar"></span>
	      </button>
	    </div>
	    <!-- Collect the nav links, forms, and other content for toggling -->
	    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
	      <ul class="nav navbar-nav">
	      </ul>
	      <form class="navbar-form navbar-left" role="search" action="firms.php" method="get">
	        <div class="form-group">
	          <input type="text" class="form-control" placeholder="Поиск по контрагентам" name="search">
	        </div>
	        <button type="submit" class="btn btn-default">Найти</button>
	      </form>
	      <form class="navbar-form navbar-left" role="search" action="../contract/dogovora.php" method="get">
	        <div class="form-group">
	          <input type="text" class="form-control" placeholder="Поиск по договорам" name="search">
	        </div>
	        <button type="submit" class="btn btn-default">Найти</button>
	      </form>
	      <ul class="nav navbar-nav navbar-right">
			  <li><a title="Создание сайта - Студия Design4net" target="_blank" href="http://design4net.ru/">2014 <span class="glyphicon glyphicon-copyright-mark"></span> Создание сайта - Студия Design4net</a></li>
	      </ul>
	    </div><!-- /.navbar-collapse -->
	  </div><!-- /.container-fluid -->
	</nav>
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