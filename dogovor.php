<!DOCTYPE html>
<html>
    <head>
	    <meta charset="utf-8">
	    <meta HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
	    <title>Редактирование договора</title>
		<link rel="stylesheet" href="css/bootstrap.min.css">
		<link rel="stylesheet" href="css/bootstrap-theme.min.css">
	</head>
<body>
<?php
	/* выключаем кэширование */
	Header("Cache-Control: no-store, no-cache, must-revalidate");
	Header("Pragma: no-cache");
	Header("Last-Modified: " . gmdate("D, d M Y H:i:s") . "GMT");
	Header("Expires: " . date("r"));
	
	require_once 'login.php';
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

	/* забираем данные из формы */
	$idset=$_REQUEST['id'];
	
	/* подготавливаем запрос к БД */
//	$query = "SELECT * FROM `contract` WHERE `id` = '$idset'";
	$query = "SELECT `contract`.`id`, `contract`.`nomer`, `contract`.`date`, `company`.`name`, `contract`.`prim`
	FROM `contract` JOIN `company` ON `contract`.`company_id` = `company`.`id`
	WHERE  `contract`.`id`='$idset'";
	$result = mysqli_query($link, $query);
	
	/* Получение ассоциативного массива */
	$row = mysqli_fetch_array($result, MYSQLI_ASSOC);

	/* вывод в форму */	
	echo '<form action="update-dogovor.php" method="post" name="u-setting">';
	echo '<legend>Редактирование договора</legend>';
	echo '<table class="table table-hover">
	<tbody>
		<tr> 
			<td>Номер договора</td><td><input type="text" name="nomer" size="35" value="'.$row['nomer'].'"></td>
		</tr>
		<tr>
			<td>Дата договора</td><td><input type="date" min="2000-01-01" name="date" value="'.$row['date'].'"></td>
		</tr>
		<tr>
			<td>Имя компании</td><td><input type="text" name="name" size="35" value="'.$row['name'].'"></td>
		</tr>
		<tr>			
			<td>Примечания</td><td><textarea rows="5" cols="35" name="prim">'.$row['prim'].'</textarea></td>
		</tr>
	</tbody>';
	echo '</table>';
	echo '<input type="hidden" name="id" value="'.$row['id'].'">';
	echo '<p align="right"><input class="btn btn-primary" id="submit" type="submit" value="Редактировать договор"></p></form>';
	
	
	echo '<br /><br /><p><a href="dogovora.php">Назад</a><br /><br />';
	echo '<a href="index.php">Home</a>';	
	
	/* очищаем результаты выборки */
	mysqli_free_result($result);
	
	/* закрываем подключение */
	mysqli_close($link);
?>
 
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
</body>
</html>