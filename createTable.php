<?php
    require_once 'header.php';

    if (!$loggedin || !isset($_GET['team_id'])){
        redirectToTime('./index.php', 0);
        exit;
    }

    //проверка на возможность редактирования таблицы table_id пользвоателем 

    $error = $table_name = "";
    $columnCounter = 0;
    $user_id = $_SESSION['user_id'];
    $team_id = sanitizeString($_GET['team_id']);
    $team_public = false;
    $jsonTypes = json_encode($GLOBALS['types']); // передача массива с типами в js

    if(isset($_POST['table_name'])){
        $table_name = sanitizeString($_POST['table_name']);
        $table_info = sanitizeString($_POST['table_info']);
    }

echo <<<_END
            <form method='post' action='./createTable.php?team_id=$team_id'>$error
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

echo<<<_END
    <script>
        var columnsCounter = $('.columns').children().length;

        function deleteColumn(column_id){
            $('[class^="' + column_id + '"]').remove();
        }

        function addColumn() {
            var n = 'column_id_' + columnsCounter;

            var newDiv = $('<div class="' + n + '" data-role="fieldcontain"></div>');
            var newInput = $('<input id="' + n + '" name="' + n + '" type="text" data-inline="true">');
            var newButton = $('<a data-inline="true" class="ui-btn ui-icon-delete ui-btn-icon-notext ui-corner-all"></a>');
            newButton.attr('onClick', 'deleteColumn(\'' + n + '\')');
            newDiv.append(newInput);
            newDiv.append(newButton);
            $('.columns').append(newDiv).enhanceWithin();

            newButton.on('click', function() {
                deleteColumn(n);
            });

            columnsCounter++;
        }

        if (columnsCounter == 0){
            for (var i = 0; i < 3; i++){
                addColumn();
            }
        }
    </script>
_END;

    require_once 'footer.php';
?>