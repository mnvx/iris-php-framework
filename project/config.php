<?php

/**
 * Custom configuration values
 *
 * @license MIT, http://opensource.org/licenses/mit-license.php
 */

class Config extends CoreConfig {

  public static $base_url = '/ex/framework';
  public static $debug = true;
  
  public static $db = array(
    'dsn' => 'sqlite:[#base_dir#]/data/sqlite.db',
    'username' =>  null,
    'password' => null,
    'driver_options' => null,
  );

  //Формат начала ссылки ([/формат][/язык]/...) - порядок следования важен
  public static $url_prefix_format = array(
    // Format: d - desktop, m - mobile, t - tablet, 
    // File names format for views: file[-format].html.php, 
    //   Example: test-m.html.php, home.html.php
    'format' => array(
      'mask' => '/^(d|t|m)$/', 
      'default' => 'd',
      'supported' => array(
        'd' => 'Desktop', 
        'm' => 'Mobile',
      ),
      'display' => true,
      'name' => 'Format',
    ),
    'locale' => array(
      'mask' => '/^(ru|en|de|es|fr|it|ja|uk|zh|af|ar|be|bg|cz|da|el|et|fa|fi|hi|hu|hy|is|ko|lv|lt|nl|no|pl|pt|rm|ro|sr|sk|sl|sq|sv|th|tr|vi)$/', 
      'default' => 'ru',
      'supported' => array(
        'ru' => 'Русский', 
        'en' => 'English', 
        'de' => 'Deutsch',
      ),
      'display' => true,
      'name' => 'Language',
    ),
  );

  // Set the default controller hte user is directed to (aka homepage).
  public static $router_default_controller = 'site';
  public static $router_default_action = 'home';

  public static $routes = array(
    'home' => array(
      'pattern' =>'/', 
      'controller' => 'site', 
      'action' => 'home',
      'caching' => true,
    ),
    'about' => array(
      'pattern' =>'/about', 
      'controller' => 'site', 
      'action' => 'about',
      'caching' => true,
    ),
    'terms' => array(
      'pattern' =>'/terms', 
      'controller' => 'site', 
      'action' => 'terms',
      'caching' => true,
    ),
    'options' => array(
      'pattern' =>'/options', 
      'controller' => 'options', 
      'action' => 'index',
    ),
    'profile' => array(
      'pattern' =>'/user', 
      'controller' => 'user', 
      'action' => 'index',
    ),
    'profile_edit' => array(
      'pattern' =>'/user/edit', 
      'controller' => 'user', 
      'action' => 'edit',
    ),
    'login' => array(
      'pattern' =>'/login', 
      'controller' => 'user', 
      'action' => 'login',
    ),
    'logout' => array(
      'pattern' =>'/logout', 
      'controller' => 'user', 
      'action' => 'logout',
    ),
    'signup' => array(
      'pattern' =>'/signup', 
      'controller' => 'user', 
      'action' => 'register',
      'caching' => true,
    ),
    /*
    // Route with parameters example
    'user' => array(
      'pattern' =>'/users/{id}',
      'controller' => 'users', 
      'action' => 'index',
      'requirements' => array(
        'id' => '[\d]{1,8}',
      ),
    ),
    // Format for links like /site/about
    'controller_action' => array(
      'pattern' =>'/{controller}/{action}',
    ),
    */
  );

  public static function lib_dir()
  {
    return dirname(__FILE__);
  }

}

?>