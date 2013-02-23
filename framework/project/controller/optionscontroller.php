<?php
namespace IrisPHPFramework;

/**
 * Options Controller
 *
 * Used for displaying all of the simpler, static pages on the site.
 */

class OptionsController extends Controller {

    /**
     * Loads a particular page from the 'site' directory in views
     *
     * @param   $name   The name of the page to load (should match filename)
     */
  public static function indexAction($params = null)
  {
    $class_view = get_final_class_name('View');
    $view = $class_view::singleton();

    if ($params) {
      $view->render("site", "error");
      return;
    }

    $standard = array("options");
    $proper = array("Options");
    $view->set_title(ucwords('options'));
    $view->render("options", 'index');
  }
}

?>