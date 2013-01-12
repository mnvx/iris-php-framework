<?php

/**
 * App Class
 *
 * Initialisation of objects
 *
 * @license MIT, http://opensource.org/licenses/mit-license.php
 */

class DataBases extends CoreDataBases {

  public function get_user_info($db_name, $login) {
    $sql = 'SELECT id, password, name, login FROM users WHERE login = :login';
    return $this->run_query($db_name, $sql, array(
      'login' => $login,
    ));
  }

  public function insert_user($db_name, $fields) {
    $field_names = '';
    $field_values = '';
    foreach ($fields as $key => $value) {
      $field_names .= ($field_names ? ', ' : '').$key;
      $field_values .= ($field_values ? ', ' : '').':'.$key;
    }
    $sql = 'INSERT INTO users ('.$field_names.') VALUES ('.$field_values.')';
    
    return $this->run_query($db_name, $sql, $fields);
  }

  public function update_user($db_name, $id, $fields) {
    $field_values = '';
    foreach ($fields as $key => $value) {
      $field_values .= ($field_values ? ', ' : '').$key.' = :'.$key;
    }
    $fields['id'] = $id;
    $sql = 'UPDATE users SET '.$field_values.' WHERE id = :id';

    return $this->run_query($db_name, $sql, $fields);
  }

  /**
   * Check to see if the proper tables exist in the database and if not,
   * create them.
   */
  function check_db($dbname) {
    $db = $this->offsetGet($dbname);

    $sql = 'CREATE TABLE IF NOT EXISTS users ( 
       id INTEGER PRIMARY KEY,
       name TEXT,
       login TEXT,
       password TEXT,
       create_ip TEXT,
       create_date TEXT,
       status INTEGER
    )';
    $query = $db->prepare($sql);
    $query->execute();
  }

}

?>