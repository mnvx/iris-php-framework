<?php

/**
 * CoreDataBases Class
 *
 * Queries for interactions with database
 *
 * @license MIT, http://opensource.org/licenses/mit-license.php
 */

class CoreDataBases extends ArrayIterator {
  use Singleton;

  protected $msg;
  
  public function get_msg()
  {
    return $this->msg;
  }
  
  public function select_db($db_name)
  {
    if (!$this->offsetExists($db_name)) {
      $this->msg = __METHOD__.': '._('Database with index').' "'.$db_name.'" '._('was not found').'.';
      return null;
    }
    $db = $this->offsetGet($db_name);
    if (!$db) {
      $this->msg = _('Database info was not found in query').' '.__METHOD__;
    }
    return $db;
  }
  
  public function run_query($db_name, $sql, $fields = array())
  {
    $db = $this->select_db($db_name);
    if (!$db) {
      return null;
    }

    try {
      $query = $db->prepare($sql);
    }
    catch (Exception $e) {
      $this->msg = _('Error when preparing the query').' '.__METHOD__;
      return null;
    }
    
    if (!$query) {
      $this->msg = _('Error in query').' '.__METHOD__;
      return null;
    }

    try {
      $query->execute($fields);
    }
    catch (Exception $e) {
      $this->msg = _('Error when executing the query').' '.__METHOD__;
      return null;
    }
    
    return $query;
  }
}

?>