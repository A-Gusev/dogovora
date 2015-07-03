<!DOCTYPE html>
<html lang="ru">
    <head>
	    <meta charset="utf-8">
		<meta http-equiv="Cache-Control" content="no-cache">
		<title>О договоре</title>
		<meta name="author" content="Alexey Gusev" />
		<meta name="rights" content="Студия Design4net.ru" />
		<link rel="stylesheet" href="../../css/bootstrap.min.css">
		<link rel="stylesheet" href="../../css/bootstrap-theme.min.css">
		<link rel="stylesheet" href="../../css/my.css">
	</head>
	<body>
	<?php
    /* Узнаем какое сегодня число */
        $today = date("Y-m-d");
    /* 1 месяца вперёд (31 день) */
        $m1 = date("Y-m-d" ,time()+60*60*24*31);
    /* 3 месяца вперёд (93 дня) */
        $m3 = date("Y-m-d" ,time()+60*60*24*31*3);
    ?>
	<!-- МЕНЮ --> 
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
					$routes = explode('/', $_SERVER['REQUEST_URI']);
					$page = $routes[1];
					$active='';
					if ($page=='company') {
						$active=' active';
					}
				?>
				<li class="dropdown<?=$active;?>">
				  <a href="" class="dropdown-toggle" data-toggle="dropdown">Контрагенты&nbsp;<span class="caret"></span>
                      <?php
                        if (application\models\Model_statistic::company_special_control() > 0) {
                            echo ' <span class="badge red">'.application\models\Model_statistic::company_special_control().'</span>';
                        }
                      ?>
                  </a>
				  <ul class="dropdown-menu" role="menu">
					<li><a href="<?=$page_link; ?>firms.php?ref=all">Полный список контрагентов&nbsp;
                            <span class="badge pull-right">
                                <?=application\models\Model_statistic::company_count()?>
                            </span></a></li>
					<li><a href="<?=$page_link; ?>firms.php?ref=ok">Контрагенты на особом контроле&nbsp;
                            <span class="badge red pull-right">
                                <?=application\models\Model_statistic::company_special_control()?>
                            </span></a></li>
					<li class="divider"></li>
					<li><a href="<?=$page_link; ?>new-firm.php">Создать нового контрагента</a></li>
				  </ul>
				</li>
				<?php
					$active='';
					if ($page=='contract') {
						$active=' active';
					}
				?>
				<li class="dropdown<?=$active;?>">
				  <a href="" class="dropdown-toggle" data-toggle="dropdown">Договора&nbsp;<span class="caret"></span>
                      <?php
                          if (application\models\Model_statistic::contract_expiry_date_3_months()>0) {
                              echo ' <span class="badge yellow">'.application\models\Model_statistic::contract_expiry_date_3_months().'</span>';
                          }
                      ?>
                  </a>
				  <ul class="dropdown-menu" role="menu">
					<li><a href="/contract/actual">Действующие договора&nbsp;
                            <span class="badge pull-right green">
                                <?=application\models\Model_statistic::contract_actual()?>
                            </span></a></li>
					<li><a href="/contract/expiry_date_30_days">Закончатся в ближайшие 30 дней&nbsp;
                            <span class="badge pull-right red">
                                <?=application\models\Model_statistic::contract_expiry_date_30_days()?>
                            </span></a></li>
					<li><a href="/contract/expiry_date_3_months">Закончатся в ближайшие 3 месяца&nbsp;
                            <span class="badge pull-right yellow">
                                <?=application\models\Model_statistic::contract_expiry_date_3_months()?>
                            </span></a></li>
					<li><a href="/contract/all">Полный список договоров&nbsp;
                            <span class="badge pull-right">
                                <?=application\models\Model_statistic::contract_all()?>
                            </span></a></li>

				<!--    <li><a href="#">Поиск договоров, действующих в определённый период</a></li>  -->
					<li class="divider"></li>
					<li><a href="/contract/new">Создать новый договор</a></li>
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
				  <a href="'.$page_link.'exit.php" title="Выход"><span class="glyphicon glyphicon-log-out" aria-hidden="true"></span></a>
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
				  <a href="'.$page_link.'user.php" title="Редактировать профиль"><span class="glyphicon glyphicon-user" aria-hidden="true"></span></a>
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
				  <a href="" class="dropdown-toggle" data-toggle="dropdown">Договора на почту <span class="caret"></span>
                      <?php // if ($mail_red > 0) {echo ' <span class="badge red">'.$mail_red.'</span> ';} if ($menu_mail_ch['0'] > 0) {echo ' <span class="badge yellow">'.$menu_mail_ch['0'].'</span>';} ?>
                  </a>
				  <ul class="dropdown-menu" role="menu">
					<li><a href="<?=$page_link;?>firms.php?ref=mail-pr">Договор просрочен <span class="badge pull-right red"><?=$menu_mail_pr['0']?></span></a></li>
					<li><a href="<?=$page_link;?>firms.php?ref=mail-pr3">Договор заканчивается (3 месяца) <span class="badge pull-right orange"><?=$menu_mail_pr3['0']?></span></a></li>
					<li><a href="<?=$page_link;?>firms.php?ref=mail-ch">Договор не проверен <span class="badge pull-right yellow"><?=$menu_mail_ch['0']?></span></a></li>
					<li><a href="<?=$page_link;?>firms.php?ref=mail-s">Договор не заключён <span class="badge pull-right"><?=$menu_mail_s['0']?></span></a></li>
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
				  <form class="navbar-form navbar-left" role="search" action="<?php echo $page_link2; ?>contracta.php" method="get">
					<div class="form-group">
					  <input type="text" class="form-control" placeholder="Поиск по договорам" name="search">
					</div>
					<button type="submit" class="btn btn-default">Найти</button>
				  </form> -->
				  <ul class="nav navbar-nav navbar-right pad">
				  <?php
				    // if ($rights == 2) {
				    echo '<li><a class="pad" title="Основные настройки" href="/config/setting.php"><span class="glyphicon glyphicon-cog btn btn-info mybtn" aria-hidden="true"></span></a></li>';
					// }
				  ?>
					  <li><a class="pad" target="_blank" href="https://github.com/A-Gusev/dogovora/">v.2.0.0</a></li>
					  <li><a class="pad" title="Создание сайта - Студия Design4net" target="_blank" href="http://design4net.ru/">2014 - 2015 <span class="glyphicon glyphicon-copyright-mark"></span> Создание сайта - Студия Design4net</a></li>
				  </ul>
				</div>
			  </div>
		</nav>
	<!-- /МЕНЮ --> 

		<?php include 'application/views/'.$content_view; ?>
		
	<script src="../../js/jquery.min.js"></script>
	<script src="../../js/bootstrap.min.js"></script>
	</body>
</html>