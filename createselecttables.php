<?php
    // Используется для создания блока селект в createTable.php
    require_once 'functions.php';

    if (isset($_POST['team_id'])){
        $team_id = $_POST['team_id'];
        $options = '';

        $result = queryMysql("SELECT * FROM tables WHERE (public=0 AND team_id=$team_id) OR public=1");

        $num = $result->num_rows;

        for ($i = 0; $i < $num; $i++){
            $row = $result->fetch_array(MYSQLI_BOTH);
            $options .= '<option value="'. $row['id'] .'">' . $row['name'] . '</option>';
        }
        echo $options;
    }
?>