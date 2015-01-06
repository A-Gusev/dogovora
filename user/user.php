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
		<title>Редактирование договора</title>
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

	/* Вывод меню */
	$page='config';
	require_once ('../nav.php');

	/* вывод в форму */
	echo '<form class="form-horizontal" role="form" method="post" action="update-user.php">
	<legend>Редактирование пароля</legend>	
	<div class="form-group">
		<label class="col-sm-3 control-label">ID</label>
		<div class="col-sm-8">
			<input readonly class="form-control" name="id" value="'.$cook_id.'">
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label">Login</label>
		<div class="col-sm-8">
			<input readonly class="form-control" name="login" value="'.$row_chek['u_login'].'">
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label">Имя</label>
		<div class="col-sm-8">
			<input title="Введите ваше имя" placeholder="Введите Ваше имя" class="form-control" name="u-name" value="'.$row_chek['u_name'].'">
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label">Текущий пароль</label>
		<div class="col-sm-8">
			<input title="Введите текущий пароль" placeholder="Введите текущий пароль" class="form-control" name="psw-old">
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label">Новый пароль</label>
		<div class="col-sm-8">
			<input title="Введите новый пароль" placeholder="Введите новый пароль" class="form-control" name="psw-new">
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label">Повторите ввод нового пароля</label>
		<div class="col-sm-8">
			<input title="Введите новый пароль ещё раз" placeholder="Введите новый пароль ещё раз" class="form-control" name="psw-new2">
		</div>
	</div>
	<div class="form-group">
		<div class="col-sm-offset-5">
			<button type="submit" class="btn btn-default" name="button" value="save">Сохранить</button>
		</div>
	</div>
</form>
';

	/* закрываем подключение */
	mysqli_close($link);
?>
	<br /><br /><br />
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