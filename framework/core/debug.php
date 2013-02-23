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

  protected $start_time;
  protected $intermediate_time;
  protected $log = array();

  public function __construct()
  {
    $this->start_time = microtime(true);
    $this->intermediate_time = microtime(true);
    $this->log('Start');
  }

  /**
  * Return duration in seconds
  * @param float $time - start time
  * @param boolean $format - false (float), true - round (2 digits)
  */
  protected function calc_duration($time, $format = true)
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
    $this->intermediate_time = microtime(true);
  }

  /**
  * Return intermediate time
  * @param boolean $format - false (float), true - round (2 digits)
  */
  public function get_intermediate_time($format = true)
  {
    return $this->calc_duration($this->intermediate_time, $format);
  }

  /**
  * Return total time
  * @param boolean $format - false (float), true - round (2 digits)
  */
  public function get_total_time($format = true)
  {
    return $this->calc_duration($this->start_time, $format);
  }

  /**
  * Log an message
  * @param string $message - information for log
  * @param boolean $format - false (float), true - round (2 digits)
  */
  public function log($message, $format = true)
  {
    $count = count($this->log);
    $this->log[$count]['time'] = $this->get_total_time($format);
    $this->log[$count]['message'] = $message;
  }

  /**
  * Return debug log info
  */
  public function log_info()
  {
    return $this->log;
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
    $current_route = $class_router::singleton()->get_current_route();
    $route = $current_route != null ? $current_route->get_route() : array();
    return $route;
  }

}

?>