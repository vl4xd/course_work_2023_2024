<?php
    require_once 'head.php';

    if (!$loggedin) die("</div></body></html>");

    if (isset($_GET['view'])){
        $view = sanitizeString($_GET['view']);

        

    }
?>