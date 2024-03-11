<?php
  require_once 'functions.php';

  if (isset($_POST['team_name']) && isset($_POST['team_mode']))
  {
    $team_name = sanitizeString($_POST['team_name']);
    $team_mode = sanitizeString($_POST['team_mode']);

    switch($team_mode){
        
        case "join":
            //фун-ия удалена
            
        case "create":
            $result = queryMysql("SELECT * FROM teams WHERE name = '$team_name'");

            if ($result->num_rows)
                echo  "<span class='taken'>&nbsp;&#x2718; " .
                    "Имя команды $team_name уже занято</span>";
            else
                echo "<span class='available'>&nbsp;&#x2714; " .
                    "Имя команды $team_name свободно</span>";
            break;
    }
    
  }
?>
