<?php
namespace IrisPHPFramework;

/**
 * Site Controller
 *
 * Used for displaying all of the simpler, static pages on the site.
 */

class SiteController extends Controller {

  private static function renderPage($name, $params)
  {
    $class_view = get_final_class_name('View');
    $view = $class_view::singleton();

    if ($params && count($params)>0) {
      $view->render("site", "error");
      return;
    }

    $standard = array("faq", "terms", "about");
    $proper = array("Frequently Asked Questions", "Terms of Service", "About Us");
    $view->set_title(ucwords(str_replace($standard, $proper, $name)));
    $view->render("site", $name);
  }

  public function homeAction($params = null)
  {
    $this->renderPage('home', $params);
  }

  public function aboutAction($params = null)
  {
    $this->renderPage('about', $params);
  }

  public function termsAction($params = null)
  {
    $this->renderPage('terms', $params);
  }

  public static function errorAction($params = null)
  {
    static::renderPage('error', $params);
  }

}

?>