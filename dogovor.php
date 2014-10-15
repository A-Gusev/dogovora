<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 3.2 Final//EN">
<HTML>
    <HEAD>
    <meta charset="utf-8">
    <META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
    <TITLE>База данных ДОГОВОР</TITLE>
    <link href="http://getbootstrap.com/dist/css/bootstrap.min.css" rel="stylesheet">
    </HEAD>
<BODY>

<?php 
Header("Cache-Control: no-store, no-cache, must-revalidate");
Header("Pragma: no-cache");
Header("Last-Modified: " . gmdate("D, d M Y H:i:s") . "GMT");
Header("Expires: " . date("r"));

require_once 'login.php';
$link = mysqli_connect($host, $user, $password, $db);

/* проверка подключения */
if (mysqli_connect_errno()) {
    printf("Не удалось подключиться: %s\n", mysqli_connect_error());
    exit();
}

if (!$link->set_charset("utf8")) {
    printf("Ошибка при загрузке набора символов utf8: %s\n", $link->error);
}
//else {
//    printf("Текущий набор символов: %s\n", $link->character_set_name());
//}

$query = "SELECT `contract`.`id`, `contract`.`nomer`, `contract`.`date`, `company`.`name`, `contract`.`prim` FROM `contract` JOIN `company` ON `contract`.`company_id` = `company`.`id` WHERE `contract`.`id`=1";
$result = mysqli_query($link, $query);

/*
print_r($result);
print_r($row);
echo '<br />';
*/

/* ассоциативный массив */
$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
	// echo $row['nomer'].$row['date'].$row['name'].$row['prim'];
	
//printf($row['nomer'].' '.$row['date'].' '.$row['name'].' '.$row['prim'].' '."<form class='form-inline' role='form' action='update-dogovor.php' method='get' name='forma'>
//		<input type='hidden' name='id' value='%s'><button type='submits' class='btn btn-default'>Редактировать</button>
//	</form>",$row['id']);



printf("<form action='update-dogovor.php' method='post' name='forma'>
	<fieldset>
		<input type='hidden' name='id' value='%s'><br/>
		<label for='company_name'>Название компании:</label><br/><input type='text' name='company_name' size='30' value='%s'><br /><br />
		<label for='job_title'>Должность директора:</label><br/> <input type='text' name='job_title' size='30' value='%s'><br/>
		<label for='job_title_r'>Должность директора (родительный):</label><br/> <input type='text' name='job_title_r' size='30' value='%s'><br/><br />
	</fieldset>
	<br/>
	<fieldset>
		<input id='submit' type='submit' value='Редактировать запись'><br/></fieldset>
	</form>",$row['id'], $row['nomer'], $row['date'], $row['name'], $row['prim']);










/* очищаем результаты выборки */
mysqli_free_result($result);

/* закрываем подключение */
mysqli_close($link);
?>
 

</BODY>
</HTML>