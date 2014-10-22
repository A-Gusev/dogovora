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
		<title>Создание нового контрагента</title>
		<link rel="stylesheet" href="../css/bootstrap.min.css">
		<link rel="stylesheet" href="../css/bootstrap-theme.min.css">
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

	/* Запрос на получение типа контрагента */
	$query_type = "SELECT `type_firm`.`tf_id` , `type_firm`.`tf_type`
	FROM `type_firm`";
	$result_type = mysqli_query($link, $query_type);

	/* форма */
	echo '<form class="form-horizontal" role="form" action="update-new-firm.php" method="post">
	<legend>Создание нового контрагента</legend>
	<div class="form-group">
		<label class="col-sm-3 control-label">Тип контрагента</label>
		<div class="col-sm-8">
			<select class="form-control" name="company_type">';
				while ($row_type = mysqli_fetch_array($result_type, MYSQLI_ASSOC)) {
					echo '<option value="'.$row_type['tf_id'].'">'.$row_type['tf_type'].'</option>';
				}
	echo '
			</select>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label">Название компании</label>
		<div class="col-sm-8">
			<input autofocus required placeholder="Введите название компании" class="form-control" name="name">
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label">ИНН</label>
		<div class="col-sm-8">
			<input pattern="[0-9]{10,12}" title="Введите от 10 до 12 цифр ИНН" placeholder="Введите от 10 до 12 цифр ИНН" class="form-control" name="inn">
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label">КПП</label>
		<div class="col-sm-8">
			<input pattern="[0-9]{9}" title="Введите 9 цифр КПП" placeholder="Введите 9 цифр КПП" class="form-control" name="kpp">
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label">ОГРН</label>
		<div class="col-sm-8">
			<input pattern="[0-9]{13}" title="Введите 13 цифр ОГРН" placeholder="Введите 13 цифр ОГРН" class="form-control" name="ogrn">
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label">Адрес регистрации</label>
		<div class="col-sm-8">
			<input class="form-control" title="Введите адрес регистрации организации" placeholder="Введите адрес регистрации организации" name="address">
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label">Дирктор</label>
		<div class="col-sm-8">
			<input class="form-control" title="Введите фамилию, имя и отчество директора" placeholder="Введите фамилию, имя и отчество директора" name="director">
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label">Директор (инициалы)</label>
		<div class="col-sm-8">
			<input class="form-control" title="Введите фамилию и инициалы директора" placeholder="Введите фамилию и инициалы директора" name="director_io">
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label">Директор (р.п.)</label>
		<div class="col-sm-8">
			<input class="form-control" title="Введите фамилию, имя и отчество директора в родительном падеже" placeholder="Введите фамилию, имя и отчество директора в родительном падеже" name="director_r">
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label">Паспорт директора</label>
		<div class="col-sm-8">
			<textarea class="form-control" title="Укажите данные паспорта директора" placeholder="Укажите данные паспорта директора" name="passport" rows="4"></textarea>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label">Телефон</label>
		<div class="col-sm-8">
			<input class="form-control" title="Укажите номер/(а) телефона/(ов)" placeholder="Укажите номер(а) телефона(ов)" type="tel" name="tel">
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label">E-mail</label>
		<div class="col-sm-8">
			<input class="form-control" type="email" multiple placeholder="Введите E-mail адресс" title="Введите E-mail адресс. При необходимости указания нескольких адресов, укажите их через запятую." name="mail">
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label">Факс</label> О_о
		<div class="col-sm-8">
			<input class="form-control" title="Укажите номер факса" placeholder="Укажите номер факса" name="fax">
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label">Реквизиты</label>
		<div class="col-sm-8">
			<textarea class="form-control" title="Укажите реквизиты организации" placeholder="Укажите реквизиты организации" name="requisites" rows="4"></textarea>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label">Особые договорённости</label>
		<div class="col-sm-8">
			<textarea class="form-control" title="Особые сверхсекретные договорённости" placeholder="Особые договорённости" name="agreement" rows="4"></textarea>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label">Заметки</label>
		<div class="col-sm-8">
			<textarea class="form-control" title="Заметки" placeholder="Укажите заметки" name="notes" rows="4"></textarea>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 text-right">На контроле?</label>
		<div class="col-sm-8">
			<input name="problem" type="checkbox" value="1"> Контрагент на личном контроле директора?
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 text-right">Договор на почту?</label>
		<div class="col-sm-8">
			<input name="mail_s" type="checkbox" value="1"> С контрагентом заключён договор на почтовое обслуживание?
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label">Договор на почту с...</label>
		<div class="col-sm-8">
			<input type="date" class="form-control" title="Дата начала действия договора на почту" name="mail_s_s" max="2020-01-01" min="2000-01-01">
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label">Договор на почту по...</label>
		<div class="col-sm-8">
			<input type="date" class="form-control" title="Дата окончания действия договора на почту" name="mail_s_e" max="2020-01-01" min="2000-01-01">
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 text-right">Договор на почту проверен?</label>
		<div class="col-sm-8">
			<input name="mail_s_checked" type="checkbox" value="1"> Договор с контрагентом на почту проверен?
		</div>
	</div>
	<div class="form-group">
		<div class="col-sm-offset-5">
			<button type="submit" class="btn btn-default" name="button" value="save">Сохранить</button>
			<button type="submit" class="btn btn-success" name="button" value="close">Сохранить и закрыть</button>
		</div>
	</div>
</form>
';

	echo '
<br /><br />
<div class="text-center">
	<a href="../index.php">Home</a> :: <a href="firms.php">Список контрагентов</a> :: <a href="new-firm.php">Создать нового контрагента</a>
</div>
';

	/* очищаем результаты выборки */
	mysqli_free_result($result_type);

	/* закрываем подключение */
	mysqli_close($link);
?>
	<script src="../js/bootstrap.min.js"></script>
</body>
</html>