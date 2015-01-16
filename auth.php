<?php

# Функция для генерации случайной строки
function generateCode($length=6) {
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHI JKLMNOPRQSTUVWXYZ0123456789";
    $code = "";
    $clen = strlen($chars) - 1;
    while (strlen($code) < $length) {
        $code .= $chars[mt_rand(0,$clen)];
    }
    return $code;
}

	require_once 'login.php';
	$link = mysqli_connect($host, $user, $password, $db);
	$nologin=0;

	if(isset($_POST['submit'])) {
	    # Вытаскиваем из БД запись, у которой логин равняется введенному
		$l=$_POST['login']; 
		$sql_l = "SELECT `u_id`, `u_psw` FROM `users` WHERE `u_login`='$l'";
		$result = mysqli_query($link, $sql_l);
		$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
		$i=$row['u_id'];
	
	    #  Сравниваем пароли
	    if($row['u_psw'] === sha1(md5('bro'.$_POST['password'].'ha'))) {      
	        # Генерируем случайное число и шифруем его
	        $hash = md5(generateCode(10));
	        # Записываем в БД новый хеш авторизации
	        $sql_u = "UPDATE `users` SET `u_hash` = '$hash' WHERE `u_id`='$i'";
	        $result_u = mysqli_query($link, $sql_u);
	        # Ставим куки
	        setcookie('u-id', $i, time()+60*60*24*30);
	        setcookie('hash', $hash, time()+60*60*24*30);
	        # Переадресовываем браузер на страницу проверки нашего скрипта
	        header("Location: index.php");
	    }
	    else {
	    	header("Location: user/login.php");
	    }
	}
?>