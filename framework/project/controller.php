<?php
namespace IrisPHPFramework;

/**
 * Controller
 *
 * Basic Controller Class
 *
 * @license MIT, http://opensource.org/licenses/mit-license.php
 */

class Controller extends CoreController {

  /**
   * Check to see if user is logged in and if not, redirect them to the login page.
   * If they're logged in, let them proceed.
   */
  protected function login_required() 
  {
    $user = UserModel::singleton();
    $class_view = get_final_class_name('View');
    $view = $class_view::singleton();
    if (!$user->is_logged()) {
      $view->set_msg("You must be logged in to access this section.", false);
      UserController::loginAction();
      exit();
    }
  }

}

?>