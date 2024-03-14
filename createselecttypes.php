<?php
    // Используется для создания блока селект в createTable.php
    require_once 'functions.php';

    $options = '';
    foreach($types as $key=>$value){
        $options .= '<option value="'. $key .'">' . $value . '</option>';
    }
    echo $options;
?>