<?php
namespace IrisPHPFramework;

require_once 'framework/core/singleton.php';
require_once 'framework/core/router.php';
require_once 'framework/core/helpers.php';
require_once 'framework/core/config.php';
require_once 'framework/core/view.php';
require_once 'framework/project/config.php';

/**
 * Router
 *
 * Routing.
 *
 * @license MIT, http://opensource.org/licenses/mit-license.php
 */

class CoreRouterTest extends \PHPUnit_Framework_TestCase {

  public function __construct()
  {
    if (!session_id()) {
      session_start();
    }
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

    $router = CoreRouter::singleton();
    $this->assertEquals($class_config::$url_prefix_format['format']['default'], 
      $router->get_url_prefix_param_value('format'));
    $this->assertEquals($class_config::$url_prefix_format['locale']['default'], 
      $router->get_url_prefix_param_value('locale'));
    $router->destroy();
    
    $_SERVER['REQUEST_URI'] = $class_config::$base_url.'/m/user';
    $router = CoreRouter::singleton();
    $this->assertEquals('m', $router->get_url_prefix_param_value('format'));
    $this->assertEquals($class_config::$url_prefix_format['locale']['default'], 
      $router->get_url_prefix_param_value('locale'));
    $router->destroy();

    $_SERVER['REQUEST_URI'] = $class_config::$base_url.'/user?name=mnv';
    $router = CoreRouter::singleton();
    $this->assertEquals($class_config::$url_prefix_format['format']['default'], 
      $router->get_url_prefix_param_value('format'));
    $this->assertEquals($class_config::$url_prefix_format['locale']['default'], 
      $router->get_url_prefix_param_value('locale'));
    $router->destroy();
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

    $router = CoreRouter::singleton();
    $this->assertEquals($class_config::$base_url.'', 
      $router->prefix_url());
    $this->assertEquals($class_config::$base_url.'', 
      $router->prefix_url(array('format' => 'd')));
    $this->assertEquals($class_config::$base_url.'/en', 
      $router->prefix_url(array('locale' => 'en')));
    $this->assertEquals($class_config::$base_url.'', 
      $router->prefix_url(array('locale' => 'ru')));
    $this->assertEquals($class_config::$base_url.'', 
      $router->prefix_url(array('locale' => 'ru'), true));
    $router->destroy();
    
    $_SERVER['REQUEST_URI'] = $class_config::$base_url.'/m/user';
    $router = CoreRouter::singleton();
    $this->assertEquals($class_config::$base_url.'/m', 
      $router->prefix_url());
    $this->assertEquals($class_config::$base_url.'', 
      $router->prefix_url(array('format' => 'd')));
    $this->assertEquals($class_config::$base_url.'/m/en', 
      $router->prefix_url(array('locale' => 'en')));
    $this->assertEquals($class_config::$base_url.'/m', 
      $router->prefix_url(array('locale' => 'ru')));
    $this->assertEquals($class_config::$base_url.'/m/user', 
      $router->prefix_url(array('locale' => 'ru'), true));
    $this->assertEquals($class_config::$base_url.'/m/en/user', 
      $router->prefix_url(array('locale' => 'en'), true));
    $router->destroy();
    
    $_SERVER['REQUEST_URI'] = $class_config::$base_url.'/en/user';
    $router = CoreRouter::singleton();
    $this->assertEquals($class_config::$base_url.'/de/user', 
      $router->prefix_url(array('locale' => 'de'), true));
    $router->destroy();

    $_SERVER['REQUEST_URI'] = $class_config::$base_url.'/user?name=mnv';
    $router = CoreRouter::singleton();
    $this->assertEquals($class_config::$base_url.'', 
      $router->prefix_url());
    $this->assertEquals($class_config::$base_url.'', 
      $router->prefix_url(array('format' => 'd')));
    $this->assertEquals($class_config::$base_url.'/en', 
      $router->prefix_url(array('locale' => 'en')));
    $this->assertEquals($class_config::$base_url.'', 
      $router->prefix_url(array('locale' => 'ru')));
    $this->assertEquals($class_config::$base_url.'/en/user', 
      $router->prefix_url(array('locale' => 'en'), true));
    $router->destroy();
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

    $router = CoreRouter::singleton();
    foreach ($class_config::$routes as $name => $route) {
      $router->map($name, $route);
    }
    $this->assertEquals(false, $router->url('not_exists_route'));
    $this->assertEquals($class_config::$base_url.'/', 
      $router->url('home'));
    $this->assertEquals($class_config::$base_url.'/users/1', 
      $router->url('user', array('id' => 1)));
    $this->assertEquals($class_config::$base_url.'/m/users/1', 
      $router->url('user', array('id' => 1), array('format' => 'm')));
    $this->assertEquals($class_config::$base_url.'/users/1', 
      $router->url('user', array('id' => 1), array('format' => 'd')));
    $this->assertEquals($class_config::$base_url.'/m/en/users/1', 
      $router->url('user', array('id' => 1), array('format' => 'm', 'locale' => 'en')));
    $router->destroy();
  }

