<?php
namespace IrisPHPFramework;

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
 * CoreApp Class
 *
 * Staring the session and routing
 *
 * @license MIT, http://opensource.org/licenses/mit-license.php
 */

class CoreApplicationTest extends \PHPUnit_Framework_TestCase {

  /**
   * Start the user session
   */
  public function test_execute() {
    $class_config = get_final_class_name('Config');
    $class_router = get_final_class_name('Router');
    $router = $class_router::singleton();
    $router->destroy();

    ob_start();
    $_SERVER['REQUEST_URI'] = $class_config::$base_url.'/about';
    $router = $class_router::singleton();
    $app = new Application();
    ob_clean();
    $this->assertNotNull(!session_id());
    $this->assertEquals('about', $router->get_action_name());
    $router->destroy();

    ob_start();
    $_SERVER['REQUEST_URI'] = $class_config::$base_url.'/not_exists';
    $router = $class_router::singleton();
    $app = new Application();
    ob_clean();
    $this->assertNotNull(!session_id());
    $this->assertEquals(null, $router->get_action_name());
    $router->destroy();
  }

}

?>