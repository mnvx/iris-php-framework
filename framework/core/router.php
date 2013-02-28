<?php
namespace IrisPHPFramework;

/**
 * Router
 *
 * Routing.
 *
 * @license MIT, http://opensource.org/licenses/mit-license.php
 */

class CoreRouter {
  use Singleton;

  protected $_request_uri;
  // Route object array
  protected $_routes;
  // Current controller name in format "options" or "my_controller"
  protected $_controller_name;
  // Current controller name in format "Options" or "MyController"
  protected $_controller_name_ucfirst;
  // Current action name
  protected $_action_name;
  // Current route module path name
  protected $_module_path_name;
  // Current route name
  protected $_current_route_name = null;
  
  // Part of current url, what contains parameters from CoreConfig::$url_prefix_format
  protected $url_prefix;
  // Associative array with parameters from current url
  protected $url_prefix_array;

  public function __construct()
  {
    $class_config = get_final_class_name('Config');
    $request = array_key_exists('REQUEST_URI', $_SERVER) ? $_SERVER['REQUEST_URI'] : '';
    $pos = strpos($request, '?');
    if ($pos) {
      $request = substr($request, 0, $pos);
    }

    //Учтём base_url
    if ($class_config::$base_url) {
      $urllen = strlen($request)-strlen($class_config::$base_url)-1;
      $request = substr($request, -$urllen, $urllen);
    }
    $request_array = explode("/", $request);

    //Если в url не прописаны параметры по умолчанию (язык и формат), то укажем их в url, 
    //который передадим далее в контроллер
    $class_view = get_final_class_name('View');
    $view = $class_view::singleton();
    $this->url_prefix = '';
    $this->url_prefix_array = array();
    foreach ($class_config::$url_prefix_format as $param => $format) {
      if (array_key_exists(0, $request_array) && preg_match($format['mask'], $request_array[0])) {
        $view->assign($param, $request_array[0]);
        $this->url_prefix .= '/'.$request_array[0];
        $this->url_prefix_array[$param] = $request_array[0];
        array_shift($request_array);
      }
      else {
        $view->assign($param, $format['default']);
      }
    }
    $request = implode('/', $request_array);

    $this->_request_uri = ($request ? '/' : '').$request;
    $this->_routes = array();
  }
  
  /**
   * Get value of parameter in prefix of url.
   * Link prefix format: ([/format][/language]/...) - order is important.
   * Full URL format: [/URL prefix]/[request_uri]
   *
   * @param $param_name Name of parameter
   */
  public function get_url_prefix_param_value($param_name)
  {
    $class_config = get_final_class_name('Config');
    if (array_key_exists($param_name, $this->url_prefix_array)) {
      return $this->url_prefix_array[$param_name];
    }
    return $class_config::$url_prefix_format[$param_name]['default'];
  }

  /**
   * Get the base url with url prefix
   *
   * @param string $param_name Name of parameter (language or display format), what need to specify
   * @param $param_value Parameter's value
   * @param boolean $current Return full current link wint specified params
   */
  public function prefix_url($params = null, $current = false)
  {
    $class_config = get_final_class_name('Config');
    if ($params == null) {
      return $class_config::$base_url.$this->url_prefix.($current ? $this->_request_uri : '');
    }
    $url = $class_config::$base_url;
    foreach ($class_config::$url_prefix_format as $prefix_name => $prefix_value) {
      //Try to get default URL parameters from current url
      if (array_key_exists($prefix_name, $this->url_prefix_array)) {
        if (!array_key_exists($prefix_name, $params)) {
          $url .= '/'.$this->url_prefix_array[$prefix_name];
        }
        elseif ($prefix_value['default'] != $params[$prefix_name]) {
          $url .= '/'.$params[$prefix_name];
        }
      }
      elseif (array_key_exists($prefix_name, $params) 
      && $prefix_value['default'] != $params[$prefix_name]) {
        $url .= '/'.$params[$prefix_name];
      }
    }
    return $url.($current ? $this->_request_uri : '');
  }
  
  /**
   * Get the url by route name
   *
   * @param string $route_name Route's name
   * @param array $url_params Values of url parameters in route's pattern
   * @param array $prefix_params Values of prefix parameters
   */
  public function url($route_name, $url_params = array(), $prefix_params = null)
  {
    if (array_key_exists($route_name, $this->_routes)) {
      $route = $this->_routes[$route_name]->get_route();
      $pattern = $route['pattern'];
      foreach ($url_params as $key => $value) {
        $pattern = str_replace('{'.$key.'}', $value, $pattern);
      }
      return $this->prefix_url($prefix_params).$pattern;
    }
    return false;
  }

  /**
   * Get current controller name in format "options" or "my_controller"
   */
  public function get_controller_name()
  {
    return $this->_controller_name;
  }

  /**
   * Get current controller class name in format (Oprions or MyController)
   */
  public function get_controller_class_name()
  {
    return $this->_controller_name_ucfirst;
  }

  /**
   * Get current controller action name
   */
  public function get_action_name()
  {
    return $this->_action_name;
  }

  /**
   * Get current routing module path name
   */
  public function get_module_path_name()
  {
    return $this->_module_path_name;
  }

  /**
   * Get current parameters from route (parsed from {})
   * @return array|null Return current route parameters
   */
  public function get_params()
  {
    if (array_key_exists($this->_current_route_name, $this->_routes)) {
      return $this->_routes[$this->_current_route_name]->get_params();
    }
    return null;
  }

  /**
   * Get sign - is found the route for current url or not
   */
  public function is_route_found()
  {
    return $this->_current_route_name != null;
  }

  /**
   * Add route info to route parser and check - is it current route?
   */
  public function map($name, $route, $module_path_name = null)
  {
    $class_route = get_final_class_name('Route');
    $this->_routes[$name] = new $class_route($route, $this->_request_uri, $module_path_name);
    if ($this->_routes[$name]->is_matched()) {
      $this->_current_route_name = $name;
      $this->_module_path_name = $module_path_name;
    }
  }

  /**
   * Execute current route if route was found
   */
  public function execute()
  {
    if (array_key_exists($this->_current_route_name, $this->_routes)) {
      $this->_set_route($this->_routes[$this->_current_route_name]);
    }
  }

  /**
   * Get current route object
   * @return Route|null Route object
   */
  public function get_current_route()
  {
    if (array_key_exists($this->_current_route_name, $this->_routes)) {
      return $this->_routes[$this->_current_route_name];
    }
    return null;
  }

  /**
   * Get current route name
   * @return string|null Current route name or null if route was not found
   */
  public function get_current_route_name()
  {
    return $this->_current_route_name;
  }

  /**
   * Set current route parameters
   * @param class::Route Route object
   */
  protected function _set_route($route)
  {
    $class_config = get_final_class_name('Config');

    if ($route) {
      $this->_controller_name = $route->get_controller_name();
      $this->_action_name = $route->get_action_name();    
      $this->_module_path_name = $route->get_module_path_name();
    }
  
    // Not defined controller or action in route
    if (empty($this->_controller_name) || empty($this->_action_name)) {
      $this->_controller_name = $class_config::$router_default_controller;
      $this->_action_name = $class_config::$router_default_action;
      $this->_module_path_name = null;
    }

    $w = explode('_', $this->_controller_name);
    foreach($w as $k => $v) {
      $w[$k] = ucfirst($v);
    }
    $this->_controller_name_ucfirst = implode('', $w);
  }

}

?>