  /**
   * Get current controller name in format "options" or "my_controller"
   */
  public function testget_controller_name()
  {
    $class_config = get_final_class_name('Config');
    $_SERVER['REQUEST_URI'] = $class_config::$base_url.'/about';
    $router = CoreRouter::singleton();
    foreach ($class_config::$routes as $name => $route) {
      $router->map($name, $route);
    }
    $router->execute();
    $this->assertEquals('site', $router->get_controller_name());
    $router->destroy();

    $_SERVER['REQUEST_URI'] = $class_config::$base_url.'/users/1';
    $router = CoreRouter::singleton();
    foreach ($class_config::$routes as $name => $route) {
      $router->map($name, $route);
    }
    $router->execute();
    $this->assertEquals('user', $router->get_controller_name());
    $router->destroy();

    $_SERVER['REQUEST_URI'] = $class_config::$base_url.'/not_exists_url';
    $router = CoreRouter::singleton();
    foreach ($class_config::$routes as $name => $route) {
      $router->map($name, $route);
    }
    $router->execute();
    $this->assertEquals(null, $router->get_controller_name());
    $router->destroy();

    $_SERVER['REQUEST_URI'] = $class_config::$base_url.'/test';
    $router = CoreRouter::singleton();
    $routes = array('name1' => array('pattern' => '/test'), 'name2' => array());
    foreach ($routes as $name => $route) {
      $router->map($name, $route);
    }
    $router->execute();
    $this->assertEquals('site', $router->get_controller_name());
    $router->destroy();

  }

  /**
   * Get current controller class name in format (Oprions or MyController)
   */
  public function test_get_controller_class_name()
  {
    $class_config = get_final_class_name('Config');
    
    $_SERVER['REQUEST_URI'] = $class_config::$base_url.'/about';
    $router = CoreRouter::singleton();
    foreach ($class_config::$routes as $name => $route) {
      $router->map($name, $route);
    }
    $router->execute();
    $this->assertEquals('Site', $router->get_controller_class_name());
    $router->destroy();

    $_SERVER['REQUEST_URI'] = $class_config::$base_url.'/users/1';
    $router = CoreRouter::singleton();
    foreach ($class_config::$routes as $name => $route) {
      $router->map($name, $route);
    }
    $router->execute();
    $this->assertEquals('User', $router->get_controller_class_name());
    $router->destroy();

    $_SERVER['REQUEST_URI'] = $class_config::$base_url.'/not_exists_url';
    $router = CoreRouter::singleton();
    foreach ($class_config::$routes as $name => $route) {
      $router->map($name, $route);
    }
    $router->execute();
    $this->assertEquals(null, $router->get_controller_class_name());
    $router->destroy();
  }

  /**
   * Get current controller action name
   */
  public function test_get_action_name()
  {
    $class_config = get_final_class_name('Config');
    
    $_SERVER['REQUEST_URI'] = $class_config::$base_url.'/about';
    $router = CoreRouter::singleton();
    foreach ($class_config::$routes as $name => $route) {
      $router->map($name, $route);
    }
    $router->execute();
    $this->assertEquals('about', $router->get_action_name());
    $router->destroy();

    $_SERVER['REQUEST_URI'] = $class_config::$base_url.'/users/1';
    $router = CoreRouter::singleton();
    foreach ($class_config::$routes as $name => $route) {
      $router->map($name, $route);
    }
    $router->execute();
    $this->assertEquals('info', $router->get_action_name());
    $router->destroy();

    $_SERVER['REQUEST_URI'] = $class_config::$base_url.'/not_exists_url';
    $router = CoreRouter::singleton();
    foreach ($class_config::$routes as $name => $route) {
      $router->map($name, $route);
    }
    $router->execute();
    $this->assertEquals(null, $router->get_action_name());
    $router->destroy();
  }

  /**
   * Get current parameters from route (parsed from {})
   * @return array|null Return current route parameters
   */
  public function test_get_params()
  {
    $class_config = get_final_class_name('Config');
    
    $_SERVER['REQUEST_URI'] = $class_config::$base_url.'/about';
    $router = CoreRouter::singleton();
    foreach ($class_config::$routes as $name => $route) {
      $router->map($name, $route);
    }
    $router->execute();
    $this->assertEquals(array(), $router->get_params());
    $router->destroy();

    $_SERVER['REQUEST_URI'] = $class_config::$base_url.'/users/1';
    $router = CoreRouter::singleton();
    foreach ($class_config::$routes as $name => $route) {
      $router->map($name, $route);
    }
    $router->execute();
    $this->assertEquals(array('id' => 1), $router->get_params());
    $router->destroy();

    $_SERVER['REQUEST_URI'] = $class_config::$base_url.'/not_exists_url';
    $router = CoreRouter::singleton();
    foreach ($class_config::$routes as $name => $route) {
      $router->map($name, $route);
    }
    $router->execute();
    $this->assertEquals(null, $router->get_params());
    $router->destroy();
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
    $_SERVER['REQUEST_URI'] = $class_config::$base_url.$url;
    $router = CoreRouter::singleton();
    foreach ($class_config::$routes as $name => $route) {
      $router->map($name, $route);
    }
    $router->execute();
    $this->assertEquals($value, $router->is_route_found());
    $router->destroy();
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
    $_SERVER['REQUEST_URI'] = $class_config::$base_url.$url;
    $router = CoreRouter::singleton();
    foreach ($class_config::$routes as $name => $route) {
      $router->map($name, $route);
    }
    $router->execute();
    $route = $router->get_current_route();
    $route_array = $route ? $route->get_route() : null;
    $this->assertEquals($value, $route_array);
    $router->destroy();
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
    $_SERVER['REQUEST_URI'] = $class_config::$base_url.$url;
    $router = CoreRouter::singleton();
    foreach ($class_config::$routes as $name => $route) {
      $router->map($name, $route);
    }
    $router->execute();
    $this->assertEquals($value, $router->get_current_route_name());
    $router->destroy();
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