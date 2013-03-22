<?php
namespace IrisPHPFramework;

/**
 * Controller
 *
 * Basic Controller Class
 *
 * @license MIT, http://opensource.org/licenses/mit-license.php
 */

class ProjectController extends CoreController {

  /**
   * Check to see if user is logged in and if not, redirect them to the login page.
   * If they're logged in, let them proceed.
   */
  protected function login_required() 
  {
    $User = UserModel::singleton();
    $view_class_name = get_final_class_name('View');
    $View = $view_class_name::singleton();
    if (!$User->is_logged()) {
      $View->set_msg("You must be logged in to access this section.", false);
      UserController::loginAction();
      exit();
    }
  }

}

?>