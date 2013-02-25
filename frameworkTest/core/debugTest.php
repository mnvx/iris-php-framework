<?php
namespace IrisPHPFramework;

//require_once 'framework/core/singleton.php';
require_once 'framework/core/debug.php';
//require_once 'framework/core/router.php';

require_once 'framework/core/singleton.php';
require_once 'framework/core/view.php';
require_once 'framework/core/config.php';
require_once 'framework/core/helpers.php';
require_once 'framework/core/controller.php';
require_once 'framework/core/singleton.php';
require_once 'framework/core/application.php';
require_once 'framework/core/db.php';
require_once 'framework/project/application.php';
require_once 'framework/project/controller.php';
require_once 'framework/project/db.php';
require_once 'framework/project/model/user.php';


/**
 * CoreDebug Class
 *
 * Tools for debugging
 *
 * @license MIT, http://opensource.org/licenses/mit-license.php
 */

class CoreDebugTest extends \PHPUnit_Framework_TestCase {

  public function __construct()
  {
    if (!session_id()) {
      session_start();
    }
 }

  /**
  * Reset intermediate time (set current)
  */
  public function test_reset_intermediate_time()
  {
    $debug = CoreDebug::singleton();

    $time = microtime(true);
    $debug->reset_intermediate_time();
    $timeDebug = $debug->get_intermediate_time(false);
    $timeTest = microtime(true) - $time;
    $this->assertLessThanOrEqual($timeTest, $timeDebug);

    $time = microtime(true);
    $debug->reset_intermediate_time();
    $timeDebug = $debug->get_intermediate_time();
    $timeTest = round(microtime(true) - $time, 2);
    $this->assertLessThanOrEqual($timeTest, $timeDebug);
  }

  /**
  * Return total time
  */
  public function test_get_total_time()
  {
    $debug = CoreDebug::singleton();
    $time = microtime(true);
    $debug->reset_intermediate_time();
    $timeTest = microtime(true) - $time;
    $this->assertGreaterThanOrEqual($timeTest, $debug->get_total_time(false));
  }

  /**
  * Log an message
  */
  public function test_log()
  {
    $debug = CoreDebug::singleton();
    $debug->log('test');
    $log = $debug->log_info();
    $this->assertArrayHasKey(0, $log);
    $this->assertEquals('test', $log[count($log)-1]['message']);
  }
  
  /**
  * Return current route name
  */
  public function test_route_name()
  {
    $class_config = get_final_class_name('Config');
    $class_router = get_final_class_name('Router');
    $router = $class_router::singleton();
    $router->destroy();

    $debug = CoreDebug::singleton();
    $this->assertEquals(null, $debug->route_name());
    
    ob_start();
    $_SERVER['REQUEST_URI'] = $class_config::$base_url.'/not_exists';
    $router = $class_router::singleton();
    $app = new Application();
    ob_clean();
    $this->assertEquals('home', $debug->route_name());
    $router->destroy();    
    
    ob_start();
    $_SERVER['REQUEST_URI'] = $class_config::$base_url.'/about';
    $router = $class_router::singleton();
    $app = new Application();
    ob_clean();
    $this->assertEquals('about', $debug->route_name());
    $router->destroy();    
  }

  /**
  * Return current route info
  */
  public function route_info()
  {
    $debug = CoreDebug::singleton();
    $this->assertEquals(null, $debug->route_info());
  }

}

?>