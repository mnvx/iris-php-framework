<?php
namespace IrisPHPFramework;

require_once 'framework/core/view.php';


require_once 'framework/core/config.php';
require_once 'framework/core/helpers.php';
require_once 'framework/core/controller.php';
require_once 'framework/core/singleton.php';
require_once 'framework/core/application.php';
require_once 'framework/project/controller.php';
require_once 'framework/project/model/user.php';


/**
 * View parameters and methods
 *
 * This class contains all of the functions used for rendering 
 * the HTML templates from the various view files.
 *
 * @license MIT, http://opensource.org/licenses/mit-license.php
 */

class CoreViewTest extends \PHPUnit_Framework_TestCase {

  public function __construct()
  {
    if (!session_id()) {
      session_start();
    }
  }

  /**
   * Register custom object to access them from views
   */
  public function test_register_custom_object()
  {
    $class_view = get_final_class_name('View');
    $view = $class_view::singleton();
    
    $object = 'testobj';
    $view->register_custom_object('testname', $object);
    
    $this->assertEquals('testobj', $view->get_custom_object('testname'));
    $this->assertNull($view->get_custom_object('testname_not_exists'));
    
    $view->destroy();
  }

  /**
   * Get file name for view
   */
  public function test_get_view_file_name() 
  {
    $class_view = get_final_class_name('View');
    $class_config = get_final_class_name('Config');

    $view = $class_view::singleton();
    
    $this->assertNull($view->get_view_file_name('model'));
    $this->assertEquals($class_config::project_path() . "/view/layout-d.html.php", 
      $view->get_view_file_name('layout'));
    $this->assertEquals($class_config::project_path() . "/view/site/about.html.php", 
      $view->get_view_file_name('site', 'about'));
    $this->assertNull($view->get_view_file_name('site'));
    
    $view->destroy();
  }

  /**
   * Set file name and controller for view
   */
  public function test_set_view_params() 
  {
    ob_start();
    $class_view = get_final_class_name('View');
    $class_config = get_final_class_name('Config');

    $view = $class_view::singleton();

    $user_model = UserModel::singleton();
    $view->register_custom_object('user', $user_model);

    $view->render('model');
    $this->assertNull($view->get_inner_file_name());

    $view->render('layout');
    $this->assertEquals($class_config::project_path() . "/view/layout-d.html.php", 
      $view->get_inner_file_name());

    $view->render('site', 'about');
    $this->assertEquals($class_config::project_path() . "/view/site/about.html.php", 
      $view->get_inner_file_name());

    $view->render('site');
    $this->assertNull($view->get_inner_file_name());

    $view->render('not_exists', 'about');
    $this->assertEquals($class_config::project_path() . "/view/site/error.html.php", 
      $view->get_inner_file_name());

    $view->destroy();
    ob_clean();
  }

  /**
   * Used to assign variables that can be used in the template files.
   */
  public function test_assign() 
  {
    $class_view = get_final_class_name('View');
    $view = $class_view::singleton();

    $object = 'testobj';
    $view->assign('testname', $object);
    $this->assertNull($view->get('not_exists'));
    $this->assertEquals($object, $view->get('testname'));

    $view->destroy();
  }

  /**
   * Used to assign the page title of the rendered HTML file.
   */
  public function test_set_title() 
  {
    $class_view = get_final_class_name('View');
    $view = $class_view::singleton();

    $view->set_title('testtitle');
    $this->assertEquals('testtitle - Iris PHP Framework', $view->page_title());

    $view->set_title('');
    $this->assertEquals('Iris PHP Framework', $view->page_title());

    $view->destroy();
  }

  /**
   * Set any status or error messages to be passed into the view files.
   */
  public function test_set_msg() 
  {
    $class_view = get_final_class_name('View');
    $view = $class_view::singleton();

    $view->set_msg('test_msg');
    $this->assertEquals("<div class=\"alert alert-error\">test_msg</div>\n", 
      $view->get_msg());

    $view->set_msg('test_msg', true);
    $this->assertEquals("<div class=\"alert alert-success\">test_msg</div>\n", 
      $view->get_msg());

    $view->set_msg(null);
    $this->assertNull($view->get_msg());

    $view->set_msg(null, true);
    $this->assertNull($view->get_msg());

    $view->destroy();
  }

  
  /**
   * Return safe for output html text
   * 
   * @param   $html       HTML text
   */
  public function test_escape()
  {
  }

}

?>