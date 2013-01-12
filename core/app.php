<?php

/**
 * CoreApp Class
 *
 * Staring the session and routing
 *
 * @license MIT, http://opensource.org/licenses/mit-license.php
 */

class CoreApp {

  /**
   * Main constructor
   */
  public function __construct() {
    // Start the session
    $this->session_start();
    
    // Routing
    $this->router();
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
  public function router() {

    // Get the Router object
    $r = Router::singleton();

    // Configure the routes, where the user should go when they access the
    // specified URL structures.
    foreach (Config::$routes as $name => $route) {
      $r->map($name, $route);
    }

    // Select current route
    $r->execute();

    // Start caching everything rendered.  We start this after the
    // header, since the header may contain user session information
    // that shouldn't be cached.
    if (Config::$cache_enable) {
      $cache = Cache::singleton();
      $cache->start();
    }

    $this->custom_after_routing();

    // Extracting info about where the user is headed, in order to match the
    // URL with the correct controller/actions.
    $controller = $r->get_controller();
    $controller_file = strtolower($r->get_controller().Config::$controller_postfix);
    $controller_class = $r->get_controller_name().Config::$controller_postfix;
    $action = $r->get_action().Config::$action_postfix;
    $params = $r->get_params(); // Returns an array(...)
    $matched = $r->is_route_found(); // Bool, where True is if a route was found.

    $default_controller_class_name = ucfirst(Config::$router_default_controller).Config::$controller_postfix;
    if ($matched) {
      if (file_exists(Config::lib_dir().'/controller/'.$controller_file.'.php')) {
        $$controller = new $controller_class;
        if (method_exists($$controller, $action)) {
          $$controller->$action($params);

          // Stop caching
          if (Config::$cache_enable) {
            $cache->end();
          }

        }
        else {
          $default_controller_class_name::errorAction();
        }
      }
      else {
        $default_controller_class_name::errorAction();
      }
    }
    else {
     $default_controller_class_name::errorAction();
    }
  }

  protected function custom_after_routing()
  {
    // For overriding in App
  }

}

?>