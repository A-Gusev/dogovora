<?php
	/* выключаем кэширование */
	Header("Cache-Control: no-store, no-cache, must-revalidate");
	Header("Pragma: no-cache");
	Header("Last-Modified: " . gmdate("D, d M Y H:i:s") . "GMT");
	Header("Expires: " . date("r"));

	require_once '../login.php';
	$link=mysqli_connect($host, $user, $password, $db);

	/* проверка подключения */
	if (mysqli_connect_errno()) {
	    printf("Не удалось подключиться: %s\n", mysqli_connect_error());
	    exit();
	}

	/* установка кодировки utf8 */
	if (!$link->set_charset("utf8")) {
	    printf("Ошибка при загрузке набора символов utf8: %s\n", $link->error);
	}
	if (isset($_COOKIE['u-id']) and isset($_COOKIE['hash'])) {
		# Вытаскиваем из БД запись, у которой id равняеться id из кук
		$cook_id=$_COOKIE['u-id']; 
		$sql_id = "SELECT * FROM `users` WHERE `u_id`='$cook_id'";
		$result_check = mysqli_query($link, $sql_id);
		$row_chek = mysqli_fetch_array($result_check, MYSQLI_ASSOC);
	    if(($row_chek['u_hash'] !== $_COOKIE['hash']) or ($row_chek['u_id'] !== $_COOKIE['u-id'])) {
	        setcookie("u-id", "", time() - 3600*24*30*12, "/");
	        setcookie("hash", "", time() - 3600*24*30*12, "/");
	        print "Кажется что-то пошло не так";
            exit();
	    }
	    else {
			if (isset($_POST['button'])) {
				if ($_POST['psw-old'] == '') {
					$u_name=htmlentities(trim($_POST['u-name']));
					$update_sql = "UPDATE `admin_arenda`.`users` SET `u_name` = '$u_name' WHERE `users`.`u_id` = '$cook_id'";
					/* отправляем запрос к БД */
					mysqli_query($link, $update_sql) or die("Ошибка: " . mysql_error());
					
					header('Location: user.php');
				}
				else {
					$u_name=htmlentities(trim($_POST['u-name']));
					if($row_chek['u_psw'] == sha1(md5('bro'.$_POST['psw-old'].'ha'))) {
						if($_POST['psw-new'] == $_POST['psw-new2']) {
							$u_psw=sha1(md5('bro'.$_POST['psw-new'].'ha'));
							$update_sql = "UPDATE `admin_arenda`.`users` SET
							`u_name` = '$u_name',
							`u_psw` = '$u_psw'
							WHERE `users`.`u_id` = '$cook_id'";
							/* отправляем запрос к БД */
							mysqli_query($link, $update_sql) or die("Ошибка: " . mysql_error());
							header('Location: user.php');
						}
						else {
							header('Location: user.php?error=new');
						}
					}
					else {
						header('Location: user.php?error=old');
					}
				}		
			}
	    }
	}

	/* закрываем подключение */
	mysqli_close($link);
?>