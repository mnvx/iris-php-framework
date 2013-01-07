<?php

/**
 * Router Controller
 *
 * This controller and helper class route the user to wherever they need to go based on
 * the URL.
 *
 * @license MIT, http://opensource.org/licenses/mit-license.php
 */

class Router {
  use Singleton;

  private $request_uri;
  private $routes;
  private $controller, $controller_name;
  private $action;
  private $params;
  private $route_found = false;
  private $current_route_index = -1;
  
  private $url_prefix;
  private $url_prefix_array;

  public function get_url_prefix($param)
  {
    if (array_key_exists($param, $this->url_prefix_array)) {
      return $this->url_prefix_array[$param];
    }
    return Config::$url_prefix_format[$param]['default'];
  }

  /**
   * Get the base url with url prefix
   */
  function _url($param = null, $value = null, $full = false)
  {
    if ($param == null) {
      return Config::$base_url.$this->url_prefix.($full ? $this->request_uri : '');
    }
    $url = Config::$base_url;
    foreach (Config::$url_prefix_format as $prefix_name => $prefix_value) {
      if (array_key_exists($prefix_name, $this->url_prefix_array)) {
        if ($prefix_name != $param) {
          $url .= '/'.$this->url_prefix_array[$prefix_name];
        }
        elseif ($prefix_value['default'] != $value) {
          $url .= '/'.$value;
        }
      }
      elseif ($prefix_name == $param && $prefix_value['default'] != $value) {
        $url .= '/'.$value;
      }
    }
    return $url.($full ? $this->request_uri : '');
  }

  function get_controller()
  {
    return $this->controller;
  }

  function get_controller_name()
  {
    return $this->controller_name;
  }

  function get_action()
  {
    return $this->action;
  }

  function get_params()
  {
    return $this->params;
  }

  function is_route_found()
  {
    return $this->route_found;
  }

  public function __construct()
  {
    $request = $_SERVER['REQUEST_URI'];
    $pos = strpos($request, '?');
    if ($pos) {
      $request = substr($request, 0, $pos);
    }

    //Учтём base_url
    if (Config::$base_url) {
      $urllen = strlen($request)-strlen(Config::$base_url)-1;
      $request = substr($request, -$urllen, $urllen);
    }
    $request_array = explode("/", $request);

    //Если в url не прописаны параметры по умолчанию (язык и формат), то укажем их в url, 
    //который передадим далее в контроллер
    $template = TemplateModel::singleton();
    $this->url_prefix = '';
    $this->url_prefix_array = array();
    foreach (Config::$url_prefix_format as $param => $format) {
      if (array_key_exists(0, $request_array) && preg_match($format['mask'], $request_array[0])) {
        $template->assign($param, $request_array[0]);
        $this->url_prefix .= '/'.$request_array[0];
        $this->url_prefix_array[$param] = $request_array[0];
        array_shift($request_array);
      }
      else { 
        $template->assign($param, $format['default']);
      }
    }
    $request = implode('/', $request_array);

    $this->request_uri = ($request ? '/' : '').$request;
    $this->routes = array();
  }

  public function map($name, $route)
  {
    $this->routes[$name] = new Route($route, $this->request_uri);
    if ($this->routes[$name]->is_matched()) {
      $this->route_found = true;
    }
  }

  private function set_route($route)
  {
    $params = $route->params;
    
    $this->controller = $params['controller'];
    unset($params['controller']);
    
    if (array_key_exists('action', $params)) {
      $this->action = $params['action']; 
      unset($params['action']);
    }
    else {
      $this->action = null; 
    }
    
    $this->params = $params;

    if (empty($this->controller)) {
      $this->controller = Config::$router_default_controller;
    }
    if (empty($this->action)) {
      $this->action = Config::$router_default_action;
    }

    $w = explode('_', $this->controller);
    foreach($w as $k => $v) {
      $w[$k] = ucfirst($v);
    }
    $this->controller_name = implode('', $w);
  }

  public function execute()
  {
    foreach ($this->routes as $key => $route) {
      if ($route->is_matched()) {
        $this->set_route($route);
        $this->current_route_index = $key;
        break;
      }
    }
  }

  public function get_current_route()
  {
    return $this->routes[$this->current_route_index];
  }

  public function get_current_route_name()
  {
    return $this->current_route_index;
  }

}
 
class Route {
  private $is_matched = false;
  public $params;
  private $conditions;
  private $route;

  function __construct($route, $request_uri) {
    $this->route = $route;
    $url = $route['pattern'] == '/' ? '' : $route['pattern'];
    $this->params = array();
    $this->conditions = array_key_exists('requirements', $route) ? $route['requirements'] : null;
    $p_names = array(); 
    $p_values = array();

    //Извлекаем из шаблона url названия параметров
    preg_match_all('#{([\w]+)}#', $url, $p_names, PREG_PATTERN_ORDER);

    $url_regex = preg_replace_callback('#{([\w]+)}#', function ($match) {
      $key = $match[1];
      if (array_key_exists($key, $this->conditions)) {
        return '('.$this->conditions[$key].')';
      } 
      else {
        return '([a-zA-Z0-9_\+\-%]+)';
      }
    }, $url);
    $url_regex .= '/?';

    if (preg_match('#^' . $url_regex . '$#', $request_uri, $p_values)) {
      array_shift($p_values);
      foreach ($p_names[1] as $index => $value) {
        $this->params[$value] = urldecode($p_values[$index]);
      }
      if (array_key_exists('controller', $route)) {
        $this->params['controller'] = $route['controller'];
      }
      if (array_key_exists('action', $route)) {
        $this->params['action'] = $route['action'];
      }
      $this->is_matched = true;
    }
  }
  
  public function is_matched()
  {
    return $this->is_matched;
  }

  public function get_route()
  {
    return $this->route;
  }

}

?>