<?php
namespace IrisPHPFramework;

require_once 'framework/core/config.php';
require_once 'framework/core/helpers.php';
require_once 'framework/core/singleton.php';
require_once 'framework/core/router.php';
require_once 'framework/core/view.php';

require_once 'framework/core/application.php';
require_once 'framework/core/controller.php';
require_once 'framework/core/debug.php';
require_once 'framework/project/model/user.php';

/**
 * Configuration values
 *
 * Configuration static parameters.
 *
 * @license MIT, http://opensource.org/licenses/mit-license.php
 */

class CoreConfigTest extends \PHPUnit_Framework_TestCase {

  public function __construct()
  {
    if (!session_id()) {
      session_start();
    }
  }

  /**
   * Base path
   */
  public function test_base_path()
  {
    $class_config = 'IrisPHPFramework\\CoreConfig';
    $this->assertEquals($class_config::base_path(),
      str_replace('Test', '', dirname(dirname(__FILE__))));
  }

  /**
   * Core path
   */
  public function test_core_path()
  {
    $class_config = 'IrisPHPFramework\\CoreConfig';
    $this->assertEquals($class_config::core_path(),
      str_replace('Test', '', dirname(__FILE__)));
  }

  /**
   * Solution path
   */
  public function test_solution_path()
  {
    $class_config = 'IrisPHPFramework\\CoreConfig';
    $this->assertEquals($class_config::solution_path(),
      str_replace('Test', '', 
        dirname(dirname(__FILE__))).$class_config::get_slash().'solution');
  }

  /**
   * Project path
   */
  public function test_project_path()
  {
    $class_config = 'IrisPHPFramework\\CoreConfig';
    $this->assertEquals($class_config::project_path(),
      str_replace('Test', '', 
        dirname(dirname(__FILE__))).$class_config::get_slash().'project');
  }

  /**
   * Set locale
   */
  public function test_set_locale() 
  {
    $class_config = 'IrisPHPFramework\\CoreConfig';

    $class_config::set_locale('ru');
    $this->assertEquals(_('User'), 'Пользователь');

/*
    $class_config::set_locale('de');
    $this->assertEquals(_('User'), 'Benutzer');
*/

    $class_config::set_locale('not_exists');
    $this->assertEquals(_('User'), 'Пользователь');

/*
    $class_config::set_locale('en');
    $this->assertEquals('User', _('User'));
*/
  }

}

?>