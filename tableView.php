<?php
    require_once 'header.php';

    if (!$loggedin || !isset($_GET['table_id'])){
        redirectToTime('./index.php', 0);
        exit;
    }

    
    // Проверка возможности входа в раздел пользователю

    $error = "";
    $table_id = sanitizeString($_GET['table_id']);

echo<<<_END
<div>
    <a data-role='button' data-inline='true' data-icon='plus'
        data-transition='slide' href="./createNote.php?table_id=$table_id">Создать запись</a>
</div>


_END;
    require_once 'footer.php';
?>