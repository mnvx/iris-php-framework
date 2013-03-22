<?php
namespace IrisPHPFramework;

/**
 * App Class
 *
 * Initialisation of objects
 *
 * @license MIT, http://opensource.org/licenses/mit-license.php
 */

class UserDB extends CoreDBInterface {

  public function get_user_info_by_id($db_name, $id) {
    $sql = 'SELECT id, password, name, login FROM users WHERE id = :id';
    return $this->_DBList->run_query($sql, array(
      'id' => $id,
    ), $db_name);
  }

  public function get_user_info($db_name, $login) {
    $sql = 'SELECT id, password, name, login FROM users WHERE login = :login';
    return $this->_DBList->run_query($sql, array(
      'login' => $login,
    ), $db_name);
  }

  public function insert_user($db_name, $fields) {
    $field_names = '';
    $field_values = '';
    foreach ($fields as $key => $value) {
      $field_names .= ($field_names ? ', ' : '').$key;
      $field_values .= ($field_values ? ', ' : '').':'.$key;
    }
    $sql = 'INSERT INTO users ('.$field_names.') VALUES ('.$field_values.')';
    
    return $this->_DBList->run_query($sql, $fields, $db_name);
  }

  public function update_user($db_name, $id, $fields) {
    $field_values = '';
    foreach ($fields as $key => $value) {
      $field_values .= ($field_values ? ', ' : '').$key.' = :'.$key;
    }
    $fields['id'] = $id;
    $sql = 'UPDATE users SET '.$field_values.' WHERE id = :id';

    return $this->_DBList->run_query($sql, $fields, $db_name);
  }

  /**
   * Check to see if the proper tables exist in the database and if not,
   * create them.
   */
  function check_db($db_name = null) {
    $DB = $this->_DBList->get_db_connection($db_name);
    if ($DB) {
      $sql = 'CREATE TABLE IF NOT EXISTS users ( 
         id INTEGER PRIMARY KEY,
         name TEXT,
         login TEXT,
         password TEXT,
         create_ip TEXT,
         create_date TEXT,
         status INTEGER
      )';
      $query = $DB->prepare($sql);
      $query->execute();
    }
  }

}

?>