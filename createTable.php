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
        $current_datetime = date('Y-m-d H:i:s');

        $last_table_id = queryMysql("INSERT INTO tables (name, info, public, date, team_id, user_id) 
                                    VALUES ('$table_name', '$table_info', 0, '$current_datetime', '$team_id', '$user_id')");

        $columns = $_POST['columns'];

        /*
        for ($i = 0; $i < count($columns); $i++){
            $column_name = $columns[$i]['name'];
            $column_type = $columns[$i]['type'];

            $result_type = queryMysql("SELECT * FROM types WHERE name='$column_type'");
            $type_id = $result_type->fetch_array(MYSQLI_BOTH);
            $column_type_id = $type_id['id'];

            if (isset($columns[$i]['table'])) {  
                $column_table_id = $columns[$i]['table'];
                queryMysql("INSERT INTO columns (name, type_id, table_id, table_data_id)
                        VALUES ('$column_name', $column_type_id, $last_table_id, $column_table_id)");
            }
            else{
                queryMysql("INSERT INTO columns (name, type_id, table_id)
                        VALUES ('$column_name', $column_type_id, $last_table_id)");
            }
        }
        */

        foreach ($columns as $column_id => $value) {
            $column_name = $column_type = $table_data_id = "";
            foreach ($value as $type => $data) {
                switch ($type){
                    case 'name':
                        $column_name = $data;
                        break;
                    case 'type':
                        $column_type = $data;
                        break;
                    case 'table':
                        $table_data_id = $data;
                        break;
                }
            }
            $result_type = queryMysql("SELECT * FROM types WHERE name='$column_type'");
            $type_id = $result_type->fetch_array(MYSQLI_BOTH);
            $column_type_id = $type_id['id'];

            if ($table_data_id != "") {
                queryMysql("INSERT INTO columns (name, type_id, table_id, table_data_id)
                        VALUES ('$column_name', $column_type_id, $last_table_id, $table_data_id)");
            }
            else{
                queryMysql("INSERT INTO columns (name, type_id, table_id)
                        VALUES ('$column_name', $column_type_id, $last_table_id)");
            }
        }

        redirectToTime('./teamTables.php?team_id=' . $team_id, 0);
    }

echo <<<_END
            <form id='myForm' method='post' action='./createTable.php?team_id=$team_id'>$error
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
            var newInput = $('<input id="' + n + '" name="columns[' + columnsCounter + '][name]" type="text" data-inline="true">');
            var newButton = $('<a data-inline="true" data-role="button" data-icon="">Удалить столбец</a>');

            var newSelectTypes = $('<select id="' + s + '" name="columns[' + columnsCounter + '][type]" data-inline="true"></select>');
            var optionDefault = $('<option>Выберите тип данных</option>');
            newSelectTypes.append(optionDefault);
            fillSelectTypes(newSelectTypes);
            

            newButton.attr('onClick', 'deleteColumn(\'' + n + '\')');
            newDiv.append(newLabel);
            newDiv.append(newInput);
            newDiv.append(newButton);
            newDiv.append(newSelectTypes);
            $('.columns').append(newDiv).enhanceWithin();


            var newSelectTables = $('<select id="' + g + '" name="columns[' + columnsCounter + '][table]" data-inline="true"></select>');
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