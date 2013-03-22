<?php
namespace IrisPHPFramework;

/**
 * App Class
 *
 * Initialisation of objects
 *
 * @license MIT, http://opensource.org/licenses/mit-license.php
 */

class ProjectApplication extends CoreApplication {

  protected function _custom_after_routing()
  {
    parent::_custom_after_routing();
    try {
      // Connecting to database
      $Config = get_final_class_name('Config');
      $PDO = new \PDO(
        str_replace('[#base_path#]', $Config::base_path(), $Config::$db['dsn']), 
        $Config::$db['username'], 
        $Config::$db['password'], 
        $Config::$db['driver_options']
      );
      $PDO->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

      $dblist_class_name = get_final_class_name('DBList');
      $DBList = $dblist_class_name::singleton();
      $DBList->offsetSet('db', $PDO);

    }
    catch (Exception $e) {
      //die($e);
    }
  }

}

?>