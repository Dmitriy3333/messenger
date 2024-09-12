<?php 

require_once "includes/db.php";
require_once 'includes/funcs.php';
require_once 'includes/connect.php';

if (isset($_SESSION['logged_user'])) {
    $this_user = R::findOne('users', 'id = ?', array($_SESSION['logged_user']->id));
}
function loadAvatar_set($avatar){
    $type = $avatar['type'];
    $name = md5(microtime()) . '.' . substr($type, strlen("image/"));
    $dir = "uploads/avatars/";

    $uploadfile = $dir . $name;
    
    if (move_uploaded_file($avatar['tmp_name'], $uploadfile)) {
        $user = $_SESSION['logged_user'];
        $user->avatar = $name;
        R::store($user);  // Сохраняем изменения в базе данных
        return true;
    } else {
        return false;
    }
}


// Обработка формы обновления аватара
if (isset($_POST['set_avatar'])) {
    $avatar = $_FILES['avatar'];
    if (avatarSecurity($avatar)) {
        loadAvatar_set($avatar);
        header("Location: settings.php");
        exit;
    } else {
        echo "Вы можете использовать типы png, jpg, jpeg, gif и webp";
    }
}

// Обработка формы изменения пароля
if (isset($_POST['remake_password'])) {
    $user = $_SESSION['logged_user'];
    $new_password = $_POST['new_password'];
    $old_password = $user->password;
    
    if (!empty($new_password)) {
        if(password_verify($_POST['old_password'], $user -> password)) {
            $user->password = password_hash($new_password, PASSWORD_DEFAULT);
            R::store($user);  // Сохраняем изменения
            $_SESSION['message'] = "Пароль изменен!";
            header("Location: settings.php");
            // echo "Пароль изменен!";
            exit;
        } else {
            // echo "Старый пароль неверный!";
            $_SESSION['error'] = "Старый пароль неверен!";
            header("Location: settings.php");
            exit;
        }
    } else {
        $_SESSION['error'] = "Пароль не может быть пустым";
        // echo "Пароль не может быть пустым";
        header("Location: settings.php");
        exit;
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
    <link rel="stylesheet" href="style/main.css"> 
    <link rel="stylesheet" href="style/settings.css">
    <link rel="stylesheet" href="style/news.css"> 


    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Comfortaa:wght@300..700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,350;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Display:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
</head>

    <body style="background-color: rgba(0, 89, 255, 0.102);">
        <div class="main-box-left">
            <div class="my_acc">
            <?php $this_user = R::findOne('users', 'id = ?', array($_SESSION['logged_user']->id));?>
                <img src="uploads/avatars/<?php echo $this_user -> avatar?>" alt="">
                <span> <?php echo $_SESSION['logged_user']->login ?></span> 
                <button class="to-exit"><img src="images/info.png" alt=""></button></a> 

            </div>
       
               
            <div class="main-box-left-open">
                <a href="settings.php"><img src="images/settings.png" alt="">
                <span>Настройки</span></a>
                <a href="logout.php"><img src="images/exit.png" alt="">
                <span>Выйти</span></a>
            </div>        
            <a href="index.php"><img src="images/main.png" alt=""><span> Главная</span></a> <br>
            <a href="users.php"><img src="images/peoples.png" alt=""><span>Пользователи</span></a> <br>
            <a href="mychats.php"><img src="images/chat.png" alt=""><span>Чаты</span></a>
            <br>
            <br><br>
        </div>
        <div class="main-box-right">
            <br>
        </div>
    

        </div>
        <div class="main-box" style="height: 95vh;">
            <br>
            <h1 class="hello">Настройте свой профиль</h1>
            <h1 class="main-hr"></h1>
            <br>

            <div class="remake-avatar">
    
                <?php $this_user = R::findOne('users', 'id = ?', array($_SESSION['logged_user']->id));?>

                <img src="uploads/avatars/<?php echo $this_user -> avatar?>" alt="" class="settigs-photo">

                <form action="settings.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="form_type" value="avatar">
                    <input type="file" name="avatar">
                    <br>
                    
                    <button type="submit" name="set_avatar" style="margin-top: 10px">Обновить фото</button>
                </form>
            </div>
            <br><br><br><br><br>
            <h1 class="hello">Изменить пароль:</h1>
<h1 class="main-hr"></h1>
<div class="remake-password">
<form action="settings.php" method="POST">
    <p>
        <span>Ваш старый пароль:</span>
        <input type="password" name="old_password">
    </p>
    <p>
        <span>Новый пароль:</span>
        <input type="password" name="new_password">
    </p>
    <button type="submit" name="remake_password" class="get-reg">Изменить пароль</button>
</form>


        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert success">
                <?php echo $_SESSION['message']; ?>
                <?php unset($_SESSION['message']); ?>
            </div>
        <?php elseif (isset($_SESSION['error'])): ?>
            <div class="alert danger">
                <?php echo $_SESSION['error']; ?>
                <?php unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>        
        </div>
        </div>
    </div>
</body>

<script>
    var buttonExit = document.querySelector(".to-exit");
    var exitWindow = document.querySelector(".main-box-left-open");

    buttonExit.onclick = function() {
        if (exitWindow.style.display == "block") {
            exitWindow.style.display = "none";
        } else {
            exitWindow.style.display = "block";
        }
    }

 </script>
</html>