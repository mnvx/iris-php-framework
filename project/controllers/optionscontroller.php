<?php

/**
 * Options Controller
 *
 * Used for displaying all of the simpler, static pages on the site.
 *
 * @package    jQuery Mobile PHP MVC Micro Framework
 * @author     Monji Dolon <md@devgrow.com>
 * @copyright  2011-2012 Monji Dolon
 * @license    http://www.gnu.org/licenses/gpl.html  GNU General Public License (GPL) v3
 * @link       http://devgrow.com/jquery-mobile-php-mvc-framework/
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