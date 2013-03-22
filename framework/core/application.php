<?php
namespace IrisPHPFramework;

/**
 * CoreApp Class
 *
 * Staring the session and routing
 *
 * @license MIT, http://opensource.org/licenses/mit-license.php
 */

class CoreApplication {

  /**
   * Main constructor
   */
  public function __construct() {
    // Start the session
    $this->session_start();

    // Routing
    $this->execute_route();
  }

  /**
   * Start the user session
   */
  public function session_start() {
    if (!session_id()) {
      session_start();
    }
  }

  /**
   * Figure out where the user is trying to get to and route them to the
   * appropriate controller/action.
   */
  public function execute_route() {
    $Config = get_final_class_name('Config');
    $router_class_name = get_final_class_name('Router');

    // Get the Router object
    $Router = $router_class_name::singleton();

    // Configure the routes, where the user should go when they access the
    // specified URL structures.
    $Module = CoreModule::singleton();
    $config_class_names = $Module->get_config_class_names();
    foreach ($config_class_names as $config_class_name => $module_path_name) {
      $full_config_class_name = 'IrisPHPFramework\\'.$config_class_name;
      foreach ($full_config_class_name::$routes as $route_name => $route) {
        $Router->map($route_name, $route, $module_path_name);
      }
    }

    // Select current route
    $Router->execute();

    // Start caching everything rendered.  We start this after the
    // header, since the header may contain user session information
    // that shouldn't be cached.
    $slash = $Config::get_slash();
    if ($Config::$cache_enable) {
      $cache_class_name = get_final_class_name('Cache');
      $Cache = new $cache_class_name(
        $Config::$cache_time, 
        $Config::base_path().$slash.'cache', 
        $Config::$cache_pages,
        $Config::$hash_function
      );
      $Cache->start();
    }

    $this->_custom_after_routing();
    $Module->execute();

    // View
    $view_class_name = get_final_class_name('View');
    $View = $view_class_name::singleton();
    $View->register_custom_object('Config', $Config);

    // Extracting info about where the user is headed, in order to match the
    // URL with the correct controller/action.
    $controller_name = $Router->get_controller_name();
    $module_path_name = $Router->get_module_path_name();
    if (!$module_path_name) {
      $module_path_name = CoreConfig::module_path();
    }
    else {
      $module_path_name = CoreConfig::base_module_path().$slash.$module_path_name;
    }
    $controller_file = 
      strtolower($Router->get_controller_name().$Config::$controller_postfix);
    $controller_class_name = '\\IrisPHPFramework\\'.
      $Router->get_controller_class_name().$Config::$controller_postfix;
    $action = $Router->get_action_name().$Config::$action_postfix;
    $params = $Router->get_params(); // Returns an array(...)

    $default_controller_class_name = '\\IrisPHPFramework\\'.
      ucfirst($Config::$router_default_controller).$Config::$controller_postfix;
    $default_controller_file = 
      strtolower($Config::$router_default_controller.$Config::$controller_postfix);
    if ($Router->is_route_found()) {
      if (file_exists($module_path_name.$slash.'controller'.$slash.$controller_file.'.php')) {
        require_once $module_path_name.$slash.'controller'.$slash.$controller_file.'.php';
        $$controller_name = new $controller_class_name;
        if (method_exists($$controller_name, $action)) {
          $$controller_name->$action($params);

          // Stop caching
          if ($Config::$cache_enable) {
            $Cache->end();
          }

          return;
        }
      }
    }

    require_once $Config::module_path().$slash.'controller'.$slash.
      $default_controller_file.'.php';
    $default_controller_class_name::errorAction();
  }

  /**
   * Custom handler, where possible to create database connection, 
   * template parameters, custom objects, what can be accessible in views
   */
  protected function _custom_after_routing()
  {
    // For overriding in App
  }

}

?>