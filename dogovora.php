<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 3.2 Final//EN">
<HTML>
    <HEAD>
    <meta charset="utf-8">
    <META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
    <TITLE>База данных ДОГОВОРA</TITLE>
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

$query = "SELECT `contract`.`id`, `contract`.`nomer`, `contract`.`date`, `company`.`name`, `contract`.`prim` FROM `contract` JOIN `company` ON `contract`.`company_id` = `company`.`id` ORDER BY  `contract`.`id` ASC";
$result = mysqli_query($link, $query);

/*
print_r($result);
print_r($row);
echo '<br />';
*/

/* ассоциативный массив */
while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
	// echo $row['nomer'].$row['date'].$row['name'].$row['prim'];
	
printf($row['nomer'].' '.$row['date'].' '.$row['name'].' '.$row['prim'].' '."<form class='form-inline' role='form' action='dogovor.php' method='get' name='forma'>
		<input type='hidden' name='id' value='%s'><button type='submits' class='btn btn-default'>Редактировать</button>
	</form>",$row['id']);


}



/*
<form method="GET" action="add.php">
  <label>
    <input type="text" name="page" id="textfield">
  </label>
  <label>
    <input type="submit" name="button" id="button" value="Отправить">
  </label>
</form>

*/



/* очищаем результаты выборки */
mysqli_free_result($result);

/* закрываем подключение */
mysqli_close($link);
?>
 

</BODY>
</HTML>