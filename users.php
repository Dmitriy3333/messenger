<?php
    require_once "includes/db.php";
    global $path;
    $rows_job = R::findAll('users');

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    foreach ($_POST as $key => $value) {
        echo 'Button "' . htmlspecialchars($key) . '" was clicked!';
        $userId = (string)$_SESSION['logged_user']->id;
        $enother = htmlspecialchars($key);

        $arr_users = [$userId, $enother];

        $path = (string)(max($arr_users) . 'a' . min($arr_users));
        $_SESSION['path'] = $path;
        $_SESSION['sobes'] = $enother;

        header('Location: chat.php');
        
        break; 
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style/users.css">
    <link rel="stylesheet" href="style/main.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Comfortaa:wght@300..700&display=swap" rel="stylesheet">
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
        <a href="index.php"><img src="images/main.png" alt=""><span> Главная</span></a> 
        <br>
        <a href="users.php"  class="active"><img src="images/peoples.png" alt=""><span>Пользователи</span></a> 
        <br>
        <a href="mychats.php"><img src="images/chat.png" alt=""><span>Чаты</span></a>
        <br>
        
    </div>
    <div class="main-menu-phone">
        <button id="menu-phone">☰</button>
    </div>
    <div class="main-box">
        <br>
        <div class="info">
            <h1 class="hello"> Найдите собеседника: </h1></div>
            <hr class="main-hr">
        <form action="users.php" method="post">
            <?php
                foreach ($rows_job as $row){
                    $this_user = R::findOne('users', 'id = ?', [$row->id]);
                    $avatar = $this_user->avatar;
                    // echo $avatar;
                    echo "<div class='user";
                    if($this_user->id == 4) { 
                        echo " admin-box";
                    }
                    echo "'>";
                    echo "<img src='uploads/avatars/{$avatar}' alt='' class='ph big'>
                    <span class='name-us'>$row->login";  
                    if($this_user->id == 4) { 
                        echo "<img src='images/star-3.gif' class='star'>";
                    }
                    echo  "</span><span></span>
                    <button type='submit' name='$row->id'> Написать </button> 
                    </div>
                    <br>";
                }
            ?>
        </form>
    </div>
<script type="text/javascript" src="js/main.js"></script>
<div class="zaslonka"></div>
<img src="images/men.png" alt="" class="big-photo">
<script src="js/bigphotos.js"></script>

</body>
</html>