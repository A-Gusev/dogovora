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

	/* забираем данные из формы; Запрос на получение данных из таблицы firms */
	if (array_key_exists('ref', $_REQUEST)) {
		$ref=$_REQUEST['ref'];
		if ($ref=='ok') {
			$query = "SELECT * FROM `firms` WHERE `f_problem`='1' ORDER BY  `firms`.`f_id` ASC";
		}
		elseif ($ref=='mail-pr') {
			$query = "SELECT * FROM `firms` WHERE TO_DAYS(`f_mail_s_e`) - TO_DAYS(NOW()) <= 0 AND `f_mail_s` > 0 ORDER BY  `firms`.`f_id` ASC";
		}
		elseif ($ref=='mail-pr3') {
			$query = "SELECT * FROM `firms` WHERE TO_DAYS(`f_mail_s_e`) - TO_DAYS(NOW()) < 93 AND TO_DAYS(`f_mail_s_e`) - TO_DAYS(NOW()) > 0 AND `f_mail_s` > 0 ORDER BY  `firms`.`f_id` ASC";
		}
		elseif ($ref=='mail-ch') {
			$query = "SELECT * FROM `firms` WHERE `f_mail_s_checked` = 0 AND`f_mail_s` > 0 ORDER BY  `firms`.`f_id` ASC";
		}
		elseif ($ref=='mail-s') {
			$query = "SELECT * FROM `firms` WHERE `f_mail_s` = 0 ORDER BY  `firms`.`f_id` ASC";
		}
		else {
			$query = "SELECT * FROM `firms` ORDER BY  `firms`.`f_id` ASC";
		}
	}
	else {
		$query = "SELECT * FROM `firms` ORDER BY  `firms`.`f_id` ASC";
	}

	/* Запрос на получение данных из таблицы firms */
	$result = mysqli_query($link, $query);

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
	echo '<table id="myTable" class="tablesorter table table-hover">
	<caption><h3>Список контрагентов';
	if (array_key_exists('ref', $_REQUEST)) {
		$ref=$_REQUEST['ref'];
		if ($ref=='ok') {echo ', находящихся на "личном контроле" руководителя';}
		elseif ($ref=='mail-pr') {echo ' с просроченными договорами на почту';}
		elseif ($ref=='mail-pr3') {echo ' с заканчивающимися договорами на почту (3 месяца)';}
		elseif ($ref=='mail-ch') {echo ' с не проверенными договорами на почту';}
		elseif ($ref=='mail-s') {echo ' не заключивших договор на почтовое обслуживание';}
	}
	echo '</h3></caption>
	<thead>
		<tr>
			<th>id</th>
			<th>тип</th>
			<th>yазвание компании</th>
			<th>ИНН</th>
			<th>КПП</th>
			<th>ОГРН</th>
			<th>директор</th>
			<th>телефон</th>
			<th>e-mail</th>
			<th>количество договоров</th>
			<th>Договор на почту</th>
			<th>редактировать</th>
			<th>удалить</th>
		</tr>
	</thead>
	<tbody>';

	/* ассоциативный массив */
	while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {

	/* Запрос на получение количества договоров */
	$sql_kol = "SELECT COUNT(`c_nomer`)
	FROM `contract` JOIN `firms` ON `contract`.`c_company_id` = `firms`.`f_id`
	WHERE `firms`.`f_id`=".$row['f_id'];
	$kol = mysqli_query($link, $sql_kol);
	$kol2 = mysqli_fetch_array($kol, MYSQLI_NUM);
	
	/* Запрос на получение типа контрагента */
	$query_type = "SELECT `type_firm`.`tf_id` , `type_firm`.`tf_type`
	FROM `type_firm`
	WHERE `type_firm`.`tf_id`=".$row['f_id'];
	$result_type = mysqli_query($link, $query_type);
	$row_type = mysqli_fetch_array($result_type, MYSQLI_ASSOC);
	
	
	echo '
		<tr';
			if (array_key_exists('ref', $_REQUEST)) {
				$ref=$_REQUEST['ref'];
				if ($ref=='ok') {}
				else {
					if ($row['f_problem']==1) {echo ' class="info"';}
				}
			}
			else {
				if ($row['f_problem']==1) {echo ' class="info"';}
			}
	echo '>
			<td>'.$row['f_id'].'</td></td>
			<td>'.$row_type['tf_type'].'</td>
			<td><a href="dogovor-firm.php?id='.$row['f_id'].'">'.$row['f_name'].'</a></td>
			<td>'.$row['f_inn'].'</td>
			<td>'.$row['f_kpp'].'</td>
			<td>'.$row['f_ogrn'].'</td>
			<td>'.$row['f_director_io'].'</td>
			<td>'.$row['f_tel'].'</td>
			<td>'.$row['f_mail'].'</td>';
			if ($kol2['0']>0) {
				echo '<td><a href="dogovor-firm.php?id='.$row['f_id'].'">'.$kol2['0'].'</a></td>';
			}
			else {
				echo '<td>'.$kol2['0'].'</td>';
			}
			echo '
			<td>';
				if ($row['f_mail_s_e']<$today && $row['f_mail_s']==1) {echo '<span class="label label-danger">ПРОСРОЧЕН!</span>';}
				elseif ($row['f_mail_s_e']<$m3 && $row['f_mail_s']==1) {echo '<span class="label label-warning">Заканчивается</span>';}
				elseif ($row['f_mail_s']==1 && $row['f_mail_s_checked']==1) {echo '<span class="label label-success">Заключён и проверен</span>';}
				elseif ($row['f_mail_s']==1 && $row['f_mail_s_checked']==0) {echo '<span class="label label-warning">Заключён, но не проверен</span>';}
				else {echo '<span class="label label-default">Не заключен</span>';}
			echo '
			</td>
			<td>
				<form class="form-inline" role="form" action="firm.php" method="get">
					<input type="hidden" name="id" value="'.$row['f_id'].'">
					<button type="submits" class="btn btn-default">Редактировать</button>
				</form>
			</td>
			<td>';
				if ($kol2['0']==0 && $row['f_mail_s']==0) { echo '
				<form class="form-inline" role="form" action="delete-firm.php" method="get">
					<input type="hidden" name="id" value="'.$row['f_id'].'">
					<button type="submits" class="btn btn-danger">Удалить</button>
				</form>
			';
			}
			echo '</td>
		</tr>';
	}

	echo '
	</tbody>
</table>
';
			if (array_key_exists('ref', $_REQUEST)) {
				$ref=$_REQUEST['ref'];
				if ($ref=='ok') {}
				else {echo '
	<br /><span class="label label-info">* - синим фонов выделены строки с контрагентами, находящимися на "личном контроле" директора</span><br />';
				}
			}
			else {echo '
	<br /><span class="label label-info">* - синим фонов выделены строки с контрагентами, находящимися на "личном контроле" директора</span><br />';
			}

	/* очищаем результаты выборки */
	mysqli_free_result($result);
	mysqli_free_result($kol);
	mysqli_free_result($result_type);

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