<?php
namespace IrisPHPFramework;

$test = true;
require_once 'test.php';
require_once 'framework/core/config.php';
require_once 'framework/core/index.php';

/**
 * Router
 *
 * Routing.
 *
 * @license MIT, http://opensource.org/licenses/mit-license.php
 */

class CoreRouterTest extends \PHPUnit_Framework_TestCase {

  use Test;

  public function __construct()
  {
    if (!session_id()) {
      session_start();
    }

    $this->_init();
  }

  /**
   * Get value of parameter in prefix of url.
   * Link prefix format: ([/format][/language]/...) - order is important.
   * Full URL format: [/URL prefix]/[request_uri]
   *
   * @param $param_name Name of parameter
   */
  public function test_get_url_prefix_param_value()
  {
    $class_config = get_final_class_name('Config');

    $this->_update_route();
    $this->assertEquals($class_config::$url_prefix_format['format']['default'], 
      $this->_Router->get_url_prefix_param_value('format'));
    $this->assertEquals($class_config::$url_prefix_format['locale']['default'], 
      $this->_Router->get_url_prefix_param_value('locale'));
    
    $this->_update_route($class_config::$base_url.'/m/user');
    $this->assertEquals('m', $this->_Router->get_url_prefix_param_value('format'));
    $this->assertEquals($class_config::$url_prefix_format['locale']['default'], 
      $this->_Router->get_url_prefix_param_value('locale'));

    $this->_update_route($class_config::$base_url.'/user?name=mnv');
    $this->assertEquals($class_config::$url_prefix_format['format']['default'], 
      $this->_Router->get_url_prefix_param_value('format'));
    $this->assertEquals($class_config::$url_prefix_format['locale']['default'], 
      $this->_Router->get_url_prefix_param_value('locale'));
  }

  /**
   * Get the base url with url prefix
   *
   * @param string $param_name Name of parameter (language or display format), what need to specify
   * @param $param_value Parameter's value
   * @param boolean $current Return full current link wint specified params
   */
  public function test_prefix_url()
  {
    $class_config = get_final_class_name('Config');

    $this->_update_route();
    $this->assertEquals($class_config::$base_url.'', 
      $this->_Router->prefix_url());
    $this->assertEquals($class_config::$base_url.'', 
      $this->_Router->prefix_url(array('format' => 'd')));
    $this->assertEquals($class_config::$base_url.'/en', 
      $this->_Router->prefix_url(array('locale' => 'en')));
    $this->assertEquals($class_config::$base_url.'', 
      $this->_Router->prefix_url(array('locale' => 'ru')));
    $this->assertEquals($class_config::$base_url.'', 
      $this->_Router->prefix_url(array('locale' => 'ru'), true));
    
    $this->_update_route($class_config::$base_url.'/m/user');
    $this->_Router = CoreRouter::singleton();
    $this->assertEquals($class_config::$base_url.'/m', 
      $this->_Router->prefix_url());
    $this->assertEquals($class_config::$base_url.'', 
      $this->_Router->prefix_url(array('format' => 'd')));
    $this->assertEquals($class_config::$base_url.'/m/en', 
      $this->_Router->prefix_url(array('locale' => 'en')));
    $this->assertEquals($class_config::$base_url.'/m', 
      $this->_Router->prefix_url(array('locale' => 'ru')));
    $this->assertEquals($class_config::$base_url.'/m/user', 
      $this->_Router->prefix_url(array('locale' => 'ru'), true));
    $this->assertEquals($class_config::$base_url.'/m/en/user', 
      $this->_Router->prefix_url(array('locale' => 'en'), true));
    
    $this->_update_route($class_config::$base_url.'/en/user');
    $this->_Router = CoreRouter::singleton();
    $this->assertEquals($class_config::$base_url.'/de/user', 
      $this->_Router->prefix_url(array('locale' => 'de'), true));

    $this->_update_route($class_config::$base_url.'/user?name=mnv');
    $this->_Router = CoreRouter::singleton();
    $this->assertEquals($class_config::$base_url.'', 
      $this->_Router->prefix_url());
    $this->assertEquals($class_config::$base_url.'', 
      $this->_Router->prefix_url(array('format' => 'd')));
    $this->assertEquals($class_config::$base_url.'/en', 
      $this->_Router->prefix_url(array('locale' => 'en')));
    $this->assertEquals($class_config::$base_url.'', 
      $this->_Router->prefix_url(array('locale' => 'ru')));
    $this->assertEquals($class_config::$base_url.'/en/user', 
      $this->_Router->prefix_url(array('locale' => 'en'), true));
  }
  
  /**
   * Get the url by route name
   *
   * @param string $route_name Route's name
   * @param array $url_params Values of url parameters in route's pattern
   * @param array $prefix_params Values of prefix parameters
   */
  public function test_url()
  {
    $class_config = get_final_class_name('Config');

    $this->_update_route();
    
    $this->assertEquals(false, $this->_Router->url('not_exists_route'));
    $this->assertEquals($class_config::$base_url.'/', 
      $this->_Router->url('home'));
    $this->assertEquals($class_config::$base_url.'/users/1', 
      $this->_Router->url('user', array('id' => 1)));
    $this->assertEquals($class_config::$base_url.'/m/users/1', 
      $this->_Router->url('user', array('id' => 1), array('format' => 'm')));
    $this->assertEquals($class_config::$base_url.'/users/1', 
      $this->_Router->url('user', array('id' => 1), array('format' => 'd')));
    $this->assertEquals($class_config::$base_url.'/m/en/users/1', 
      $this->_Router->url('user', array('id' => 1), 
      array('format' => 'm', 'locale' => 'en')));
  }

