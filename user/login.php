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
		<link rel="stylesheet" href="../css/bootstrap.min.css">
		<link rel="stylesheet" href="../css/bootstrap-theme.min.css">
		<link rel="stylesheet" href="../css/my.css">
	</head>
	<body>
		<h1>Система учёта договоров аренды</h1>
		<form class="form-horizontal" role="form" method="post" action="../auth.php">
			<legend>Введите Ваш логин и пароль</legend>	
			<div class="form-group">
				<label class="col-sm-3 control-label">Логин</label>
				<div class="col-sm-8">
					<input autofocus required placeholder="Введите логин" class="form-control" name="login">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label">Пароль</label>
				<div class="col-sm-8">
					<input type="password" placeholder="Введите пароль" class="form-control" name="password">
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-offset-5">
					<button type="submit" class="btn btn-default" name="submit" value="Войти">Войти</button>
				</div>
			</div>
		</form>
	</body>
</html>