<?php
namespace IrisPHPFramework;

/**
 * App Class
 *
 * Initialisation of objects
 *
 * @license MIT, http://opensource.org/licenses/mit-license.php
 */

class Application extends CoreApplication {

  protected function custom_after_routing()
  {
    try {
      // Connecting to database
      $db = new \PDO(
        str_replace('[#base_path#]', Config::base_path(), Config::$db['dsn']), 
        Config::$db['username'], 
        Config::$db['password'], 
        Config::$db['driver_options']
      );
      $db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
      $databases = DB::singleton();
      $databases->offsetSet('db', $db);
      // Check the database structure
      $databases->check_db('db');
    } 
    catch (Exception $e) {
      //die($e);
    }
    
    // User model
    $user = UserModel::singleton();

    // View
    $class_view = get_final_class_name('View');
    $view = $class_view::singleton();
    $view->assign('user_name', $user->get_name());
    $view->assign('user_login', $user->get_login());
    $view->register_custom_object('user', $user);
  }
  
}

?>