<?php
namespace IrisPHPFramework;

/**
 * CoreDebug Class
 *
 * Tools for debugging
 *
 * @license MIT, http://opensource.org/licenses/mit-license.php
 */

class CoreDebug {

  use Singleton;

  protected $_start_time;
  protected $_intermediate_time;
  protected $_log = array();

  public function __construct()
  {
    $this->_start_time = microtime(true);
    $this->_intermediate_time = microtime(true);
    $this->log('Start');
  }

  /**
  * Return duration in seconds
  * @param float $time - start time
  * @param boolean $format - false (float), true - round (2 digits)
  */
  protected function _calc_duration($time, $format = true)
  {
    $result_time = microtime(true) - $time;
    if ($format) {
      $result_time = round($result_time, 2);
    }
    return $result_time;
  }

  /**
  * Reset intermediate time (set current)
  */
  public function reset_intermediate_time()
  {
    $this->_intermediate_time = microtime(true);
  }

  /**
  * Return intermediate time
  * @param boolean $format - false (float), true - round (2 digits)
  */
  public function get_intermediate_time($format = true)
  {
    return $this->_calc_duration($this->_intermediate_time, $format);
  }

  /**
  * Return total time
  * @param boolean $format - false (float), true - round (2 digits)
  */
  public function get_total_time($format = true)
  {
    return $this->_calc_duration($this->_start_time, $format);
  }

  /**
  * Log an message
  * @param string $message - information for log
  * @param boolean $format - false (float), true - round (2 digits)
  */
  public function log($message, $format = true)
  {
    $count = count($this->_log);
    $this->_log[$count]['time'] = $this->get_total_time($format);
    $this->_log[$count]['message'] = $message;
  }

  /**
  * Return debug log info
  */
  public function log_info()
  {
    return $this->_log;
  }
  
  /**
  * Return current route name
  */
  public function route_name()
  {
    $class_router = get_final_class_name('Router');
    return $class_router::singleton()->get_current_route_name();
  }

  /**
  * Return current route info
  */
  public function route_info()
  {
    $class_router = get_final_class_name('Router');
    $CurrentRoute = $class_router::singleton()->get_current_route();
    $route = $CurrentRoute != null ? $CurrentRoute->get_route() : array();
    return $route;
  }

}

?>