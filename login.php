<?php
    require_once "includes/db.php";

    $data = $_POST;

    if(isset($data['do_login'])) {
        $errors = array();
        $user = R::findOne('users', 'login = ?', array($data['login']));

        if($user) {
            // логин существует
            if(password_verify($data['password'], $user -> password)) {
                // авторизация
                $_SESSION['logged_user'] = $user;
                header('Location: index.php');
            } 
            else {
                $errors[] = '<span class="error">Пароль не верен</span>';
            }

        } else {
            $errors[] = '<span class="error">Логин не верен</span>';
        }

        if (!empty($errors)) {
            echo '<div>'.array_shift($errors).'</div>';
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Comfortaa:wght@300..700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style/log.css">

</head>
<body>
    <div class="box">
        <h1>Вход</h1>
        <hr>
        <form action="login.php" method="POST">
            <div class="form-main-log-in" style="margin-top: 50px; ">
                <p>
                    <span>Имя:   &nbsp;&nbsp;</span>
                    <input type="text" name="login" value="<?php echo @$data['login'];?>">
                </p>
                <p>
                    <span>Пароль:</span>
                    <input type="password" name="password" value="<?php echo @$data['password'];?>">
                </p>
                <button type="submit" name="do_login">Войти</button>
            </div>
        </form>
    <a href="index.php" class="back">< Назад</a>
    </div>
</body>
</html>