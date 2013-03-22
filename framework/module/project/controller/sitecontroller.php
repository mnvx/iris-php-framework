<?php
namespace IrisPHPFramework;

/**
 * Site Controller
 *
 * Used for displaying all of the simpler, static pages on the site.
 */

class SiteController extends ProjectController {

  private static function renderPage($name, $params)
  {
    $view_class_name = get_final_class_name('View');
    $View = $view_class_name::singleton();

    if ($params && count($params)>0) {
      $View->render(CoreModule::singleton()->get_class_path_name(__CLASS__), 
        "site", "error");
      return;
    }

    $standard = array("faq", "terms", "about");
    $proper = array("Frequently Asked Questions", "Terms of Service", "About Us");
    $View->set_title(ucwords(str_replace($standard, $proper, $name)));
    $View->render(CoreModule::singleton()->get_class_path_name(__CLASS__), 
      "site", $name);
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