<?php
namespace IrisPHPFramework;

require_once 'framework/core/config.php';
require_once 'framework/core/helpers.php';
require_once 'framework/core/controller.php';
require_once 'framework/project/controller.php';

// Do not include this file (for test_autoload())!
//require_once 'framework/project/controller/usercontroller.php';

/**
 * Configuration values
 *
 * Configuration static parameters.
 *
 * @license MIT, http://opensource.org/licenses/mit-license.php
 */

class HelpersTest extends \PHPUnit_Framework_TestCase {

  public function __construct()
  {
    if (!session_id()) {
      session_start();
    }
  }

  /**
   * Base path
   */
  public function test_autoload()
  {
    $user = new UserController();
  }

  public function test_hash_case()
  {
    $this->assertEquals(hash_case(1), 'c4ca4238a0b923820dcc509a6f75849b');
  }

}

?>