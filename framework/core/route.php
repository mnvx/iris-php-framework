<?php
namespace IrisPHPFramework;

/**
 * Route
 *
 * @license MIT, http://opensource.org/licenses/mit-license.php
 */

class CoreRoute {
  // Array from configuration with info about route
  protected $_route;
  // Parsed URL parameters (from {})
  protected $_params;
  // Is current URL matched to this route?
  protected $_is_matched = false;
  // Controller name
  protected $_controller_name;
  // Action name
  protected $_action_name;
  // Module path name
  protected $_module_path_name;

  /**
   * Route constructor
   *
   * @param $route Array from configuration with info about route
   * @param $request_uri Current URL
   */
  function __construct($route, $request_uri, $module_path_name = null) {
    $this->_route = $route;
    $url = '';
    if (isset($route['pattern'])) {
      $url = $route['pattern'] == '/' ? '' : $route['pattern'];
    }
    $this->_params = array();
    $this->conditions = isset($route['requirements']) 
      ? $route['requirements'] 
      : null;
    $p_names = array(); 
    $p_values = array();

    // Get parameter names from url template
    preg_match_all('#{([\w]+)}#', $url, $p_names, PREG_PATTERN_ORDER);

    // Get url template in regex format
    $url_regex = preg_replace_callback('#{([\w]+)}#', function ($match) {
      $key = $match[1];
      if (isset($this->conditions[$key])) {
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
        $this->_params[$value] = urldecode($p_values[$index]);
      }
      if (isset($route['controller'])) {
        $this->_controller_name = $route['controller'];
      }
      if (isset($route['action'])) {
        $this->_action_name = $route['action'];
      }
      if ($module_path_name) {
        $this->_module_path_name = $module_path_name;
      }
      $this->_is_matched = true;
    }
  }
  
  /**
   * Check route for matching with request_url
   *
   * @result boolean
   */
  public function is_matched()
  {
    return $this->_is_matched;
  }

  /**
   * Get route information
   *
   * @result array
   */
  public function get_route()
  {
    return $this->_route;
  }

  /**
   * Get route parameters, what contains in {}
   *
   * @result array
   */
  public function get_params()
  {
    return $this->_params;
  }

  /**
   * Get controller name for this route
   *
   * @result string
   */
  public function get_controller_name()
  {
    return $this->_controller_name;
  }

  /**
   * Get controller action name for this route
   *
   * @result string
   */
  public function get_action_name()
  {
    return $this->_action_name;
  }

  /**
   * Get module path name, where realised processing of the route
   *
   * @result string
   */
  public function get_module_path_name()
  {
    return $this->_module_path_name;
  }

}

?>