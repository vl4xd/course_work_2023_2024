<?php
    require_once 'header.php';

    if (!$loggedin || !isset($_GET['table_id'])){
        redirectToTime('./index.php', 0);
        exit;
    }

    
    // Проверка возможности входа в раздел пользователю

    $error = "";
    $table_id = sanitizeString($_GET['table_id']);
    $user_id = $_SESSION['user_id'];

    if (isset($_POST['columns'])){
        $current_datetime = date('Y-m-d H:i:s');
        $last_note_id = queryMysql("INSERT INTO notes (date, table_id, user_id)
                                    VALUES ('$current_datetime', '$table_id', '$user_id')");

        $columns = $_POST['columns'];
        foreach ($columns as $column_id => $value) {
            foreach ($value as $type => $data) {
                $last_item_id = queryMysql("INSERT INTO items (column_id, note_id)
                                            VALUES ('$column_id', '$last_note_id')");
                queryMysql("INSERT INTO " . $type . " (item_id, data)
                            VALUES ('$last_item_id', '$data')");
            }
        }
    }

echo<<<_FORM_START
<form id='myForm' method='post' action='./createNote.php?table_id=$table_id'>$error
_FORM_START;

$result_columns = queryMysql("SELECT columns.*, types.name as type FROM columns 
                            INNER JOIN types ON columns.type_id = types.id 
                            WHERE columns.table_id = $table_id");
$num_columns = $result_columns->num_rows;

for ($i = 0; $i < $num_columns; $i++){
    $row_columns = $result_columns->fetch_array(MYSQLI_BOTH);
    $name = $row_columns['name'];
    $input_name = "columns[" . $row_columns['id'] . "]";
    switch($row_columns['type']){
        case 'text_data':
            $input_name .= "[text_data]";
            echo<<<_END
                <div data-role='fieldcontain'>
                    <label>$name</label>
                    <input id='table_name' type='text' maxlength='64' name='$input_name' value=''>
                    <label></label>
                </div>
            _END;
            break;
        case 'textarea_data':
            $input_name .= "[textarea_data]";
            echo<<<_END
                <div data-role='fieldcontain'>
                    <label>$name</label>
                    <textarea cols="40" rows="8" name="$input_name" id="table_info"></textarea>
                    <label></label>
                </div>
            _END;
            break;
        case 'int_data':
            $input_name .= "[int_data]";
            echo<<<_END
                <div data-role='fieldcontain'>
                    <label>$name</label>
                    <input id='table_name' type='number' maxlength='255' name='$input_name' value=''>
                    <label></label>
                </div>
            _END;
            break;
        case 'float_data':
            $input_name .= "[float_data]";
            echo<<<_END
                <div data-role='fieldcontain'>
                    <label>$name</label>
                    <input id='table_name' type='number' pattern="^-?\d+(\.\d{1,2})?$" name='$input_name' value=''>
                    <label></label>
                </div>
            _END;
            break;
        case 'date_data':
            $input_name .= "[date_data]";
            echo<<<_END
                <div data-role='fieldcontain'>
                    <label>$name</label>
                    <input type="date" name="$input_name" id="date" value="">
                    <label></label>
                </div>
            _END;
            break;
        case 'file_data':
            $input_name .= "[file_data]";
            echo<<<_END
                <div data-role='fieldcontain'>
                    <label>$name</label>
                    <input type="file" name="$input_name" id="file" value="">
                    <label></label>
                </div>
            _END;
            break;
        case 'bool_data':
            $input_name .= "[bool_data]";
            echo<<<_END
                <div data-role='fieldcontain'>
                    <label>$name</label>
                    <select name="$input_name" id="slider2" data-role="slider">
                        <option value="0">Нет</option>
                        <option value="1">Да</option>
                    </select>
                </div>
            _END;
            break;
        case 'table_data':
            $result_notes = queryMysql('SELECT notes.id as note_id, items.id as item_id, types.name as type FROM notes
                                        INNER JOIN items ON notes.id = items.note_id
                                        INNER JOIN columns ON items.column_id = columns.id
                                        INNER JOIN types ON columns.type_id = types.id
                                        WHERE notes.table_id =' . $row_columns['table_data_id']);
            $num_notes = $result_notes->num_rows;

            $input_name .= "[table_data]";
            echo<<<_END
                <div data-role='fieldcontain'>
                    <label>$name</label>
                    <select id="anotherSelect" name="$input_name">
            _END;

            $current_value = $next_value = 0;
            $key = "";
            for ($j = 0; $j < $num_notes; $j++){
                $row_notes = $result_notes->fetch_array(MYSQLI_BOTH);

                if ($j == 0) {
                    $current_value = $row_notes['note_id'];
                }

                $result_item = queryMysql('SELECT * FROM ' . $row_notes['type'] . ' WHERE item_id =' . $row_notes['item_id']);
                $row_item = $result_item->fetch_array(MYSQLI_BOTH);

                $next_value = $row_notes['note_id'];
                if ($current_value == $next_value){
                    $key .= $row_item['data'] . " ";
                    
                }
                else{
                    echo '<option value="' . $current_value . '">' . $key . '</option>';
                    $current_value = $next_value;
                    $key = "";
                    $key .= $row_item['data'] . " ";
                }
            }
            echo '<option value="' . $current_value . '">' . $key . '</option>';

            echo<<<_END
                    </select>
                </div>
            _END;

            $result_notes->close();
            $result_item->close();

            break;
    }
}
$result_columns->close();

echo<<<_FORM_END
<div data-role='fieldcontain'>
    <label></label>
    <input data-transition='slide' type='submit' value='Создать запись'>
</div>
</form>
_FORM_END;

    require_once 'footer.php';
?>