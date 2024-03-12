<!DOCTYPE html>
<html>
  <head>
    <title>Setting up database</title>
  </head>
  <body>

    <h3>Setting up...</h3>

<?php
  require_once 'functions.php';

  //pass 331
  createTable('users', 
              'id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
              name VARCHAR(16),
              pass VARCHAR(255),
              INDEX(name(16)),
              INDEX(pass(16))');

  createTable('teams',
              'id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
              name VARCHAR(16),
              pass VARCHAR(255),
              INDEX(name(16)),
              INDEX(pass(16))');

  createTable('team_users',
              'team_id BIGINT UNSIGNED,
              user_id BIGINT UNSIGNED,
              FOREIGN KEY (team_id) REFERENCES teams (id)
                ON DELETE CASCADE
                ON UPDATE CASCADE,
              FOREIGN KEY (user_id) REFERENCES users (id)
                ON DELETE CASCADE
                ON UPDATE CASCADE');

  createTable('types',
              'id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
              name varchar(16),
              INDEX(name)');

  createTable('tables',
              'id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
              name VARCHAR(16),
              info VARCHAR(255),
              public BOOLEAN,
              date DATETIME,
              team_id BIGINT UNSIGNED,
              user_id BIGINT UNSIGNED,
              FOREIGN KEY (team_id) REFERENCES teams (id)
                ON DELETE CASCADE
                ON UPDATE CASCADE,
              FOREIGN KEY (user_id) REFERENCES users (id)
                ON DELETE SET NULL
                ON UPDATE CASCADE');
  
  createTable('columns',
              'id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
              name VARCHAR(16),
              info VARCHAR(255),
              type_id BIGINT UNSIGNED,
              table_id BIGINT UNSIGNED,
              table_data_id BIGINT UNSIGNED,
              FOREIGN KEY (type_id) REFERENCES types (id)
                ON DELETE SET NULL
                ON UPDATE CASCADE,
              FOREIGN KEY (table_id) REFERENCES tables (id)
                ON DELETE CASCADE
                ON UPDATE CASCADE,
              FOREIGN KEY (table_data_id) REFERENCES tables (id)
                ON DELETE SET NULL
                ON UPDATE CASCADE');

  createTable('notes',
              'id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
              date DATETIME,
              table_id BIGINT UNSIGNED,
              user_id BIGINT UNSIGNED,
              FOREIGN KEY (table_id) REFERENCES tables (id)
                ON DELETE CASCADE
                ON UPDATE CASCADE,
              FOREIGN KEY (user_id) REFERENCES users (id)
                ON DELETE SET NULL
                ON UPDATE CASCADE'); 

  createTable('items',
              'id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
              column_id BIGINT UNSIGNED,
              note_id BIGINT UNSIGNED,
              FOREIGN KEY (column_id) REFERENCES columns (id)
                ON DELETE CASCADE
                ON UPDATE CASCADE,
              FOREIGN KEY (note_id) REFERENCES notes (id)
                ON DELETE CASCADE
                ON UPDATE CASCADE');


  foreach($GLOBALS['types'] as $type_key => $type_value){
    $result = queryMysql("SELECT * FROM types WHERE name = '$type_key'");
    if ($result->num_rows) continue;
    queryMysql("INSERT INTO types (name) VALUES ('$type_key')");
  }

?>

    <br>...done.
  </body>
</html>