<?php
    require_once 'header.php';

    $error = $user = $pass = "";

    if (isset($_POST['user'])){
        $user = sanitizeString($_POST['user']);
        $pass = sanitizeString($_POST['pass']);

        if ($user == "" || $pass == "") $error = 'Не все поля заполнены<br><br>';
        else{
            $result = queryMysql("SELECT name, pass FROM users WHERE name='$user'");

            if ($result->num_rows == 0){
                $error = "Неверная попытка входа";
            } 
            else{
                $row = $result->fetch_array(MYSQLI_BOTH);
                $result->close();

                if (password_verify($pass, $row['pass'])){
                    $_SESSION['user'] = $user;
                    redirectToTime('./teams.php', 0);
                }
                else{
                    $error = "Неверная попытка входа";
                }
                
            }
        }
    }

echo <<<_END
                <form method='post' action='login.php'>$error
                    <div data-role='fieldcontain'>
                        <label></label>
                        Пожайлуйста введите данные: Имя пользователя, Пароль
                    </div>
                    <div data-role='fieldcontain'>
                        <label>Имя пользователя</label>
                        <input type='text' maxlength='16' name='user' value='$user'>
                    </div>
                    <div data-role='fieldcontain'>
                        <label>Пароль</label>
                        <input type='password' maxlength='16' name='pass' value='$pass'>
                    </div>
                    <div data-role='fieldcontain'>
                        <label></label>
                        <input data-transition='slide' type='submit' value='Войти'>
                    </div>
                </form>
_END;

    require_once 'footer.php';
?>