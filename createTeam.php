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
            'checkteam.php',
            {
                team_name: team_name.value,
                team_mode: "create",
            },
            function(data){
                $('#used').html(data)
            }
        )

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

    $error = $team_name = $team_pass = $user_id = "";

    if (isset($_POST['team_name'])){
        $team_name = sanitizeString($_POST['team_name']);
        $team_pass = sanitizeString($_POST['team_pass']);

        if ($team_name == "" || $team_pass == "") $error = 'Не все поля заполнены<br><br>';
        else{
            $result_teams = queryMysql("SELECT * FROM teams WHERE name='$team_name'");

            if ($result_teams->num_rows) $error = "Имя команды $team_name уже занято";
            else {

                $team_pass_hash = password_hash($team_pass, PASSWORD_DEFAULT);
                $last_team_id = queryMysql("INSERT INTO teams (name, pass) VALUES('$team_name', '$team_pass_hash')");
                $user_id = $_SESSION['user_id'];
                queryMysql("INSERT INTO team_users (team_id, user_id) VALUES('$last_team_id', '$user_id')");
                redirectToTime('./teams.php', 0);
            }

            $result_teams->close();
        }
    }

echo <<<_END
                <form method='post' action='createTeam.php'>$error
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
                    <input data-transition='slide' type='submit' value='Создать'>
                </div>
                </form>
_END;

    require_once 'footer.php';
?>