<?php
        # Обнуляем куки
        setcookie('u-id', '', time()+60*60*24*30);
        setcookie('hash', '', time()+60*60*24*30);
        header("Location: user/login.php"); exit();
?>