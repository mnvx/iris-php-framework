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
    $class_config = get_final_class_name('Config');
    $class_router = get_final_class_name('Router');

    // Get the Router object
    $r = $class_router::singleton();

    // Configure the routes, where the user should go when they access the
    // specified URL structures.
    foreach ($class_config::$routes as $name => $route) {
      $r->map($name, $route);
    }

    // Select current route
    $r->execute();

    // Start caching everything rendered.  We start this after the
    // header, since the header may contain user session information
    // that shouldn't be cached.
    if ($class_config::$cache_enable) {
      $class_cache = get_final_class_name('Cache');
      $cache = new $class_cache(
        $class_config::$cache_time, 
        $class_config::base_path().$class_config::get_slash().'cache', 
        $class_config::$cache_pages,
        $class_config::$hash_function
      );
      $cache->start();
    }

    $this->custom_after_routing();

    // Extracting info about where the user is headed, in order to match the
    // URL with the correct controller/action.
    $controller = $r->get_controller_name();
    $controller_file = strtolower($r->get_controller_name().$class_config::$controller_postfix);
    $controller_class = '\\IrisPHPFramework\\'.$r->get_controller_class_name().$class_config::$controller_postfix;
    $action = $r->get_action_name().$class_config::$action_postfix;
    $params = $r->get_params(); // Returns an array(...)

    $default_controller_class_name = '\\IrisPHPFramework\\'.ucfirst($class_config::$router_default_controller).$class_config::$controller_postfix;
    $default_controller_file = strtolower($class_config::$router_default_controller.$class_config::$controller_postfix);
    if ($r->is_route_found()) {
      if (file_exists($class_config::project_path().$class_config::get_slash().'controller'.$class_config::get_slash().$controller_file.'.php')) {
        require_once $class_config::project_path().$class_config::get_slash().'controller'.$class_config::get_slash().$controller_file.'.php';
        $$controller = new $controller_class;
        if (method_exists($$controller, $action)) {
          $$controller->$action($params);

          // Stop caching
          if ($class_config::$cache_enable) {
            $cache->end();
          }
          
          return;
        }
      }
    }
    
    require_once $class_config::project_path().$class_config::get_slash().
      'controller'.$class_config::get_slash().
      $default_controller_file.'.php';
    $default_controller_class_name::errorAction();
  }

  /**
   * Custom handler, where possible to create database connection, 
   * template parameters, custom objects, what can be accessible in views
   */
  protected function custom_after_routing()
  {
    // For overriding in App
  }

}

?>