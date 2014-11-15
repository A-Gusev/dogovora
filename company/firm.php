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
		<title>Редактирование данных контрагента</title>
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

	/* /Запросы для меню закончились :) */
	//

	/* забираем данные из формы */
	$idset=$_REQUEST['id'];

	/* подготавливаем запрос к БД */
	$query = "SELECT * FROM `firms`
	WHERE  `firms`.`f_id`='$idset'";
	$result = mysqli_query($link, $query);

	/* Запрос на получение количества договоров */	
	$sql_kol = "SELECT COUNT(`c_nomer`)
	FROM `contract` JOIN `firms` ON `contract`.`c_company_id` = `firms`.`f_id`
	WHERE `firms`.`f_id`='$idset'";
	$kol = mysqli_query($link, $sql_kol);

	/* Получение массивов */
	$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
	$kol2 = mysqli_fetch_array($kol, MYSQLI_NUM);	

	/* Запрос на получение типа контрагента */
	$query_type = "SELECT `type_firm`.`tf_id` , `type_firm`.`tf_type`
	FROM `type_firm`";
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
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">Договора <span class="caret"></span><?php if ($menu_kol_c3['0']>0) {echo ' <span class="badge yellow">'.$menu_kol_c3['0'].'</span>';}?></a>
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
	

	/* вывод в форму */
	echo '<form class="form-horizontal" role="form" action="update-firm.php" method="post">
	<legend>Редактирование данных контрагента <strong>'.$row['f_name'].'</strong></legend>	
	<div class="form-group">
		<label class="col-sm-3 control-label">ID</label>
		<div class="col-sm-8">
			<input readonly class="form-control" name="id" value="'.$row['f_id'].'">
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label">Тип контрагента</label>
		<div class="col-sm-8">
			<select class="form-control" name="company_type">';
				while ($row_type = mysqli_fetch_array($result_type, MYSQLI_ASSOC)) {
					echo '<option value="'.$row_type['tf_id'].'"';
					if ($row_type['tf_id']==$row['f_id_type']) echo ' selected ';
					echo ' >' . $row_type['tf_type'] . '</option>';
				}
	echo '
			</select>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label">Название компании</label>
		<div class="col-sm-8">
			<input autofocus required placeholder="Введите название компании" class="form-control" name="name" value="'.$row['f_name'].'">
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label">ИНН</label>
		<div class="col-sm-8">
			<input pattern="[0-9]{10,12}" title="Введите от 10 до 12 цифр ИНН" placeholder="Введите от 10 до 12 цифр ИНН" class="form-control" name="inn" value="'.$row['f_inn'].'">
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label">КПП</label>
		<div class="col-sm-8">
			<input pattern="[0-9]{9}" title="Введите 9 цифр КПП" placeholder="Введите 9 цифр КПП" class="form-control" name="kpp" value="'.$row['f_kpp'].'">
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label">ОГРН</label>
		<div class="col-sm-8">
			<input pattern="[0-9]{13}" title="Введите 13 цифр ОГРН" placeholder="Введите 13 цифр ОГРН" class="form-control" name="ogrn" value="'.$row['f_ogrn'].'">
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label">Адрес регистрации</label>
		<div class="col-sm-8">
			<input class="form-control" title="Введите адрес регистрации организации" placeholder="Введите адрес регистрации организации" name="address" value="'.$row['f_address'].'">
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label">Директор</label>
		<div class="col-sm-8">
			<input class="form-control" title="Введите фамилию, имя и отчество директора" placeholder="Введите фамилию, имя и отчество директора" name="director" value="'.$row['f_director'].'">
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label">Директор (инициалы)</label>
		<div class="col-sm-8">
			<input class="form-control" title="Введите фамилию и инициалы директора" placeholder="Введите фамилию и инициалы директора" name="director_io" value="'.$row['f_director_io'].'">
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label">Директор (р.п.)</label>
		<div class="col-sm-8">
			<input class="form-control" title="Введите фамилию, имя и отчество директора в родительном падеже" placeholder="Введите фамилию, имя и отчество директора в родительном падеже" name="director_r" value="'.$row['f_director_r'].'">
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label">Паспорт директора</label>
		<div class="col-sm-8">
			<textarea class="form-control" title="Укажите данные паспорта директора" placeholder="Укажите данные паспорта директора" name="passport" rows="4">'.$row['f_passport'].'</textarea>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label">Телефон</label>
		<div class="col-sm-8">
			<input class="form-control" title="Укажите номер/(а) телефона/(ов)" placeholder="Укажите номер(а) телефона(ов)" type="tel" name="tel" value="'.$row['f_tel'].'">
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label">E-mail</label>
		<div class="col-sm-8">
			<input class="form-control" type="email" multiple placeholder="Введите E-mail адресс" title="Введите E-mail адресс. При необходимости указания нескольких адресов, укажите их через запятую." name="mail" value="'.$row['f_mail'].'">
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label">Факс</label> О_о
		<div class="col-sm-8">
			<input class="form-control" title="Укажите номер факса" placeholder="Укажите номер факса" name="fax" value="'.$row['f_fax'].'">
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label">Реквизиты</label>
		<div class="col-sm-8">
			<textarea class="form-control" title="Укажите реквизиты организации" placeholder="Укажите реквизиты организации" name="requisites" rows="4">'.$row['f_requisites'].'</textarea>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label">Особые договорённости</label>
		<div class="col-sm-8">
			<textarea class="form-control" title="Особые сверхсекретные договорённости" placeholder="Особые договорённости" name="agreement" rows="4">'.$row['f_agreement'].'</textarea>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label">Заметки</label>
		<div class="col-sm-8">
			<textarea class="form-control" title="Заметки" placeholder="Укажите заметки" name="notes" rows="4">'.$row['f_notes'].'</textarea>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 text-right">На контроле?</label>
		<div class="col-sm-8">
			<input name="problem" type="checkbox" value="1" '; if ($row['f_problem']==1) {echo 'checked';} echo '> Контрагент на личном контроле директора!
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 text-right">Договор на почту?</label>
		<div class="col-sm-8">
			<input name="mail_s" type="checkbox" value="1" '; if ($row['f_mail_s']==1) {echo 'checked';} echo '> С контрагентом заключён договор на почтовое обслуживание!
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label">Договор на почту с...</label>
		<div class="col-sm-8">
			<input type="date" class="form-control" title="Дата начала действия договора на почту" name="mail_s_s" max="2020-01-01" min="2000-01-01" value="'.$row['f_mail_s_s'].'">
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label">Договор на почту по...</label>
		<div class="col-sm-8">
			<input type="date" class="form-control" title="Дата окончания действия договора на почту" name="mail_s_e" max="2020-01-01" min="2000-01-01" value="'.$row['f_mail_s_e'].'">
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 text-right">Договор на почту проверен?</label>
		<div class="col-sm-8">
			<input name="mail_s_checked" type="checkbox" value="1" '; if ($row['f_mail_s_checked']==1) {echo 'checked';} echo '> Договор с контрагентом на почту проверен!
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 text-right">Количество договоров</label>';
			if ($kol2['0']>0) {
				echo '<div class="col-sm-8"><a href="dogovor-firm.php?id='.$row['f_id'].'"><span class="badge">'.$kol2['0'].'</span></a></div>';
			}
			else {
				echo '<div class="col-sm-8">'.$kol2['0'].'</div>';
			}
		echo '
	</div>	
	<div class="form-group">
		<div class="col-sm-offset-5">
			<input type="hidden" name="id" value="'.$row['f_id'].'">
			<button type="submit" class="btn btn-default" name="button" value="save">Сохранить</button>
			<button type="submit" class="btn btn-success" name="button" value="close">Сохранить и закрыть</button>
		</div>
	</div>
</form>';

	/* очищаем результаты выборки */
	mysqli_free_result($result);
	mysqli_free_result($kol);
	mysqli_free_result($result_type);

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