<?php
    require_once 'header.php';

    if (!isset($_GET['team_id'])){
        redirectToTime('./index.php', 0);
        exit;
    }

    $user_id = $_SESSION['user_id'];
    $team_id = sanitizeString($_GET['team_id']);
    $check_team_users = false;
    
    $result_team_users = queryMysql("SELECT * FROM team_users WHERE team_id = $team_id AND user_id = $user_id");
    if ($result_team_users->num_rows){
        $check_team_users = true;
    }
    $result_team_users->close();

    if (!$loggedin || !$check_team_users) redirectToTime('./index.php', 0);

echo <<< _TABLES
    <div>
        <a data-role='button' data-inline='true' data-icon='plus'
            data-transition='slide' href="./createTable.php?team_id=$team_id">Создать</a>
    </div>
    <ul id='teams' data-role="listview" data-filter="true" data-filter-placeholder="Имя таблицы" data-inset="true">
_TABLES;
    
        $result = queryMysql("SELECT * FROM tables WHERE team_id = '$team_id'");
        $num = $result->num_rows;
    
        for ($i = 0; $i < $num; $i++){
            $row = $result->fetch_array(MYSQLI_BOTH);
            $hrefTable = "./tableView.php?table_id=" . $row['id'];
            $nameTable = $row['name'];
            echo "<li><a data-transition='slide' href='$hrehrefTablefTeam'>$nameTable</a></li>";
        }
        $result->close();
    
echo <<<_TABLES
        </ul>
_TABLES;

    require_once 'footer.php';
?>