  /**
   * Get current controller name in format "options" or "my_controller"
   */
  public function testget_controller_name()
  {
    $class_config = get_final_class_name('Config');

    $this->_update_route($class_config::$base_url.'/about');
    $this->assertEquals('site', $this->_Router->get_controller_name());

    $this->_update_route($class_config::$base_url.'/users/1');
    $this->assertEquals('user', $this->_Router->get_controller_name());

    $this->_update_route($class_config::$base_url.'/not_exists_url');
    $this->assertEquals(null, $this->_Router->get_controller_name());

    //Route without controller
    $this->_update_route($class_config::$base_url.'/test');
    $routes = array('name1' => array('pattern' => '/test'), 'name2' => array());
    foreach ($routes as $name => $route) {
      $this->_Router->map($name, $route);
    }
    $this->_Router->execute();
    $this->assertEquals('site', $this->_Router->get_controller_name());
  }

  /**
   * Get current controller class name in format (Oprions or MyController)
   */
  public function test_get_controller_class_name()
  {
    $class_config = get_final_class_name('Config');
    
    $this->_update_route($class_config::$base_url.'/about');
    $this->assertEquals('Site', $this->_Router->get_controller_class_name());

    $this->_update_route($class_config::$base_url.'/users/1');
    $this->assertEquals('User', $this->_Router->get_controller_class_name());

    $this->_update_route($class_config::$base_url.'/not_exists_url');
    $this->assertEquals(null, $this->_Router->get_controller_class_name());
  }

  /**
   * Get current controller action name
   */
  public function test_get_action_name()
  {
    $class_config = get_final_class_name('Config');
    
    $this->_update_route($class_config::$base_url.'/about');
    $this->assertEquals('about', $this->_Router->get_action_name());

    $this->_update_route($class_config::$base_url.'/users/1');
    $this->assertEquals('info', $this->_Router->get_action_name());

    $this->_update_route($class_config::$base_url.'/not_exists_url');
    $this->assertEquals(null, $this->_Router->get_action_name());
  }

  /**
   * Get current parameters from route (parsed from {})
   * @return array|null Return current route parameters
   */
  public function test_get_params()
  {
    $class_config = get_final_class_name('Config');
    
    $this->_update_route($class_config::$base_url.'/about');
    $this->assertEquals(array(), $this->_Router->get_params());

    $this->_update_route($class_config::$base_url.'/users/1');
    $this->assertEquals(array('id' => 1), $this->_Router->get_params());

    $this->_update_route($class_config::$base_url.'/not_exists_url');
    $this->assertEquals(null, $this->_Router->get_params());
  }

  /**
   * Get sign - is found the route for current url or not
   */
  public function test_is_route_found()
  {
    $class_config = get_final_class_name('Config');    
    $this->helper_is_route_found($class_config, '/about', true);
    $this->helper_is_route_found($class_config, '/users/1', true);
    $this->helper_is_route_found($class_config, '/not_exists_url', false);
  }
  
  private function helper_is_route_found($class_config, $url, $value)
  {
    $this->_update_route($class_config::$base_url.$url);
    $this->assertEquals($value, $this->_Router->is_route_found());
  }

  /**
   * Add route info to route parser and check - is it current route?
   */
  public function test_map()
  {
    // Tested in other methods
  }

  /**
   * Execute current route if route was found
   */
  public function test_execute()
  {
    // Tested in other methods
  }

  /**
   * Get current route object
   * @return Route|null Route object
   */
  public function test_get_current_route()
  {
    $class_config = get_final_class_name('Config');    
    $this->helper_get_current_route($class_config, '/about', array(
      'pattern' =>'/about', 
      'controller' => 'site', 
      'action' => 'about',
      'caching' => true,
    ));
    $this->helper_get_current_route($class_config, '/users/1', array(
      'pattern' =>'/users/{id}',
      'controller' => 'user', 
      'action' => 'info',
      'requirements' => array(
        'id' => '[\d]{1,8}',
      ),
    ));
    $this->helper_get_current_route($class_config, '/not_exists_url', null);
  }

  private function helper_get_current_route($class_config, $url, $value)
  {
    $this->_update_route($class_config::$base_url.$url);
    $route = $this->_Router->get_current_route();
    $route_array = $route ? $route->get_route() : null;
    $this->assertEquals($value, $route_array);
  }

  /**
   * Get current route name
   * @return string|null Current route name or null if route was not found
   */
  public function test_get_current_route_name()
  {
    $class_config = get_final_class_name('Config');    
    $this->helper_get_current_route_name($class_config, '/about', 'about');
    $this->helper_get_current_route_name($class_config, '/users/1', 'user');
    $this->helper_get_current_route_name($class_config, '/not_exists_url', null);
  }

  private function helper_get_current_route_name($class_config, $url, $value)
  {
    $this->_update_route($class_config::$base_url.$url);
    $this->assertEquals($value, $this->_Router->get_current_route_name());
  }

  /**
   * Set current route parameters
   * @param class::Route Route object
   */
  public function test_set_route()
  {
    // Tested in other methods
  }

}

?>