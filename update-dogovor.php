<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 3.2 Final//EN">
<HTML>
    <HEAD>
    <meta charset="utf-8">
    <TITLE>База данных АРЕНДА</TITLE>
    </HEAD>
<BODY>
<?php 
require_once 'login.php';
$db_server = mysql_connect($host, $user, $password);
if (!$db_server) die("Невозможно подключиться".mysql_error());
mysql_select_db($db)
	or die("Невозможно выбрать БД".mysql_error());
mysql_query("SET NAMES 'utf8'"); 
mysql_query("SET CHARACTER SET 'utf8'");
mysql_query("SET SESSION collation_connection = 'utf8_general_ci'");


$select_sql = "SELECT * FROM `gl_settings` WHERE idset=1";
$result = mysql_query($select_sql);
$row = mysql_fetch_array($result);


$idset=$_REQUEST['idset'];
$company_name=trim($_REQUEST['company_name']);
$job_title=trim($_REQUEST['job_title']);
$job_title_r=trim($_REQUEST['job_title_r']);
$director=trim($_REQUEST['director']);
$director_io=trim($_REQUEST['director_io']);
$director_r=trim($_REQUEST['director_r']);
$bank_account_1=trim($_REQUEST['bank_account-1']);
$bank_account_2=trim($_REQUEST['bank_account-2']);

//$update_sql = "UPDATE users SET first_name='$first_name', last_name='$last_name', email='$email', facebook='$facebook' WHERE id='$id'";
$update_sql = "UPDATE `admin_arenda`.`gl_settings` SET `company_name` = '$company_name', `job_title` = '$job_title', `job_title_r` = '$job_title_r', `director` = '$director', `director_io` = '$director_io', `director_r` = '$director_r', `bank_account-1` = '$bank_account_1', `bank_account-2` = '$bank_account_2' WHERE `gl_settings`.`idset` = 1;";
mysql_query($update_sql) or die("Ошибка вставки" . mysql_error()); echo '<p>Запись успешно обновлена!</p>';

echo '<p><a href="index.php">Home</a><br /><a href="setting.php">Глобальные настройки</a></p>';

?>
</BODY>
</HTML>