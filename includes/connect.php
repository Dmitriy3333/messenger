<?php

// $servername = "localhost";
// $username = "root";
// $password = "root"; // Пароль, если используется MAMP PRO. Если обычный MAMP, оставьте пустым.
// $dbname = "gb";

// $db =  @mysqli_connect($servername, $username, $password, $dbname) or die('нет соединения');

// mysqli_set_charset($db, "utf8mb4") or die("ошибка кодировки");
// require 'libs/rb.php'; // Подключаем RedBeanPHP

// Устанавливаем соединение с базой данных
if (!R::testConnection()) {
    R::setup('mysql:host=localhost;dbname=gb', 'root', 'root');
}
// Устанавливаем кодировку
R::exec('SET NAMES utf8mb4');

// Проверяем соединение
if (!R::testConnection()) {
    die('нет соединения');
}

