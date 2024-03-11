<?php
  require_once 'functions.php';

  if (isset($_POST['text']))
  {
    $text = strtolower($_POST['text']);
    echo "$text";
  }
?>
