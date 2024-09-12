<?php
    require_once "includes/db.php";
    require_once 'includes/funcs.php';
    require_once 'includes/connect.php';
    
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);


    $data = $_POST;
    if (isset($_SESSION['logged_user'])) {
        $this_user = R::findOne('users', 'id = ?', array($_SESSION['logged_user']->id));
    }



    if(!empty($_POST)) {
        $form_type = $_POST['form_type'];

        if ($form_type === 'message') {
            save_mess();
            header("Location: {$_SERVER['PHP_SELF']}");
            exit;
        }
    }
// echo "123";
if (isset($_GET['delete'])) {
    $messageId = (int)$_GET['delete'];  // Преобразуем в целое число для безопасности
    // echo "Удаление сообщения с ID: " . $messageId;  // Отладочное сообщение

    $message = R::load('gb', $messageId);

    if ($message->id) {  // Проверяем, существует ли сообщение
        R::trash($message);
        // echo "Сообщение с ID $messageId было удалено.";  // Сообщение о успешном удалении
    } else {
        // echo "Сообщение не найдено.";  // Если сообщение не найдено
    }
}

/////////////////////////////////// начало лайков
//     foreach ($_GET as $key => $value) {
//         $key_new = explode('f', $key); // это массив 1й элемент - id пользователя, кому поставили лайк, воторой - id сообщения на который поставили лайк
//         echo $_SESSION['logged_user']->login . " поставил лайк " . R::findOne('users', 'id = ?', [$key_new[0]]) -> login;
        
//     $id = $key_new[1]; // ID записи, которую нужно обновить
// $new_likes = $_SESSION['logged_user']->id; // Новое значение для поля likes

// // Подготовка запроса на обновление
// $query = "UPDATE gb SET likes = ? WHERE id = ?";
// $stmt = mysqli_prepare($db, $query);

// // Проверка на ошибки при подготовке запроса
// if (!$stmt) {
//     die('Ошибка подготовки запроса: ' . mysqli_error($db));
// }

// // Привязка параметров и выполнение запроса
// mysqli_stmt_bind_param($stmt, 'ii', $new_likes, $id);
// mysqli_stmt_execute($stmt);
//         exit;
//     }
/////////////////////////////////// конец лайков

    $messages = get_mess();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style/main.css"> 
    <link rel="stylesheet" href="style/log.css">
    <link rel="stylesheet" href="style/news.css"> 

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Comfortaa:wght@300..700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,350;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Display:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
</head>
    
