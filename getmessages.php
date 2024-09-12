<?php 
require_once "includes/db.php";
require_once "libs/rb.php";

// session_start();,

$path = $_SESSION['path'];
$lastMessageId = $_GET['lastMessageId'];

// Получаем все сообщения, id которых больше, чем $lastMessageId
$newMessages = R::findAll($path, 'id > ? ORDER BY id ASC', [$lastMessageId]);

// Подготавливаем данные для отправки в формате JSON
$response = [];
foreach ($newMessages as $message) {
    $response[] = [
        'id' => $message->id,
        'name' => htmlspecialchars($message->name),
        'text' => nl2br(htmlspecialchars($message->text)),
        'photo' => $message->photo,
        'date' => $message->date
    ];
}

header('Content-Type: application/json');
echo json_encode($response);
?>
