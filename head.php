<?php // Example 27-2: header.php
  session_start();

echo <<<_INIT
<!DOCTYPE html> 
<html>
    <head>
        <meta charset='utf-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1'> 
        <link rel="stylesheet" href="http://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.css">
        <link rel='stylesheet' href='styles.css' type='text/css'>
        <script src='javascript.js'></script>
        <script src="http://code.jquery.com/jquery-2.2.4.min.js"></script>
        <script src="http://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.js"></script>
_INIT;

    require_once 'functions.php';

    $userstr = "Вы вошли как: гость";

    if(isset($_SESSION['user'])){
        $user  = $_SESSION['user'];
        $loggedin   = TRUE;
        $userstr    = "Вы вошли как: $user";
    }
    else $loggedin  = False;

echo <<<_MAIN
        <title>$userstr</title>
    </head>
    <body>
        <div data-role='page'>
            <div data-role='header'>
                <div class='userstr'>$userstr</div>
            </div>
            <div data-role='content'>
_MAIN;

    if ($loggedin){
echo <<<_LOGGEDIN
                
_LOGGEDIN;
    }
    else{
echo <<<_GUEST
                <div class='center'>
                    <a data-role='button' data-inline='true' data-icon='home'
                        data-transition='slide' href='index.php'>Главная страница</a>
                    <a data-role='button' data-inline='true' data-icon='plus'
                        data-transition='slide' href='signup.php'>Зарегистрироваться</a>
                    <a data-role='button' data-inline='true' data-icon='user'
                        data-transition='slide' href='login.php'>Войти</a>
                </div>
_GUEST;
    }
?>