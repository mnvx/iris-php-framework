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
      'ProjectController' => array(
        'class' => 'ProjectController'
      ),
      'OptionsController' => array(
        'class' => 'OptionsController'
      ),
      'SiteController' => array(
        'class' => 'SiteController'
      ),
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
    /*
    // Format for links like /site/about
    'controller_action' => array(
      'pattern' =>'/{controller}/{action}',
    ),
    */
  );
  
  /**
   * Module path (current module path)
   */
  public static function module_path()
  {
    return __DIR__;
  }

}

?>