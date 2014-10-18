<!DOCTYPE html>
<html>
    <head>
	    <meta charset="utf-8">
	    <meta HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
	    <title>Создание нового контрагента</title>
		<link rel="stylesheet" href="../css/bootstrap.min.css">
		<link rel="stylesheet" href="../css/bootstrap-theme.min.css">
	</head>
<body>
<?php
	/* выключаем кэширование */
	Header("Cache-Control: no-store, no-cache, must-revalidate");
	Header("Pragma: no-cache");
	Header("Last-Modified: " . gmdate("D, d M Y H:i:s") . "GMT");
	Header("Expires: " . date("r"));

	/* форма */
	echo '<form class="form-horizontal" role="form" action="update-new-firm.php" method="post">
	<legend>Создание нового контрагента</legend>

	<div class="form-group">
		<label class="col-sm-3 control-label">Название компании</label>
		<div class="col-sm-8">
			<input class="form-control" type="text" name="name">
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label">ФИО директора</label>
		<div class="col-sm-8">
			<input class="form-control" type="text" name="director">
		</div>
	</div>	
	<div class="form-group">
		<label class="col-sm-3 control-label">Реквизиты</label>
		<div class="col-sm-8">
			<textarea class="form-control" rows="8" name="requisites"></textarea>
		</div>
	</div>
	<div class="form-group">
		<div class="col-sm-offset-5">
			<button type="submit" class="btn btn-default" name="button" value="save">Сохранить</button>
			<button type="submit" class="btn btn-success" name="button" value="close">Сохранить и закрыть</button>
		</div>
	</div>
</form>';

	echo '<br /><br /><p><a href="../index.php">Home</a> :: <a href="firms.php">Список контрагентов</a> :: <a href="new-firm.php">Создать нового контрагента</a></p>';

	/* очищаем результаты выборки */
	mysqli_free_result($result);
	mysqli_free_result($result2);

	/* закрываем подключение */
	mysqli_close($link);
?>

	<script src="../js/bootstrap.min.js"></script>
</body>
</html>