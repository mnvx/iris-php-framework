<?php
namespace IrisPHPFramework;

$test = true;
require_once 'framework/core/config.php';
require_once 'framework/core/index.php';


/**
 * Controller
 *
 * Base Controller Class
 *
 * @license MIT, http://opensource.org/licenses/mit-license.php
 */

class CoreControllerTest extends \PHPUnit_Framework_TestCase {

  public function __construct()
  {
    if (!session_id()) {
      session_start();
    }
  }

  /**
   * Redirect the user to any page on the site.
   *
   * @param   $location  URL of where you want to return the user to.
   */
  public function test_redirect_to() {
  /*
    $user_model = UserModel::singleton();
    $class_view = get_final_class_name('View');
    $view = $class_view::singleton();
    $view->register_custom_object('user', $user_model);

    $user = new UserController();
    $user->editAction();    
    */
  }

}

?>