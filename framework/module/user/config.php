<?php
namespace IrisPHPFramework;

/**
 * Custom configuration values
 *
 * @license MIT, http://opensource.org/licenses/mit-license.php
 */

class UserConfig extends CoreConfig {

  // Structure of the current module
  public static $module_structure = array(
    // Current module name
    'name' => 'User',
    // What modules are required for current module
    'require_module' => array('Core'),
    // Format: BaseClassName => array('class' => ClassName[, 'parent' => PaerntClassName])
    'classes' => array(
      'UserConfig' => array(
        'class' => 'UserConfig', 
      ),
      'UserDB' => array(
        'class' => 'UserDB'
      ),
      'UserModel' => array(
        'class' => 'UserModel'
      ),
      'UserController' => array(
        'class' => 'UserController'
      ),
    ),
  );

  public static $routes = array(
    'profile' => array(
      'pattern' =>'/user', 
      'controller' => 'user', 
      'action' => 'index',
    ),
    'profile_edit' => array(
      'pattern' =>'/user/edit', 
      'controller' => 'user', 
      'action' => 'edit',
    ),
    'login' => array(
      'pattern' =>'/login', 
      'controller' => 'user', 
      'action' => 'login',
    ),
    'logout' => array(
      'pattern' =>'/logout', 
      'controller' => 'user', 
      'action' => 'logout',
    ),
    'signup' => array(
      'pattern' =>'/signup', 
      'controller' => 'user', 
      'action' => 'register',
      'caching' => true,
    ),
    // Route with parameters example
    'user' => array(
      'pattern' =>'/users/{id}',
      'controller' => 'user', 
      'action' => 'info',
      'requirements' => array(
        'id' => '[\d]{1,8}',
      ),
    ),
  );
  
  /**
   * Module path (current module path)
   */
  public static function module_path()
  {
    return __DIR__;
  }

  public static function init() {
    $userdb_class_name = get_final_class_name('UserDB');
    $UserDB = $userdb_class_name::singleton();
    $UserDB->set_db_list();

    // Check the database structure
    $UserDB->check_db('db');

    // User model
    $UserModel = UserModel::singleton();

    // View
    $view_class_name = get_final_class_name('View');
    $View = $view_class_name::singleton();
    $View->assign('user_name', $UserModel->get_name());
    $View->assign('user_login', $UserModel->get_login());
    $View->register_custom_object('user', $UserModel);
  }
}

?>