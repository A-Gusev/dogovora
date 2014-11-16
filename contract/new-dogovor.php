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

	/* запрос на определение имени компании */
	$query2 = "SELECT `firms`.`f_id` , `firms`.`f_name`
	FROM `firms`";
	$result2 = mysqli_query($link, $query2);

	/* Запрос на получение типа контракта */
	$query_type = "SELECT `type_contract`.`tc_id` , `type_contract`.`tc_type`
	FROM `type_contract`";
	$result_type = mysqli_query($link, $query_type);

	/* Вывод меню */
	require_once ('nav.php');

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