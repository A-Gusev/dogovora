<?php
	if (isset($_COOKIE['u-id']) and isset($_COOKIE['hash'])) {
		# Вытаскиваем из БД запись, у которой id равняеться id из кук
		$cook_id=$_COOKIE['u-id']; 
		$sql_u_id = "SELECT * FROM `users` WHERE `u_id`='$cook_id'";
		$result_check = mysqli_query($link, $sql_u_id);
		$row_chek = mysqli_fetch_array($result_check, MYSQLI_ASSOC);

	    if(($row_chek['u_hash'] !== $_COOKIE['hash']) or ($row_chek['u_id'] !== $_COOKIE['u-id'])) {
	        setcookie("u-id", "", time() - 3600*24*30*12, "/");
	        setcookie("hash", "", time() - 3600*24*30*12, "/");
	        print "Кажется что-то пошло не так";
	    }
	    else {
	        $rights=$row_chek['u_rights'];
	    }
	}
	else {
	    //print "Включите печеньки!";
	    $rights=-1;
	}

	if ($rights < 1) {
      	if ($page=='home') {
      		$page_link='user/';
      	}
      	else {
      		$page_link='../user/';
      	}
 
		echo '<p><a href="'.$page_link.'login.php">Пройдите авторизацию</a></p>';
		exit();
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

	//
	/* пути для ссылок меню */	
	$page_up='../';
	$page_contract='contract/';
	$page_company='company/';
	$page_config='config/';
?>

<nav class="navbar navbar-default" role="navigation">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
        <span class="sr-only">Переключатель</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" class="active" href="/">Система учёта договоров аренды</a>
    </div>
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
      	<?php
      		$active='';
      		if ($page=='home') {
	      		$page_link='company/';
      		}
      		elseif ($page=='contract') {
	      		$page_link='../company/';
      		}
      		elseif ($page=='company') {
	      		$page_link='';
	      		$active=' active';
      		}
      		elseif ($page=='config') {
	      		$page_link='../company/';
      		}
      	?>
        <li class="dropdown<?php echo $active; ?>">
          <a href="" class="dropdown-toggle" data-toggle="dropdown">Контрагенты <span class="caret"></span><?php if ($menu_kol_ok['0']>0) {echo ' <span class="badge red">'.$menu_kol_ok['0'].'</span>';}?></a>
          <ul class="dropdown-menu" role="menu">
            <li><a href="<?php echo $page_link; ?>firms.php?ref=all">Полный список контрагентов <span class="badge pull-right"><?=$menu_kol_k['0']?></span></a></li>
            <li><a href="<?php echo $page_link; ?>firms.php?ref=ok">Контрагенты на особом контроле <span class="badge red"><?=$menu_kol_ok['0']?></span></a></li>
            <li class="divider"></li>
            <li><a href="<?php echo $page_link; ?>new-firm.php">Создать нового контрагента</a></li>
          </ul>
        </li>
        <?php
        	$active='';
      		if ($page=='home') {
	      		$page_link='contract/';
      		}
      		elseif ($page=='contract') {
	      		$page_link='';
	      		$active=' active';
      		}
      		elseif ($page=='company') {
	      		$page_link='../contract/';
      		}
      		elseif ($page=='config') {
	      		$page_link='../contract/';
      		}
      	?>
        <li class="dropdown<?php echo $active; ?>">
          <a href="" class="dropdown-toggle" data-toggle="dropdown">Договора <span class="caret"></span><?php if ($menu_kol_c3['0']>0) {echo ' <span class="badge yellow">'.$menu_kol_c3['0'].'</span>';}?></a>
          <ul class="dropdown-menu" role="menu">
            <li><a href="<?php echo $page_link; ?>dogovora.php?ref=all">Полный список договоров <span class="badge pull-right"><?=$menu_kol_c['0']?></span></a></li>
            <li><a href="<?php echo $page_link; ?>dogovora.php?ref=red">Закончатся в ближайшие 30 дней <span class="badge pull-right red"><?=$menu_kol_c1['0']?></span></a></li>
            <li><a href="<?php echo $page_link; ?>dogovora.php?ref=yellow">Закончатся в ближайшие 3 месяца <span class="badge pull-right yellow"><?=$menu_kol_c3['0']?></span></a></li>
            <li><a href="<?php echo $page_link; ?>dogovora.php?ref=green">Действующие договора <span class="badge pull-right"><?=$menu_kol_c0['0']?></span><br />(закончатся больше чем через 3 месяца) <span class="badge pull-right green"><?=$menu_kol_c4['0']?></span></a></li>
        <!--    <li><a href="#">Поиск договоров, действующих в определённый период</a></li>  -->
            <li class="divider"></li>
            <li><a href="<?php echo $page_link; ?>new-dogovor.php">Создать новый договор</a></li>
          </ul>
        </li>
      </ul>

	  <?php
      		if ($page=='home') {
	      		$page_link='';
      		}
      		else {
	      		$page_link='../';
      		}
      echo '<ul class="nav navbar-nav navbar-right">
        <li class="dropdown">
          <a href="'.$page_link.'exit.php">Выход</a>
        </li>
      </ul>';
      
		  if ($page=='home') {
	      		$page_link='user/';
      		}
      		else {
	      		$page_link='../user/';
      		}
     echo '<ul class="nav navbar-nav navbar-right">
        <li class="dropdown">
          <a href="'.$page_link.'user.php">';
          if ($row_chek['u_name'] == '') {
	          echo $row_chek['u_login'];
          }
          else {
	          echo $row_chek['u_name'];
          }
          echo '</a>
        </li>
      </ul>';


	?>
      
      <ul class="nav navbar-nav navbar-right">
        <li class="dropdown">
        <?php
      		if ($page=='home') {
	      		$page_link='company/';
      		}
      		elseif ($page=='contract') {
	      		$page_link='../company/';
      		}
      		elseif ($page=='company') {
	      		$page_link='';
      		}
      		elseif ($page=='config') {
	      		$page_link='../company/';
      		}
      	?>
          <a href="" class="dropdown-toggle" data-toggle="dropdown">Договора на почту <span class="caret"></span><?php if ($mail_red > 0) {echo ' <span class="badge red">'.$mail_red.'</span> ';} if ($menu_mail_ch['0'] > 0) {echo ' <span class="badge yellow">'.$menu_mail_ch['0'].'</span>';} ?></a>
          <ul class="dropdown-menu" role="menu">
            <li><a href="<?php echo $page_link; ?>firms.php?ref=mail-pr">Договор просрочен <span class="badge pull-right red"><?=$menu_mail_pr['0']?></span></a></li>
            <li><a href="<?php echo $page_link; ?>firms.php?ref=mail-pr3">Договор заканчивается (3 месяца) <span class="badge orange"><?=$menu_mail_pr3['0']?></span></a></li>
            <li><a href="<?php echo $page_link; ?>firms.php?ref=mail-ch">Договор не проверен <span class="badge pull-right yellow"><?=$menu_mail_ch['0']?></span></a></li>
            <li><a href="<?php echo $page_link; ?>firms.php?ref=mail-s">Договор не заключён <span class="badge pull-right"><?=$menu_mail_s['0']?></span></a></li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>

<nav class="navbar navbar-default navbar-fixed-bottom" role="navigation">
	  <div class="container-fluid">
	    <div class="navbar-header">
	      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
	        <span class="sr-only">Переключатель</span>
	        <span class="icon-bar"></span>
	        <span class="icon-bar"></span>
	        <span class="icon-bar"></span>
	      </button>
	    </div>
	    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
	    <?php
      		if ($page=='home') {
	      		$page_link='company/';
	      		$page_link2='contract/';
      		}
      		elseif ($page=='contract') {
	      		$page_link='../company/';
	      		$page_link2='';
      		}
      		elseif ($page=='company') {
	      		$page_link='';
	      		$page_link2='../contract/';
      		}
      		elseif ($page=='config') {
	      		$page_link='company/';
	      		$page_link2='contract/';
      		}
      	?>
	  <!--    <ul class="nav navbar-nav">
	      </ul>
	      <form class="navbar-form navbar-left" role="search" action="<?php echo $page_link; ?>firms.php" method="get">
	        <div class="form-group">
	          <input type="text" class="form-control" placeholder="Поиск по контрагентам" name="search">
	        </div>
	        <button type="submit" class="btn btn-default">Найти</button>
	      </form>
	      <form class="navbar-form navbar-left" role="search" action="<?php echo $page_link2; ?>dogovora.php" method="get">
	        <div class="form-group">
	          <input type="text" class="form-control" placeholder="Поиск по договорам" name="search">
	        </div>
	        <button type="submit" class="btn btn-default">Найти</button>
	      </form> -->
	      <ul class="nav navbar-nav navbar-right pad">
			  <li><a class="pad" title="Создание сайта - Студия Design4net" target="_blank" href="https://github.com/A-Gusev/dogovora/">v.1.0.1</a></li>
			  <li><a class="pad" title="Создание сайта - Студия Design4net" target="_blank" href="http://design4net.ru/">2014 - 2015 <span class="glyphicon glyphicon-copyright-mark"></span> Создание сайта - Студия Design4net</a></li>
	      </ul>
	    </div>
	  </div>
</nav>