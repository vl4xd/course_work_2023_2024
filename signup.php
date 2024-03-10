<?php
    require_once 'header.php';

echo <<<_END
                <script>
                    function checkUser(user){
                        if (user.value == ''){
                            $('#used').html('&nbsp;')
                            return
                        }

                        $.post
                        (
                            'checkuser.php',
                            {user: user.value},
                            function(data){
                                $('#used').html(data)
                            }
                        )

                        $.post
                        (
                            'lowercaseuser.php',
                            {user: user.value},
                            function(data){
                                $('#user').val(data)
                            }
                        )
                    }
                </script>
_END;

    $error = $user = $pass = "";

    if (isset($_SESSION['user'])) destroySession();

    if (isset($_POST['user'])){
        $user = sanitizeString($_POST['user']);
        $pass = sanitizeString($_POST['pass']);

        if ($user == "" || $pass == "") $error = 'Не все поля заполнены<br><br>';
        else{
            $result = queryMysql("SELECT * FROM users WHERE name='$user'");

            if ($result->num_rows) $error = 'Данное имя уже занято<br><br>';
            else {
                $pass_hash = password_hash($pass, PASSWORD_DEFAULT);
                queryMysql("INSERT INTO users (name, pass) VALUES('$user', '$pass_hash')");
                $_SESSION['user'] = $user;
                echo <<<_REDIRECT
                    <script>
                        setTimeout(function(){location.href="./index.php"} , 0);
                    </script>
                _REDIRECT;
            }
        }
    }

echo <<<_END
                <form method='post' action='signup.php'>$error
                    <div data-role='fieldcontain'>
                        <label></label>
                        Пожайлуйста введите данные: Имя пользователя, Пароль
                    </div>
                    <div data-role='fieldcontain'>
                        <label>Имя пользователя</label>
                        <input id='user' type='text' maxlength='16' name='user' value='$user'
                            onBlur='checkUser(this)'>
                        <label></label><div id='used'>&nbsp;</div>
                    </div>
                    <div data-role='fieldcontain'>
                        <label>Пароль</label>
                        <input type='password' maxlength='16' name='pass' value='$pass'>
                    </div>
                    <div data-role='fieldcontain'>
                        <label></label>
                        <input data-transition='slide' type='submit' value='Зарегистрироваться'>
                    </div>
                </form>
            </div>
        </div>
    </body>
</html>
_END;
?>