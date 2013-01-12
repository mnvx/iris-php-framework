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
    $template = TemplateModel::singleton();

    if ($params) {
      $template->render("site", "error");
      return;
    }

    $standard = array("options");
    $proper = array("Options");
    $template->set_title(ucwords(str_replace($standard, $proper, $name)));
    $template->render("options", 'index');
  }
}

?>