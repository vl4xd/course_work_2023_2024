<?php
  require_once 'functions.php';

  if (isset($_POST['user']))
  {
    $user   = sanitizeString($_POST['user']);
    $result = queryMysql("SELECT * FROM users WHERE name='$user'");

    if ($result->num_rows)
      echo  "<span class='taken'>&nbsp;&#x2718; " .
            "Имя пользователя '$user' занято</span>";
    else
      echo "<span class='available'>&nbsp;&#x2714; " .
           "Имя пользователя '$user' свободно</span>";
  }
?>
