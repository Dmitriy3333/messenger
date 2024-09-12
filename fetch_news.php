<!-- <?php
// require_once "includes/db.php";
// require_once 'includes/funcs.php';
// require_once 'includes/connect.php';

// if (!isset($_GET['lastMessageId'])) {
//     echo json_encode([]);
//     exit;
// }

// $lastMessageId = intval($_GET['lastMessageId']);

// // Получаем новые сообщения, которые были добавлены после последнего загруженного сообщения
// $newMessages = R::findAll('gb', 'id > ?', [$lastMessageId]);

// $response = [];
// foreach ($newMessages as $message) {
//     $user = R::findOne('users', 'login = ?', [$message['name']]);
//     $response[] = [
//         'id' => $message->id,
//         'name' => $message->name,
//         'text' => htmlspecialchars(nl2br($message->text), ENT_QUOTES, 'UTF-8'),
//         'date' => $message->date,
//         'avatar' => $user->avatar
//     ];
// }

// echo json_encode($response);
?> -->

<?php
    // require_once "includes/db.php";
    // require_once 'includes/funcs.php';

    // $messages = get_mess();

    // if(!empty($messages)) {
    //     foreach($messages as $message) {
    //         $user = R::findOne('users', 'login = ?', [$message['name']]);
    
    //         // Начинаем вывод сообщения
    //         echo '
    //         <div class="message">
    //             <div class="mess-inf">
    //                 <img src="uploads/avatars/' . $user->avatar . '" alt="" class="ph big">
    //                 <div class="mess-inf-text">
    //                     <span class="' . (($user->id == 4) ? "admin" : "") . '">' . $message['name'];
    
    //         // Добавляем звездочку для пользователя с id = 4
    //         if($user->id == 4) {
    //             echo '<img src="images/star-3.gif" class="star">';
    //         }
    
    //         echo '</span>
    //                     <span>' . $message['date'] . '</span>
    //                 </div>
    //             </div>';
    //         // Выводим кнопку удаления для администратора
    //         if($_SESSION['logged_user']->id == 4) {
    //             echo '
    //             <form method="get" action="">
    //                 <button class="admin-dell" name="delete" value="' . $message['id'] . '">X</button>
    //             </form>';
    //         }
    //             echo '<div class="text-mess">' . htmlspecialchars(nl2br($message['text']), ENT_QUOTES, 'UTF-8') . '</div>
    //             <img src="uploads/wall/' . $message['photo'] . '" alt="" class="got-photo big">
    //         ';
    
            
    
    //         // Заканчиваем вывод сообщения
    //         echo '</div>';
    //     }
    // }
    
    <?php 
require_once "includes/db.php";
require_once 'includes/funcs.php';

$lastMessageId = isset($_POST['lastMessageId']) ? (int)$_POST['lastMessageId'] : 0;

// Загружаем только новые сообщения
$messages = R::findAll('gb', 'id > ? ORDER BY id ASC', [$lastMessageId]);

if(!empty($messages)) {
    foreach($messages as $message) {
        $user = R::findOne('users', 'login = ?', [$message['name']]);
        echo '
        <div class="message" data-id="' . $message['id'] . '">
            <div class="mess-inf">
                <img src="uploads/avatars/' . $user->avatar . '" alt="" class="ph big">
                <div class="mess-inf-text">
                    <span class="' . (($user->id == 4) ? "admin" : "") . '">' . $message['name'] . '</span>
                    <span>' . $message['date'] . '</span>
                </div>
            </div>
            <div class="text-mess">' . htmlspecialchars(nl2br($message['text']), ENT_QUOTES, 'UTF-8') . '</div>
            <img src="uploads/wall/' . $message['photo'] . '" alt="" class="got-photo big">';
        
        if ($_SESSION['logged_user']->id == 4) {
            echo '<button class="admin-dell" name="delete" value="' . $message['id'] . '">X</button>';
        }

        echo '</div>';
    }
}
    ?>
