<?php
    require_once 'header.php';

    if (isset($_SESSION['user']))
    {
    destroySession();
    }
    redirectToTime("./index.php", 0);
?>
    </div>
  </body>
</html>
