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
		<title>Создание нового договора</title>
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


	/* запрос на имя компании */
	$query2 = "SELECT `firms`.`f_id` , `firms`.`f_name`
	FROM `firms`";
	$result2 = mysqli_query($link, $query2);

	/* Запрос на получение типа контракта */
	$query_type = "SELECT `type_contract`.`tc_id` , `type_contract`.`tc_type`
	FROM `type_contract`";
	$result_type = mysqli_query($link, $query_type);

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
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">Контрагенты <span class="caret"></span><?php if ($menu_kol_ok['0']>0) {echo ' <span class="badge red">'.$menu_kol_ok['0'].'</span>';}?></a>
          <ul class="dropdown-menu" role="menu">
            <li><a href="../company/firms.php?ref=all">Полный список контрагентов <span class="badge pull-right"><?=$menu_kol_k['0']?></span></a></li>
            <li><a href="../company/firms.php?ref=ok">Контрагенты на особом контроле <span class="badge red"><?=$menu_kol_ok['0']?></span></a></li>
            <li class="divider"></li>
            <li><a href="../company/new-firm.php">Создать нового контрагента</a></li>
          </ul>
        </li>
        <li class="dropdown active">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">Договора <span class="caret"></span><?php if ($menu_kol_c3['0']>0) {echo ' <span class="badge yellow">'.$menu_kol_c3['0'].'</span>';}?></a>
          <ul class="dropdown-menu" role="menu">
            <li><a href="dogovora.php?ref=all">Полный список договоров <span class="badge pull-right"><?=$menu_kol_c['0']?></span></a></li>
            <li><a href="dogovora.php?ref=red">Закончатся в ближайшие 30 дней <span class="badge pull-right red"><?=$menu_kol_c1['0']?></span></a></li>
            <li><a href="dogovora.php?ref=yellow">Закончатся в ближайшие 3 месяца <span class="badge pull-right yellow"><?=$menu_kol_c3['0']?></span></a></li>
            <li><a href="dogovora.php?ref=green">Действующие договора <span class="badge pull-right"><?=$menu_kol_c0['0']?></span><br />(закончатся больше чем через 3 месяца) <span class="badge pull-right green"><?=$menu_kol_c4['0']?></span></a></li>
            <li><a href="#">Поиск договоров, действующих в определённый период</a></li>
            <li class="divider"></li>
            <li><a href="new-dogovor.php">Создать новый договор</a></li>
          </ul>
        </li>
      </ul>
      <ul class="nav navbar-nav navbar-right">
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">Договора на почту <span class="caret"></span><?php if ($mail_red > 0) {echo ' <span class="badge red">'.$mail_red.'</span> ';} if ($menu_mail_ch['0'] > 0) {echo ' <span class="badge yellow">'.$menu_mail_ch['0'].'</span>';} ?></a>
          <ul class="dropdown-menu" role="menu">
            <li><a href="../company/firms.php?ref=mail-pr">Договор просрочен <span class="badge pull-right red"><?=$menu_mail_pr['0']?></span></a></li>
            <li><a href="../company/firms.php?ref=mail-pr3">Договор заканчивается (3 месяца) <span class="badge orange"><?=$menu_mail_pr3['0']?></span></a></li>
            <li><a href="../company/firms.php?ref=mail-ch">Договор не проверен <span class="badge pull-right yellow"><?=$menu_mail_ch['0']?></span></a></li>
            <li><a href="../company/firms.php?ref=mail-s">Договор не заключён <span class="badge pull-right"><?=$menu_mail_s['0']?></span></a></li>
          </ul>
        </li>
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>

<?php


	/* вывод в форму */
	echo '<form class="form-horizontal" role="form" action="update-new-dogovor.php" method="post">
	<legend>Создание нового договора</legend>
	<div class="form-group">
		<label class="col-sm-3 control-label">Номер договора</label>
		<div class="col-sm-8">
			<input title="Введите номер договора" placeholder="Введите номер договора" class="form-control" name="nomer" value="б/н">
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label">Дата заключения договора</label>
		<div class="col-sm-8">
			<input required type="date" class="form-control" title="Введите дату заключения договора" name="date" max="2020-01-01" min="2000-01-01">
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label">Тип договора</label>
		<div class="col-sm-8">
			<select class="form-control" name="id_type_dog">';
				while ($row_type = mysqli_fetch_array($result_type, MYSQLI_ASSOC)) {
					echo '<option value="'.$row_type['tc_id'].'"';
					echo '>' . $row_type['tc_type'] . '</option>';
				}
	echo '
			</select>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label">Название компании</label>
		<div class="col-sm-8">
			<select class="form-control" name="name">';
			while ($row2 = mysqli_fetch_array($result2, MYSQLI_ASSOC)) {
		echo '<option value="'.$row2['f_id'].'"';
		echo ' >' . $row2['f_name'] . '</option>';
	}
	echo '
			</select>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label">Договор с...</label>
		<div class="col-sm-8">
			<input required type="date" class="form-control" title="Договор действителен с..." name="date-s" max="2020-01-01" min="2000-01-01">
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label">Договор по...</label>
		<div class="col-sm-8">
			<input required type="date" class="form-control" title="Договор действителен до..." name="date-po" max="2020-01-01" min="2000-01-01">
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label">Дата акта</label>
		<div class="col-sm-8">
			<input type="date" class="form-control" title="Дата акта" name="date-akt" max="2020-01-01" min="2000-01-01">
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label">Номер помещения</label>
		<div class="col-sm-8">
			<input class="form-control" title="Номер помещения" placeholder="Введите номер помещения" name="number">
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label">Площадь помещения в м<sup>2</sup></label>
		<div class="col-sm-8">
			<input class="form-control" title="Номер помещения" placeholder="Введите номер помещения" name="m2">
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label">Примечания</label>
		<div class="col-sm-8">
			<textarea class="form-control" title="Примечания" placeholder="Укажите примечания" name="prim" rows="4"></textarea>
		</div>
	</div>	
	<div class="form-group">
		<div class="col-sm-offset-5">
			<button type="submit" class="btn btn-default" name="button" value="save">Сохранить</button>
			<button type="submit" class="btn btn-success" name="button" value="close">Сохранить и закрыть</button>
		</div>
	</div>
</form>';

	/* очищаем результаты выборки */
	mysqli_free_result($result_type);
	mysqli_free_result($result2);

	/* закрываем подключение */
	mysqli_close($link);
?>
	<br /><br /><br /><nav class="navbar navbar-default navbar-fixed-bottom" role="navigation">
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
	      <form class="navbar-form navbar-left" role="search" action="../company/firms.php" method="get">
	        <div class="form-group">
	          <input type="text" class="form-control" placeholder="Поиск по контрагентам" name="search">
	        </div>
	        <button type="submit" class="btn btn-default">Найти</button>
	      </form>
	      <form class="navbar-form navbar-left" role="search" action="dogovora.php" method="get">
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