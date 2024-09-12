<?php
    require_once "includes/db.php";
    global $path;
    $rows_job = R::findAll('users');

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    foreach ($_POST as $key => $value) {
        // echo 'Button "' . htmlspecialchars($key) . '" was clicked!';
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
            <img src="uploads/avatars/<?php echo $this_user -> avatar?>" alt=""><span> <?php echo $_SESSION['logged_user']->login ?></span> <button class="to-exit"><img src="images/info.png" alt=""></button></a> 
        
        </div>
            
        <div class="main-box-left-open">
            <a href="settings.php"><img src="images/settings.png" alt="">
            <span>Настройки</span></a>
            <a href="logout.php"><img src="images/exit.png" alt="">
            <span>Выйти</span></a>
        </div>        
        <a href="index.php"><img src="images/main.png" alt=""><span> Главная</span></a> <br>
        <a href="users.php"><img src="images/peoples.png" alt=""><span>Пользователи</span></a> <br>
        <a href="mychats.php"  class="active"><img src="images/chat.png" alt=""><span>Чаты</span></a>
        <br>
    </div>

    <div class="main-menu-phone">
        <button id="menu-phone">☰</button>
    </div>
    <div class="main-box">
        <?php if(isset($_SESSION['logged_user'])): ?>
         
        <?php 
            $this_person_id = $_SESSION['logged_user']['id'];
            $this_person_id_well = R::load('users', $this_person_id); // сам пользователь
            $peoples_str = $this_person_id_well -> people; 
            
            $peoples = explode('f', $peoples_str);
            unset($peoples[0]);
        ?>

        <br>
        <div class="info"><h1 class="hello">Ваши друзья:</h1></div>
        <hr class="main-hr">

        <?php endif; ?>
        <form action="mychats.php" method="post">
            <?php

            foreach ($peoples as $people => $value) {
                //$value // идедификатор каждого собеседника
                $people_same = R::load('users', $value); // сам собеседник
                echo "<div class='user";
                if($people_same->id == 4) { 
                    echo " admin-box";
                }
                echo "'>";
                echo "<img src='uploads/avatars/{$people_same->avatar}' alt='' class='ph big'>
                <span class='name-us'>$people_same->login";  
                    if($people_same->id == 4) { 
                        echo "<img src='images/star-3.gif' class='star'>";
                    }
                    echo "</span><button type='submit' name='$people_same->id'> Написать </button> 
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