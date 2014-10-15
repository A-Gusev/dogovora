<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 3.2 Final//EN">
<HTML>
    <HEAD>
    <meta charset="utf-8">
    <META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
    <TITLE>База данных АРЕНДА</TITLE>
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




$query = "SELECT * FROM `gl_settings` WHERE id=1";
$result = mysqli_query($link, $query);

/* ассоциативный массив */
$row = mysqli_fetch_array($result, MYSQLI_ASSOC);

printf("<form action='update-setting.php' method='post' name='forma'>
	<fieldset>
		<input type='hidden' name='id' value='%s'><br/>
		<label for='company_name'>Название компании:</label><br/><input type='text' name='company_name' size='30' value='%s'><br /><br />
		<label for='job_title'>Должность директора:</label><br/> <input type='text' name='job_title' size='30' value='%s'><br/>
		<label for='job_title_r'>Должность директора (родительный):</label><br/> <input type='text' name='job_title_r' size='30' value='%s'><br/><br />
		<label for='director'>ФИО директора</label><br/> <input type='text' name='director' size='30' value='%s'><br/>
		<label for='director_io'>ФИО директора (инициалы)</label><br/> <input type='text' name='director_io' size='30' value='%s'><br/>
		<label for='director_r'>ФИО директора (родительный)</label><br/> <input type='text' name='director_r' size='30' value='%s'><br/><br />		
		<label for='bank_account-1'>Реквизиты №1</label><br/> <textarea rows='5' cols='35' name='bank_account-1'>%s</textarea><br/>
		<label for='bank_account-2'>Реквизиты №2</label><br/> <textarea rows='5' cols='35' name='bank_account-2'>%s</textarea><br />
	</fieldset>
	<br/>
	<fieldset>
		<input id='submit' type='submit' value='Редактировать запись'><br/></fieldset>
	</form>",$row['id'], $row['company_name'], $row['job_title'], $row['job_title_r'], $row['director'], $row['director_io'], $row['director_r'], $row['bank_account-1'], $row['bank_account-2']);



/* очищаем результаты выборки */
mysqli_free_result($result);

/* закрываем подключение */
mysqli_close($link);
?>
</BODY>
</HTML>


