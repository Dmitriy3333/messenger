<?php
require_once "libs/rb.php";
R::setup( 'mysql:host=localhost;dbname=users',
        'root', 'root');

session_start();

function avatarSecurity($avatar) {
        $name = $avatar['name'];  
        $type = $avatar['type'];  
        $size = $avatar['size'];  
    
        $blacklist = array(".php", ".js", ".html");
    
        // Проверка на запрещенные расширения
        foreach($blacklist as $row) {
            if (preg_match("/$row\$/i", $name)) return false;
        }
        
        // Проверка на допустимый MIME-тип
        if(($type != "image/png") && ($type != "image/jpeg") && ($type != "image/jpg") && ($type != "image/webp") && ($type != "image/gif")) return false;
        
        // Проверка на размер 
        if($size > 7 * 1024 * 1024) return false;
    
        return true;
    }