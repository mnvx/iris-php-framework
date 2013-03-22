<?php
namespace IrisPHPFramework;

/**
 * Options Controller
 *
 * Used for displaying all of the simpler, static pages on the site.
 */

class OptionsController extends ProjectController {

    /**
     * Loads a particular page from the 'site' directory in views
     *
     * @param   $name   The name of the page to load (should match filename)
     */
  public static function indexAction($params = null)
  {
    $view_class_name = get_final_class_name('View');
    $View = $view_class_name::singleton();

    if ($params) {
      $View->render(CoreModule::singleton()->get_class_path_name(__CLASS__),
        "site", "error");
      return;
    }

    $standard = array("options");
    $proper = array("Options");
    $View->set_title(ucwords('options'));
    $View->render(CoreModule::singleton()->get_class_path_name(__CLASS__), 
      "options", 'index');
  }
}

?>