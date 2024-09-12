<?php
    require_once "includes/db.php";

    $data = $_POST;
    if (isset($data['do_signup'])) {
        // регистрация
        $errors = array();
        if (trim($data['login']) == '') {
            $errors[] = '<span class="error">Введите логин</span>';
        }

        // Проверка на использование только букв в логине (любой алфавит)
        if (!preg_match('/^\p{L}+$/u', trim($data['login']))) {
            $errors[] = '<span class="error">Имя может содержать только буквы</span>';
        }
        
        if (mb_strlen(trim($data['login'])) > 9) {
            $errors[] = '<span class="error"> Имя не более 9 символов</span>';
        }
        if (trim($data['email']) == '') {
            $errors[] = '<span class="error">Введите email</span>';
        }

        if (trim($data['password']) == '') {
            $errors[] = '<span class="error">Введите пароль</span>';
        }

        if (($data['password_2']) != $data['password']) {
            $errors[] = '<span class="error">Повторный пароль неверен</span>';
        }

        if (R::count('users', "login = ?", array($data['login'])) > 0) {
            $errors[] = '<span class="error">Логин занят</span>';
        }

        if (empty($errors)) {
            // регестрируем
            $avatar = $_FILES['avatar'];
            $user = R::dispense('users'); 
            $user -> login = $data['login'];
            $user -> email = $data['email'];
            $user -> password = password_hash($data['password'], PASSWORD_DEFAULT);
            $user -> people = "";

            function loadAvatar($avatar, $user){
                $type = $avatar['type'];
                $name = md5(microtime()) . '.' . substr($type, strlen("image/"));
                $dir = "uploads/avatars/";
        
                $uploadfile = $dir . $name;
                
                if(move_uploaded_file($avatar['tmp_name'], $uploadfile)) {
                    $user -> avatar = $name;
                } else {
                    return false;
                }
                return true;
            }
            

            if ($avatar && avatarSecurity($avatar)) {
                loadAvatar($avatar, $user);
            } else {
                $user->avatar = 'no.png';
            }

            R::store($user);
            
            echo '<div style="color: green">Успешно!<a href="login.php">Авторизоваться</a></div><hr>';

        } else {
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
    <!-- <link rel="stylesheet" href="style/style.css"> -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Comfortaa:wght@300..700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style/log.css">
</head>
<body>
    <div class="box">
        <h1>Регистрация</h1>
        <hr>
        <form action="signup.php" method="POST" enctype="multipart/form-data">
            <div class="form-main-log" style="margin-top: -90px; width: 100%; margin-left: 0%;">
                <div class="form-main-log-left">
                    <p style="margin-top:130px">
                        <span>Ваш логин:</span>
                        <input type="text" name="login" value="<?php echo @$data['login'];?>">
                    </p>
                    <p>
                        <span>Ваш email:</span>
                        <input type="email" name="email" value="<?php echo @$data['email'];?>">
                    </p>
                    <p>
                        <span>Ваш пароль:</span>
                        <input type="password" name="password" value="<?php echo @$data['password'];?>">
                    </p>
                    <p>
                        <span>Повтор пароля:</span>
                        <input type="password" name="password_2" value="<?php echo @$data['password_2'];?>">
                    </p>
                </div>
                <div class="form-main-log-right">
                    <img src="uploads/avatars/no.png" alt="" class="settigs-photo" id="avatarPrev"> 
                    <input type="file" name="avatar" id="avatarInput">
                </div>
            </div>
            <button type="submit" name="do_signup" class="get-reg">Зарегистрироваться</button>
        </form>
    <script>
        var avatarPrev = document.getElementById('avatarPrev');
        var avatarInput = document.getElementById('avatarInput');

        avatarInput.onchange = function(event) {
            var file = event.target.files[0];
            var reader = new FileReader();

                reader.onload = function(e) {
                avatarPrev.src = e.target.result;
                avatarPrev.style.display = 'block'; // Показываем изображение
            };

            if (file) {
                reader.readAsDataURL(file);
            } else {
                avatarPrev.src = 'uploads/avatars/no.png';
                avatarPrev.style.display = 'none'; // Скрываем изображение, если файл не выбран
            }
        }
    </script>
    
     <a href="index.php" class="back">< Назад</a>
    </div>
</body>
</html>