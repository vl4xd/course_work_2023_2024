<?php
    require_once 'header.php';

    if (!$loggedin) redirectToTime('./index.php', 0);

echo <<< _TEAMS
<h4 class='center' data-inline='true'>Команды:<h4>
<a data-role='button' data-inline='true' data-icon='home'
    data-transition='slide' href='joinTeam.php'>Присоединиться</a>
<a data-role='button' data-inline='true' data-icon='plus'
    data-transition='slide' href='createTeam.php'>Создать</a>
<ul data-role="listview" data-filter="true" data-filter-placeholder="Название команды" data-inset="true">
    <li><a href="#">Команда 1</a></li>
    <li><a href="#">Команда 2</a></li>
</ul>
_TEAMS;


    require_once 'footer.php';
?>