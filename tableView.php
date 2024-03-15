<?php
    require_once 'header.php';

    if (!$loggedin || !isset($_GET['table_id'])){
        redirectToTime('./index.php', 0);
        exit;
    }

    // Проверка возможности входа в раздел пользователю

    

    require_once 'footer.php';
?>