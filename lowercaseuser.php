<?php
  require_once 'functions.php';

  if (isset($_POST['user']))
  {
    $user = strtolower($_POST['user']);
    echo "$user";
  }
?>