<?php if(isset($_SESSION['logged_user'])): ?>
    <body style="background-color: rgba(0, 89, 255, 0.102);">
        <div class="main-box-left">
            <div class="my_acc">
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

            <a href="index.php" class="active"><img src="images/main.png" alt=""><span> Главная</span></a> <br>
            <a href="users.php"><img src="images/peoples.png" alt=""><span>Пользователи</span></a> <br>
            <a href="mychats.php"><img src="images/chat.png" alt=""><span>Чаты</span></a>
            <br>
            <br><br>
        </div>
        <div class="main-menu-phone">
            <button id="menu-phone">☰</button>
        </div>

        <div class="main-box">
            <div class="form-main">
                <form method="post" action="index.php" class="get-text" id="messageForm" enctype="multipart/form-data">
                <input type="hidden" name="form_type" value="message">
                <span>Предложите свою новость</span>
                        <!-- <label for="name"></label>  -->
                        <div class="main-form-part">
                            <textarea name="text" id="text" class="text-area-news"></textarea>
                            <label class="get-photo-label">
                                <input type="file" name="avatar" class="get-photo">
                                
                            </label>
                            <button type="submit" >></button>
                            
                        </div>
                </form>
            
            </div>
        
            <hr>
            <br>
    
            <div class="all-messages" id="textContainer">

                <?php if(!empty($messages)): ?>
                    <?php foreach($messages as $message): ?>
                        
                        <div class="message">
                       
                            <?php 
                                $user = R::findOne('users', 'login = ?', [$message['name']]);
                            ?>
                            <div class="mess-inf">
                                <img src="uploads/avatars/<?php echo $user -> avatar?>" alt="" class="ph big">
                                <div class="mess-inf-text">
                                
                                    <span class="<?php if(($user->id) == 4): echo "admin"; endif;?>"><?php echo $message['name']?><?php if(($user->id) == 4): echo "<img src='images/star-3.gif' class='star'>"; endif;?></span> 
                                    <span><?php echo $message['date']?> </span>
                                </div>
                            </div>
                            
                            <?php if(($_SESSION['logged_user']->id) == 4): ?>
                                <form method="get" action="">
                                    <button class="admin-dell" name="delete" value="<?php echo $message['id']; ?>">X</button>
                                <form>
                            <?php endif; ?>
                            <div class="text-mess"><?php echo htmlspecialchars(nl2br($message['text']), ENT_QUOTES, 'UTF-8'); ?></div>
                            <img src="uploads/wall/<?php echo $message['photo']?>" alt="" class="got-photo big">
                            
                            <!-- <hr>  -->
                            <!-- система лайков -->
                            <!-- <form method="get" action="">
                                <button class="like" name="<?php echo $user->id ."f". $message['id'] ?>">лайк</button> <span class="like">0</span> <br>
                                <button class="dislike" name="<?php echo  $user->id ."f". $message['id'] ?>">дизлайк</button> <span class="dislike">0</span>
                            </form> -->
                                
                            </div>
                    
                    <?php endforeach; ?>
                <?php endif; ?>
                <script>
    // function loadMessages() {
    //     var xhr = new XMLHttpRequest();
    //     xhr.open('GET', 'fetch_news.php', true);
    //     xhr.onload = function() {
    //         if (xhr.status === 200) {
    //             document.getElementById('textContainer').innerHTML = xhr.responseText;
    //         }
    //     };
    //     xhr.send();
    // }

    // setInterval(loadMessages, 5000);  // Загружать новые сообщения каждые 5 секунд
    // loadMessages();  // Загрузить сообщения при загрузке страницы
    let lastMessageId = null;

function loadNewMessages() {
    $.ajax({
        url: 'fetch_news.php',
        method: 'POST',
        data: { lastMessageId: lastMessageId },
        success: function(data) {
            if (data) {
                $('#textContainer').append(data);  // Добавляем новые сообщения в контейнер
                lastMessageId = $('.message').last().data('id');  // Обновляем последний ID
            }
        }
    });
}

// Загружаем новые сообщения каждые 5 секунд
setInterval(loadNewMessages, 2000);

$(document).ready(function() {
    // Сохраняем ID последнего сообщения при загрузке страницы
    lastMessageId = $('.message').last().data('id');
});
</script>
            </div>
        </div>


        <div class="zaslonka"></div>
        <img src="images/men.png" alt="" class="big-photo">
    </body>
    
<script type="text/javascript" src="js/main.js"></script>
    <?php else: ?>
        <body style="background-color:#fff;">
        <div class="box">
        <h1>Вход в систему</h1>
        <hr>
        <div class="form-main-log-in">
           
        <a href="login.php">Авторизация</a><br>
        <a href="signup.php">Регистрация</a>
        </div>
    </div>

    
    </body>
    
<?php endif; ?>


<script src="js/bigphotos.js"></script>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
    var textarea = document.getElementById('text');
    var form = document.getElementById('messageForm');
    var inputFile = document.querySelector('.get-photo');

    function isTextareaEmpty(text) {
        return text.trim().length === 0;
    }

    form.addEventListener('submit', function(event) {
        var text = textarea.value;

        // Проверяем, пустой ли текст
        if (isTextareaEmpty(text) && inputFile.files.length <= 0) {
            event.preventDefault();  // Предотвращаем отправку формы
            alert('Текстовое поле не может быть пустым или содержать только пробелы.');
        }
    });



    textarea.addEventListener('keydown', function(event) {
        if (event.key === 'Enter' && !event.shiftKey) {  // Если нажата клавиша Enter и не нажата Shift
            event.preventDefault();  // Предотвращаем перенос строки
            var text = textarea.value;

            // Проверяем, пустой ли текст
            if (isTextareaEmpty(text) && inputFile.files.length <= 0) {
                alert('Текстовое поле не может быть пустым или содержать только пробелы.');
            } else {
                form.submit();  // Отправляем форму
            }
        }
    });
});

</script>
</html>