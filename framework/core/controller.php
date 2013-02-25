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
  protected function _redirect_to($location) {
    $class_router = get_final_class_name('Router');
    $router = $class_router::singleton();
    $location = $router->prefix_url().'/'.$location;
    header("Location: $location");
    exit();
  }

}

?>