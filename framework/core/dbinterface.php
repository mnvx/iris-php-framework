<?php
namespace IrisPHPFramework;

/**
 * CoreDBinterface Class
 *
 * Base class for queries to database
 *
 * @license MIT, http://opensource.org/licenses/mit-license.php
 */

class CoreDBInterface {
  use Singleton;

  // Type: CoreDBList
  protected $_DBList;

  /**
   * Set db list object
   * @param $DBList CoreDBList List of databases object
   */
  public function set_db_list($DBList = null)
  {
    if ($DBList) {
      $this->_DBList = $DBList;
    }
    else {
      $dblist_class_name = get_final_class_name('DBList');
      $this->_DBList = $dblist_class_name::singleton();
    }
  }

  /**
   * Get message about last operation
   */
  public function get_msg()
  {
    return $this->_DBList->get_msg();
  }

}

?>