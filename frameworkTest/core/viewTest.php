<?php
namespace IrisPHPFramework;

$test = true;
require_once 'framework/core/config.php';
require_once 'framework/core/index.php';


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
    $View = $class_view::singleton();
    
    $object = 'testobj';
    $View->register_custom_object('testname', $object);
    
    $this->assertEquals('testobj', $View->get_custom_object('testname'));
    $this->assertNull($View->get_custom_object('testname_not_exists'));
    
    $View->destroy();
  }

  /**
   * Get file name for view
   */
  public function test_get_view_file_name() 
  {
    $class_view = get_final_class_name('View');
    $Config = get_final_class_name('Config');

    $View = $class_view::singleton();
    
    $this->assertNull($View->get_view_file_name('model', ''));
    $this->assertEquals($Config::module_path() . "/view/site/home-d.html.php", 
      $View->get_view_file_name('project', 'site', 'home'));
    $this->assertEquals($Config::module_path() . "/view/site/about.html.php", 
      $View->get_view_file_name('project', 'site', 'about'));
    $this->assertNull($View->get_view_file_name('project', 'site'));
    
    $View->destroy();
  }

  /**
   * Set file name and controller for view
   */
  public function test_set_view_params() 
  {
    ob_start();
    $class_view = get_final_class_name('View');
    $Config = get_final_class_name('Config');

    $View = $class_view::singleton();

    $user_model = UserModel::singleton();
    $View->register_custom_object('user', $user_model);

    $View->render('model', '');
    $this->assertNull($View->get_inner_file_name());

    $View->render('project', 'site', 'home');
    $this->assertEquals($Config::module_path() . "/view/site/home-d.html.php", 
      $View->get_inner_file_name());

    $View->render('site', 'about');
    $this->assertEquals($Config::module_path() . "/view/site/about.html.php", 
      $View->get_inner_file_name());

    $View->render('site');
    $this->assertNull($View->get_inner_file_name());

    $View->render('not_exists', 'about');
    $this->assertEquals($Config::module_path() . "/view/site/error.html.php", 
      $View->get_inner_file_name());

    $View->destroy();
    ob_clean();
  }

  /**
   * Used to assign variables that can be used in the template files.
   */
  public function test_assign() 
  {
    $class_view = get_final_class_name('View');
    $View = $class_view::singleton();

    $object = 'testobj';
    $View->assign('testname', $object);
    $this->assertNull($View->get('not_exists'));
    $this->assertEquals($object, $View->get('testname'));

    $View->destroy();
  }

  /**
   * Used to assign the page title of the rendered HTML file.
   */
  public function test_set_title() 
  {
    $class_view = get_final_class_name('View');
    $View = $class_view::singleton();

    $View->set_title('testtitle');
    $this->assertEquals('testtitle - Iris PHP Framework', $View->page_title());

    $View->set_title('');
    $this->assertEquals('Iris PHP Framework', $View->page_title());

    $View->destroy();
  }

  /**
   * Set any status or error messages to be passed into the view files.
   */
  public function test_set_msg() 
  {
    $class_view = get_final_class_name('View');
    $View = $class_view::singleton();

    $View->set_msg('test_msg');
    $this->assertEquals("<div class=\"alert alert-error\">test_msg</div>\n", 
      $View->get_msg());

    $View->set_msg('test_msg', true);
    $this->assertEquals("<div class=\"alert alert-success\">test_msg</div>\n", 
      $View->get_msg());

    $View->set_msg(null);
    $this->assertNull($View->get_msg());

    $View->set_msg(null, true);
    $this->assertNull($View->get_msg());

    $View->destroy();
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