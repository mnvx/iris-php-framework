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
    $template = TemplateModel::singleton();

    if ($params && count($params)>0) {
      $template->render("site", "error");
      return;
    }

    $standard = array("faq", "terms", "about");
    $proper = array("Frequently Asked Questions", "Terms of Service", "About Us");
    $template->set_title(ucwords(str_replace($standard, $proper, $name)));
    $template->render("site", $name);
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