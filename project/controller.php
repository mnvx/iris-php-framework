<?php

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
  protected function login_required() {
    $user = UserModel::singleton();
    $template = TemplateModel::singleton();
    if (!$user->is_logged) {
      $template->set_msg("You must be logged in to access this section.", false);
      User::login();
      exit();
    }
  }

}

?>