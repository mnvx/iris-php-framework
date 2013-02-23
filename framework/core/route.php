<?php
namespace IrisPHPFramework;

/**
 * Route
 *
 * @license MIT, http://opensource.org/licenses/mit-license.php
 */

class CoreRoute {
  // Array from configuration with info about route
  protected $route;
  // Parsed URL parameters (from {})
  protected $params;
  // Is current URL matched to this route?
  protected $is_matched = false;
  // Controller name
  protected $controller_name;
  // Action name
  protected $action_name;

  /**
   * Route constructor
   *
   * @param $route Array from configuration with info about route
   * @param $request_uri Current URL
   */
  function __construct($route, $request_uri) {
    $this->route = $route;
    $url = '';
    if (array_key_exists('pattern', $route)) {
      $url = $route['pattern'] == '/' ? '' : $route['pattern'];
    }
    $this->params = array();
    $this->conditions = array_key_exists('requirements', $route) 
      ? $route['requirements'] 
      : null;
    $p_names = array(); 
    $p_values = array();

    // Get parameter names from url template
    preg_match_all('#{([\w]+)}#', $url, $p_names, PREG_PATTERN_ORDER);

    // Get url template in regex format
    $url_regex = preg_replace_callback('#{([\w]+)}#', function ($match) {
      $key = $match[1];
      if (array_key_exists($key, $this->conditions)) {
        return '('.$this->conditions[$key].')';
      } 
      else {
        return '([a-zA-Z0-9_\+\-%]+)';
      }
    }, $url);
    $url_regex = '#^'.$url_regex.'/?$#';

    // Check what request_url is matching to url template
    if (preg_match($url_regex, $request_uri, $p_values)) {
      array_shift($p_values);
      foreach ($p_names[1] as $index => $value) {
        $this->params[$value] = urldecode($p_values[$index]);
      }
      if (array_key_exists('controller', $route)) {
        $this->controller_name = $route['controller'];
      }
      if (array_key_exists('action', $route)) {
        $this->action_name = $route['action'];
      }
      $this->is_matched = true;
    }
  }
  
  /**
   * Check route for matching with request_url
   *
   * @result boolean
   */
  public function is_matched()
  {
    return $this->is_matched;
  }

  /**
   * Get route information
   *
   * @result array
   */
  public function get_route()
  {
    return $this->route;
  }

  /**
   * Get route parameters, what contains in {}
   *
   * @result array
   */
  public function get_params()
  {
    return $this->params;
  }

  /**
   * Get controller name for this route
   *
   * @result string
   */
  public function get_controller_name()
  {
    return $this->controller_name;
  }

  /**
   * Get controller action name for this route
   *
   * @result string
   */
  public function get_action_name()
  {
    return $this->action_name;
  }

}

?>