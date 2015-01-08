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
		<title>Редактирование данных арендодателя</title>
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

	$query = "SELECT * FROM `settings` WHERE s_id=1";
	$result = mysqli_query($link, $query);

	/* Получение ассоциативного массива */
	$row = mysqli_fetch_array($result, MYSQLI_ASSOC);

	/* вывод меню */
	$page='config';
	require_once ('../nav.php');

	/* вывод в форму */	
echo '<form class="form-horizontal" role="form" action="update-setting.php" method="post" name="setting">
	<legend>Редактирование данных арендодателя</legend>
	<div class="form-group">
		<label class="col-sm-3 control-label">Название компании</label>
		<div class="col-sm-8">
			<input class="form-control" type="text" name="company_name" value="'.$row['s_company_name'].'">
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label">Должность руководителя</label>
		<div class="col-sm-8">
			<input class="form-control" type="text" name="job_title" value="'.$row['s_job_title'].'">
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label">Должность руководителя (родительный)</label>
		<div class="col-sm-8">
			<input class="form-control" type="text" name="job_title_r" value="'.$row['s_job_title_r'].'">
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label">ФИО руководителя</label>
		<div class="col-sm-8">
			<input class="form-control" type="text" name="director" value="'.$row['s_director'].'">
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label">ФИО руководителя (инициалы)</label>
		<div class="col-sm-8">
			<input class="form-control" type="text" name="director_io" value="'.$row['s_director_io'].'">
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label">ФИО руководителя (родительный)</label>
		<div class="col-sm-8">
			<input class="form-control" type="text" name="director_r" value="'.$row['s_director_r'].'">
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label">Реквизиты №1</label>
		<div class="col-sm-8">
			<textarea class="form-control" rows="8" name="bank_account-1">'.$row['s_bank_account-1'].'</textarea>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label">Реквизиты №2</label>
		<div class="col-sm-8">
			<textarea class="form-control" rows="8" name="bank_account-2">'.$row['s_bank_account-2'].'</textarea>
		</div>
	</div>
	<div class="form-group">
		<div class="col-sm-offset-9">
			<input type="hidden" name="id" value="'.$row['s_id'].'">
			<button type="submit" class="btn btn-default">Редактировать настройки</button>
		</div>
	</div>
</form>
<br /><br /><br />
';
	/* очищаем результаты выборки */
	mysqli_free_result($result);

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