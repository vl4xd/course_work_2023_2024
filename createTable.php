<?php
    require_once 'header.php';

    if (!$loggedin ||!isset($_GET['team_id'])){
        redirectToTime('./index.php', 0);
        exit;
    }

    $error = $table_name = "";
    $columnCounter = 0;
    $user_id = $_SESSION['user_id'];
    $team_id = sanitizeString($_GET['team_id']);
    $team_public = false;
    $jsonTypes = json_encode($GLOBALS['types']); // передача массива с типами в js

echo<<<_END
    <script>
        function addColumn(childrenCounter) {
            $('.columns').append("<div data-role='fieldcontain'>").enhanceWithin(); 
            $('.columns').append("<input type='text' data-inline='true'>").enhanceWithin();
            $('.columns').append("</div>").enhanceWithin();
        }
        
        function deleteColumn(column_id){

        }
    </script>
_END;

    if(isset($_POST['table_name'])){
        $table_name = sanitizeString($_POST['table_name']);
        $table_info = sanitizeString($_POST['table_info']);
    }

echo <<<_END
            <form method='post' action='createTable.php'>$error
            <div data-role='fieldcontain'>
                <label></label>
                Пожайлуйста введите данные: Название таблицы, Описание
            </div>
            <div data-role='fieldcontain'>
                <label>Название таблицы</label>
                <input id='table_name' type='text' maxlength='16' name='table_name' value='$table_name'
                    onBlur="checkTable(this)">
                <label></label><div id='used'>&nbsp;</div>
            </div>
            <div data-role='fieldcontain'>
                <label>Описание</label>
                <textarea cols="40" rows="8" name="table_info" id="table_info"></textarea>
            </div>
            <div class="columns">
                
            </div>
            <script>
                var childrenCounter = $('.columns').children().length;
                if (childrenCounter == 0){
                    for (var i = 0; i < 3; i++){
                        addColumn(childrenCounter);
                    }
                }
            </script>
            <div data-role='fieldcontain'>
                <label></label>
                <input type='button' value='Добавить' onClick="addColumn()">
            </div>
            <div data-role='fieldcontain'>
                <label></label>
                <input data-transition='slide' type='submit' value='Создать'>
            </div>
            </form>
_END;

    require_once 'footer.php';
?>