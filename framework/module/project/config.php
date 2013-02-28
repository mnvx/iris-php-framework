<?php
namespace IrisPHPFramework;

/**
 * Custom configuration values
 *
 * @license MIT, http://opensource.org/licenses/mit-license.php
 */

class ProjectConfig extends CoreConfig {

  // Structure of the current module
  public static $module_structure = array(
    // Current module name
    'name' => 'Project',
    // What modules are required for current module
    'require_module' => array('Core'),
    // Format: BaseClassName => array('class' => ClassName[, 'parent' => PaerntClassName])
    'classes' => array(
      'Config' => array(
        'class' => 'ProjectConfig', 
        'parent' => 'CoreConfig'
      ),
      'Application' => array(
        'class' => 'ProjectApplication', 
        'parent' => 'CoreApplication'
      ),
      'Controller' => array(
        'class' => 'ProjectController', 
        'parent' => 'CoreController'
      ),
      'DB' => array(
        'class' => 'ProjectDB', 
        'parent' => 'CoreDB'
      ),
      'UserModel' => null,
      'OptionsController' => null,
      'SiteController' => null,
      'UserController' => null,
    ),
  );

  public static $base_url = '/framework/framework';
  public static $debug = true;
  
  public static $db = array(
    'dsn' => 'sqlite:[#base_path#]/data/sqlite.db',
    'username' =>  null,
    'password' => null,
    'driver_options' => null,
  );

  // Set the default controller hte user is directed to (aka homepage).
  public static $router_default_controller = 'site';
  public static $router_default_action = 'home';

  public static $routes = array(
    'home' => array(
      'pattern' =>'/', 
      'controller' => 'site', 
      'action' => 'home',
      'caching' => true,
    ),
    'about' => array(
      'pattern' =>'/about', 
      'controller' => 'site', 
      'action' => 'about',
      'caching' => true,
    ),
    'terms' => array(
      'pattern' =>'/terms', 
      'controller' => 'site', 
      'action' => 'terms',
      'caching' => true,
    ),
    'options' => array(
      'pattern' =>'/options', 
      'controller' => 'options', 
      'action' => 'index',
    ),
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
    /*
    // Format for links like /site/about
    'controller_action' => array(
      'pattern' =>'/{controller}/{action}',
    ),
    */
  );

  /**
   * Project path
   * @deprecated
   */
  public static function project_path()
  {
    return __DIR__;
  }
  
  /**
   * Module path (current module path)
   */
  public static function module_path()
  {
    return __DIR__;
  }

}

?>