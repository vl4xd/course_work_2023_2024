<?php
    require_once 'header.php';

    if (!$loggedin) redirectToTime('./index.php', 0);

    $user_id = $_SESSION['user_id'];



echo <<< _TEAMS
<div>
    <a data-role='button' data-inline='true' data-icon='home'
        data-transition='slide' href='joinTeam.php'>Присоединиться</a>
    <a data-role='button' data-inline='true' data-icon='plus'
        data-transition='slide' href='createTeam.php'>Создать</a>
</div>
<ul id='teams' data-role="listview" data-filter="true" data-filter-placeholder="Название команды" data-inset="true">
_TEAMS;

    $result = queryMysql("SELECT teams.id as team_id, teams.name as team_name FROM team_users 
                            INNER JOIN users ON team_users.user_id = users.id
                            INNER JOIN teams ON team_users.team_id = teams.id
                            WHERE users.id = $user_id");
    $num = $result->num_rows;

    for ($i = 0; $i < $num; $i++){
        $row = $result->fetch_array(MYSQLI_BOTH);
        $hrefTeam = "./teamInfo.php?team_id=" . $row['team_id'];
        $nameTeam = $row['team_name'];
        echo "<li><a data-transition='slide' href='$hrefTeam'>$nameTeam</a></li>";
    }

echo <<<_TEAMS
    </ul>
_TEAMS;

    require_once 'footer.php';
?>