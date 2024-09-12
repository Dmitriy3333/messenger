
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style/chat.css">
    <!-- <meta http-equiv="refresh" content="5"> -->
    <link rel="stylesheet" href="style/main.css">

</head>

<?php
require_once "includes/db.php";
require_once "libs/rb.php";

// session_start();
$this_person_id = $_SESSION['logged_user']['id'];
$this_person_id_well = R::load('users', $this_person_id); // сам пользователь 

$path = $_SESSION['path'];
$messages2 = R::findAll($path, 'ORDER BY id ASC');

foreach($messages2 as $message1): ?>
        
    <?php if ($message1['name'] == $this_person_id_well -> login) {
        echo "<div class='message i-am'>";
        echo '<span> Вы </span>';
        } else {
            echo "<div class='message'>";
            echo '<span>' . $message1['name'] . '</span>';
            };?>
            
    <!-- <span><?php //echo $message1['name']?></span> <span> </span> -->
     
        <div class="text-mess"><?php echo nl2br($message1['text'])?></div>
        <img src="uploads/avatars/<?php echo $message1['photo']?>" alt="" class="got-photo">

        <span class="date-mess"><?php echo $message1['date']?></span>
        
    </div>
<?php endforeach; ?>


