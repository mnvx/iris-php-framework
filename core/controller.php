<?php
namespace IrisPHPFramework;

/**
 * Controller
 *
 * Base Controller Class
 *
 * @license MIT, http://opensource.org/licenses/mit-license.php
 */

class CoreController {

  /**
   * Redirect the user to any page on the site.
   *
   * @param   $location  URL of where you want to return the user to.
   */
  protected function return_to($location) {
    $router = Router::singleton();
    $location = $router->_url().'/'.$location;
    header("Location: $location");
    exit();
  }

}

?>