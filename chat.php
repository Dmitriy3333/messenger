<?php

    require_once "includes/db.php";
    require_once "libs/rb.php";

    $path = $_SESSION['path']; // связь пользователей для названия бд
    $sobes = $_SESSION['sobes']; // собеседник 

    $is_this_person = 0;

    $person = R::load('users', $sobes); // собеседник

    $this_person_id = $_SESSION['logged_user']['id'];
    $this_person_id_well = R::load('users', $this_person_id); // сам пользователь 
    
    $person_id = $person -> id; // id собеседника
    $person_id_well = R::load('users', $person_id);
    // echo $this_person_id;
    // echo $person -> id;
    

    try {
        R::count($path);
        $is_this_person = 1;
    } catch (Exception $e) {
        // Создание таблицы, если она не существует
        
        $label = R::dispense($path);
        R::store($label);
    }


    function loadAvatar($avatar, $message1) {
        $type = $avatar['type'];
        $nameph = md5(microtime()) . '.' . substr($type, strlen("image/"));
        $dir = "uploads/avatars/";
        $uploadfile = $dir . $nameph;
    
        if (move_uploaded_file($avatar['tmp_name'], $uploadfile)) {
            $message1->photo = $nameph;  // Сохраняем путь к фото в объекте сообщения
            return true;
        } else {
            return false;
        }
    }

    if(!empty($_POST)) {
        $name = htmlspecialchars($_SESSION['logged_user']->login);
        $text = htmlspecialchars($_POST['text']);
        $message1 = R::dispense($path);
        $message1->name = $name;
        $message1->text = $text;

        $avatar = $_FILES['avatar'];
        if ($avatar && avatarSecurity($avatar)) {
            // if (loadAvatar($avatar, $message1)) {
                loadAvatar($avatar, $message1);
            // } else {
            //     echo "Ошибка загрузки аватара.";
            // }
        } else {
            echo "Вы можете использовать типы png, jpg, jpeg, gif и webp";
        }
            $date = date('Y-m-d H:i');
            $message1->date = $date;

            // это должно выполняться только 1 раз - при первой переписке
            $bilo = $this_person_id_well -> people; // для текущего пользователя
            $bilo_sobes = $person_id_well -> people; // для собеседника пользователя
            if (!str_contains($bilo, "f" . $sobes)) {
                $this_person_id_well -> people = $bilo. "f". (string)$sobes;
                R::store($this_person_id_well);
            }
            if (!str_contains($bilo_sobes, "f" . $this_person_id)) {
                $person_id_well -> people = $bilo_sobes. "f". (string)$this_person_id;
                R::store($person_id_well);
            }

            R::store($message1);
            header("Location: {$_SERVER['PHP_SELF']}");
            exit;
        }

        $messages2 = R::findAll($path, 'ORDER BY id ASC');

?>



            


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style/chat.css">
    <link rel="stylesheet" href="style/main.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Comfortaa:wght@300..700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,350;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">

</head>

<body style="background-color: rgba(0, 89, 255, 0.102);">
    <div class="main-box-left">
        <div class="my_acc">
            <?php $this_user = R::findOne('users', 'id = ?', array($_SESSION['logged_user']->id));?>
                <img src="uploads/avatars/<?php echo $this_user -> avatar?>" alt=""><span> <?php echo $_SESSION['logged_user']->login ?></span> 
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
        <a href="mychats.php"  class="active"><img src="images/chat.png" alt=""><span>Чаты</span></a>
        <br>
    </div>

    <div class="main-menu-phone">
        <button id="menu-phone">☰</button>
    </div>

    <div class="main-box">
        <hr>
        <br>
        <div class="info"><img src="uploads/avatars/<?php echo$person->avatar ?>" alt="" class="sobes big"><span> <?php echo $person -> login;?></span></div>
        <div class="all-messages" id="textContainer">
            
            <?php if(!empty($messages2)): ?>

            <?php foreach($messages2 as $message1): ?>
                <?php if ($message1['name'] == $this_person_id_well -> login) {
                    echo "<div class='message i-am'>";
                    echo '<span> Вы </span>';
                    } else {
                        echo "<div class='message'>";
                        echo '<span>' . $message1['name'] . '</span>';
                };?>
                    
                    <div class="text-mess"><?php echo nl2br($message1['text'])?></div>
                    <img src="uploads/avatars/<?php echo $message1['photo']?>" alt="" class="got-photo big">
                    <span class="date-mess"><?php echo $message1['date']?></span>
                            
                </div>

                <?php endforeach; ?> 
            <?php endif; ?>

        </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var lastMessageId = <?php echo end($messages2)->id; ?>; // id последнего загруженного сообщения

            function checkForNewMessages() {
                var xhr = new XMLHttpRequest();
                xhr.open('GET', 'getmessages.php?lastMessageId=' + lastMessageId, true);
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        var newMessages = JSON.parse(xhr.responseText);
                        
                        newMessages.forEach(function(message) {
                            var messageElement = document.createElement('div');
                            messageElement.className = 'message';
                            
                            // Если сообщение отправлено текущим пользователем, добавляем класс 'i-am'
                            if (message.name === "<?php echo $this_person_id_well->login; ?>") {
                                messageElement.classList.add('i-am');
                                messageElement.innerHTML = '<span> Вы </span>' + 
                                                        '<div class="text-mess">' + message.text + '</div>' +
                                                        '<img src="uploads/avatars/' + message.photo + '" alt="" class="got-photo big">' +
                                                        '<span class="date-mess">' + message.date + '</span>';
                            } else {
                                messageElement.innerHTML = '<span>' + message.name + '</span>' + 
                                                        '<div class="text-mess">' + message.text + '</div>' +
                                                        '<img src="uploads/avatars/' + message.photo + '" alt="" class="got-photo big">' +
                                                        '<span class="date-mess">' + message.date + '</span>';
                            }
                        
                            document.getElementById('textContainer').appendChild(messageElement);
                            
                            lastMessageId = message.id;

                            // Прокрутка вниз для отображения новых сообщений -- когда пришло новое
                            var textContainer = document.getElementById('textContainer');
                            textContainer.scrollTop = textContainer.scrollHeight;
                        });

                    
                    }
                };
                console.log("Последний id сообщения:", lastMessageId);
                xhr.send();
            }
            // Опрос сервера каждые 2 секунды
            setInterval(checkForNewMessages, 2000);
        });
    </script>

        <div class="form-main">
            <form method="post" action="chat.php" class="get-text"  enctype="multipart/form-data" id="messageForm">
                <p>
                    <label for="name"></label> 
                    <!-- <text type="text" name="name"> -->
                    <textarea name="text" id="text" class="text-area-chat"></textarea>
                    <label class="get-photo-label">
                        <input type="file" name="avatar" class="get-photo">
                    </label>
                    <button type="submit" >></button>
                </p>     
                <br>
            </form>
        </div>
    </div>
    <div class="zaslonka"></div>
    <img src="images/men.png" alt="" class="big-photo">
</body>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        var textContainer = document.getElementById("textContainer");
        textContainer.scrollTop = textContainer.scrollHeight;
    });
</script>
<script type="text/javascript" src="js/chatsize.js"></script>

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
<script src="js/bigphotos.js"></script>
<script type="text/javascript" src="js/main.js"></script>
</html>