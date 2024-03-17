<?php
    require_once 'header.php';

    if (!$loggedin || !isset($_GET['table_id'])){
        redirectToTime('./index.php', 0);
        exit;
    }

    
    // Проверка возможности входа в раздел пользователю

    $error = "";
    $table_id = sanitizeString($_GET['table_id']);

echo<<<_END
<div>
    <a data-role='button' data-inline='true' data-icon='plus'
        data-transition='slide' href="./createNote.php?table_id=$table_id">Создать запись</a>
</div>
_END;

echo <<< _TABLES
    <ul id='teams' data-role="listview" data-filter="true" data-filter-placeholder="Имя таблицы" data-inset="true">
_TABLES;

    $result_notes = queryMysql("SELECT notes.id as note_id, items.id as item_id, types.name as type FROM items
                                INNER JOIN notes ON items.note_id = notes.id
                                INNER JOIN columns ON items.column_id = columns.id
                                INNER JOIN types ON columns.type_id = types.id
                                WHERE notes.table_id =" . $table_id);
    $num_notes = $result_notes->num_rows;

    $current_note_id = $next_note_id = 0;
    $value = $hrefNote = "";
    
    for ($i = 0; $i < $num_notes; $i++){        
        $row_notes = $result_notes->fetch_array(MYSQLI_BOTH);

        if ($i == 0) {
            $current_note_id = $row_notes['note_id'];
        }

        $result_item = queryMysql('SELECT * FROM ' . $row_notes['type'] . ' WHERE item_id =' . $row_notes['item_id']);
        $row_item = $result_item->fetch_array(MYSQLI_BOTH);


        $next_note_id = $row_notes['note_id'];
        if ($current_note_id == $next_note_id){
            $value .= $row_item['data'] . " ";
            
        }
        else{
            $hrefNote = "./noteView.php?note_id=" . $current_note_id;
            echo "<li><a data-transition='slide' href='$hrefNote'>$value</a></li>";
            $current_note_id = $next_note_id;
            $value = "";
            $value .= $row_item['data'] . " ";
        }
    }

    echo "<li><a data-transition='slide' href='$hrefNote'>$value</a></li>";
    $result_notes->close();
    $result_item->close();
    
echo <<<_TABLES
        </ul>
_TABLES;

    require_once 'footer.php';
?>