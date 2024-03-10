<?php
    require_once 'header.php';

    if (isset($_SESSION['user']))
    {
    destroySession();
    }
    echo <<<_REDIRECT
        <script>
            setTimeout(function(){location.href="./index.php"} , 0);
        </script>
    _REDIRECT;
?>
    </div>
  </body>
</html>
