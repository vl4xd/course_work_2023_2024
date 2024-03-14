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
        
        function fillSelectTypes(select){
            $.post
            (
                'createselecttypes.php',
                function(data){
                    select.append(data);
                }
            )
        }
        
        function fillSelectTables(select){
            var team_id = '$team_id';
            $.post(
                'createselecttables.php',
                {team_id: team_id},
                function(data){
                    select.append(data);
                }
            )
        }

        function deleteColumn(column_id){
            $('[class^="' + column_id + '"]').remove();
        }

        function addColumn() {
            var n = 'column_id_' + columnsCounter;
            var s = 'select_type_id_' + columnsCounter;
            var g = 'select_table_id_' + columnsCounter;

            var newDiv = $('<div class="' + n + '" data-role="fieldcontain" data-type="horizontal"></div>');
            var newLabel = $('<label>Столбец ' + columnsCounter + '</label>');
            var newInput = $('<input id="' + n + '" name="' + n + '" type="text" data-inline="true">');
            var newButton = $('<a data-inline="true" data-role="button" data-icon="">Удалить столбец</a>');

            var newSelectTypes = $('<select id="' + s + '" name="' + s + '" data-inline="true"></select>');
            var optionDefault = $('<option>Выберите тип данных</option>');
            newSelectTypes.append(optionDefault);
            fillSelectTypes(newSelectTypes);
            

            newButton.attr('onClick', 'deleteColumn(\'' + n + '\')');
            newDiv.append(newLabel);
            newDiv.append(newInput);
            newDiv.append(newButton);
            newDiv.append(newSelectTypes);
            $('.columns').append(newDiv).enhanceWithin();


            var newSelectTables = $('<select id="' + s + '" name="' + s + '" data-inline="true"></select>');
            var optionDefault = $('<option>Выберите вспомогательную таблицу</option>');
            newSelectTables.append(optionDefault);
            fillSelectTables(newSelectTables);
            
            $(function() {
                newSelectTypes.change(function(){
                    var selectedValue = $(this).val();
                    if (selectedValue === "table_data"){
                        newDiv.append(newSelectTables);
                        $('.columns').enhanceWithin();
                    }
                    else {
                        if (newSelectTables) {
                            newSelectTables.remove();
                        }
                    }
                })
            })

            

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