<?php
    $dbhost = 'localhost';
    $dbname = 'course_work_2023_2024_db';
    $dbuser = 'root';
    $dbpass = 'mysql';

    $connection = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
    if ($connection -> connect_error) die('Fatal Error');

    function createTable($name, $query)
    {
        queryMysql("CREATE TABLE IF NOT EXISTS $name($query)");
        echo "Table '$name' created or already exists.<br>";
    }

    function queryMysql($query)
    {
        global $connection;
        $result = $connection->query($query);
        if (!$result) die("Fatal Error");
        if (strpos($query, 'INSERT') === 0){
            $last_id = $connection->insert_id;
            return $last_id;
        }
        return $result;
    }

    function destroySession()
    {
        $_SESSION=array();

        if (session_id() != "" || isset($_COOKIE[session_name()]))
        setcookie(session_name(), '', time()-2592000, '/');

        session_destroy();
    }

    function sanitizeString($var) //295
    {
        global $connection;
        $var = strip_tags($var);
        $var = htmlentities($var);
        if (get_magic_quotes_gpc())
        $var = stripslashes($var);
        return $connection->real_escape_string($var);
    }

    function redirectToTime($page, $time){
        
        echo <<<_REDIRECT
                    <script>
                        setTimeout(function(){location.href="$page"} , $time);
                    </script>
                _REDIRECT;
    }

    $GLOBALS['types'] = [
                            "text_data" => "Однострочная запись",
                            "textarea_data" => "Многострочная запись",
                            "int_data" => "Целое число",
                            "float_data" => "Десятичное число",
                            "date_data" => "Дата",
                            "file_data" => "Файл",
                            "bool_data" => "Флаг (Да / Нет)",
                            "table_data" => "Таблица",
                        ];
?>