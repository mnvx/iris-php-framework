<?php
namespace IrisPHPFramework;

/**
 * CoreDBList Class
 *
 * Queries for interactions with database
 *
 * @license MIT, http://opensource.org/licenses/mit-license.php
 */

class CoreDBList extends \ArrayIterator {
  use Singleton;

  protected $_msg;

  /**
   * Get message about last operation
   */
  public function get_msg()
  {
    return $this->_msg;
  }

  /**
   * Get database connection by sinonym (by key in db list)
   *
   * @param $db_name Database sinonym
   */
  public function get_db_connection($db_name = null)
  {
    $db = null;
    if (!$db_name) {
      if ($this->count() == 1) {
        $this->seek(0);
        $db = $this->current();
      }
    }
    else {
      if (!$this->offsetExists($db_name)) {
        $this->_msg = __METHOD__.': '._('Database with index').' "'.$db_name.'" '._('was not found').'.';
        return null;
      }
      $db = $this->offsetGet($db_name);
    }
    if (!$db) {
      $this->_msg = _('Database info was not found in query').' '.__METHOD__;
    }
    return $db;
  }

  /**
   * Execute query on selected db
   *
   * @param $db_name Database sinonym
   * @param $sql SQL query
   * @param $params Array Associative array of parameters for query
   */
  public function run_query($sql, $params = array(), $db_name = null)
  {
    $db = $this->get_db_connection($db_name);
    if (!$db) {
      $this->_msg = _('Database was not found').': "'.$db_name.'"; '.__METHOD__;
      return null;
    }

    try {
      $query = $db->prepare($sql);
    }
    catch (Exception $e) {
      $this->_msg = _('Error when preparing the query').' '.__METHOD__;
      return null;
    }

    if (!$query) {
      $this->_msg = _('Error in query').' '.__METHOD__;
      return null;
    }

    try {
      $query->execute($params);
    }
    catch (PDOException $e) {
      $this->_msg = _('Error when executing the query').' '.__METHOD__;
      return null;
    }

    return $query;
  }

}

?>