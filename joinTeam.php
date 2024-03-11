<?php
    require_once 'header.php';

    if (!$loggedin) redirectToTime('./index.php', 0);

echo <<<_END
    <script>
    function checkTeam(team_name){
        if (team_name.value == ''){
            $('#used').html('&nbsp;')
            return
        }
    
        $.post
        (
            'lowercasetext.php',
            {text: team_name.value},
            function(data){
                $('#team_name').val(data)
            }
        )
    }
    </script>
_END;

    $error = $team_name = $team_pass = $team_id = $user_id = "";

    if (isset($_POST['team_name'])){
        $team_name = sanitizeString($_POST['team_name']);
        $team_pass = sanitizeString($_POST['team_pass']);

        if ($team_name == "" || $team_pass == "") $error = 'Не все поля заполнены<br><br>';
        else{
            $result_teams = queryMysql("SELECT * FROM teams WHERE name='$team_name'");

            if ($result_teams->num_rows == 0){
                $error = "Неверная попытка входа";
            } 
            else{
                $row_teams = $result_teams->fetch_array(MYSQLI_BOTH);
                $result_teams->close();

                $team_id = $row_teams['id'];
                $user_id = $_SESSION['user_id'];

                $result_team_users = queryMysql("SELECT * FROM team_users WHERE team_id = $team_id AND user_id = $user_id");

                if ($result_team_users->num_rows == 0){
                    if (password_verify($team_pass, $row_teams['pass'])){
                        queryMysql("INSERT INTO team_users (team_id, user_id) VALUES('$team_id', '$user_id')");
                        redirectToTime('./teams.php', 0);
                    }
                    else{
                        $error = "Неверная попытка входа";
                    }
                }
                else{
                    $error = "Команда $team_name уже была добавлена ранее";
                }
                
                
            }
        }
    }

echo <<<_END
                <form method='post' action='joinTeam.php'>$error
                <div data-role='fieldcontain'>
                    <label></label>
                    Пожайлуйста введите данные: Название команды, Пароль
                </div>
                <div data-role='fieldcontain'>
                    <label>Название команды</label>
                    <input id='team_name' type='text' maxlength='16' name='team_name' value='$team_name'
                        onBlur="checkTeam(this)">
                    <label></label><div id='used'>&nbsp;</div>
                </div>
                <div data-role='fieldcontain'>
                    <label>Пароль</label>
                    <input type='password' maxlength='16' name='team_pass' value='$team_pass'>
                </div>
                <div data-role='fieldcontain'>
                    <label></label>
                    <input data-transition='slide' type='submit' value='Присоединиться'>
                </div>
                </form>
_END;

    require_once 'footer.php';
?>