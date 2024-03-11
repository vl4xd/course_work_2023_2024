<?php
    require_once 'header.php';

    if (!$loggedin) {
        require_once 'info.php';
    }
    else{
        redirectToTime('./teams.php', 0);
    }

    require_once 'footer.php';
?>