<?php
// function save_mess() {
//     global $db;
//     $name = mysqli_real_escape_string($db, $_SESSION['logged_user'] -> login);
//     $text = mysqli_real_escape_string($db, $_POST['text']);
//     $query = "INSERT INTO gb (name, text) VALUES ('$name', '$text')";

//     mysqli_query($db, $query);
    
// }

// function get_mess() {
//     global $db;
//     $query = "SELECT * FROM gb ORDER BY id DESC";

//     $res = mysqli_query($db, $query);
//     return mysqli_fetch_all($res, MYSQLI_ASSOC);
// }

// function print_arr($arr) {
//     echo '<pre>' . print_r($arr, true) . '</pre>';
// }
require_once "includes/db.php";

function loadAvatar($avatar, $message) {
    $type = $avatar['type'];
    $nameph = md5(microtime()) . '.' . substr($type, strlen("image/"));
    $dir = "uploads/wall/";
    $uploadfile = $dir . $nameph;

    if (move_uploaded_file($avatar['tmp_name'], $uploadfile)) {
        $message->photo = $nameph;  // Сохраняем путь к фото в объекте сообщения
        return true;
    } else {
        return false;
    }
}

function save_mess() {
    $name = $_SESSION['logged_user']->login;
    $text = $_POST['text'];
    $avatar = $_FILES['avatar'];
    
    // Создаем новую запись в таблице gb
    $message = R::dispense('gb');
    $message->name = $name;
    $message->text = $text;
    $message->date = date('Y-m-d H:i:s'); // Добавляем дату и время
    // $message->photo = 'no';

    if ($avatar && avatarSecurity($avatar)) { 
            loadAvatar($avatar, $message);
    } else {
        echo "Вы можете использовать типы png, jpg, jpeg, gif и webp";
    }

    // Сохраняем запись в базе данных
    R::store($message);
}

function get_mess() {
    // Извлекаем все записи из таблицы gb, отсортированные по ID в порядке убывания
    return R::findAll('gb', 'ORDER BY id DESC');
}

function print_arr($arr) {
    echo '<pre>' . print_r($arr, true) . '</pre>';
}