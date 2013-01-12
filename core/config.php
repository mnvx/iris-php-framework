<?php

/**
 * Configuration values
 *
 * Configuration static parameters.
 *
 * @license MIT, http://opensource.org/licenses/mit-license.php
 */

class CoreConfig {
  // Change for every commit in master branch (string, http://habrahabr.ru/post/118756/)
  public static $version = '1.0-b1';
  // Change for every commit in develop branch (int)
  public static $release = 4;

  public static $app_name = 'Iris PHP Framework';
  public static $app_description = 'Fast MVC framework, multilingual, with adaptive desigh support';
  public static $app_keywords = 'framework, mvc, php';

  // '' if files disposed in root catalog of web files. This property will be overriden in Config
  public static $base_url = '';

  // Cache
  public static $cache_enable = false;
  // $cache_pages values:
  // 'indiscriminately' - cache all cacheable pages for user and for guests equally
  // 'guest' - cache only for not authorized visitors
  // 'user' - personal cache for every user (not authorized visitors like some user)
  public static $cache_pages = 'user';
  public static $cache_time = 21600; // 6 hours = 6*60*60
  public static $hash_function = 'md5'; // For cache and passwords
  public static $hash_lowercase = null; // true/false/null

  public static $password_salt = 'askjdfnasvfg#d64';

  public static $debug = false;
  
  // Encoding of messages.mo
  public static $encoding = 'utf8';
  
  public static function lib_dir()
  {
    return static::base_dir().'/project';
  }

  public static function base_dir()
  {
    return dirname(dirname(__FILE__));
  }

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

  // Default controller and action
  public static $router_default_controller = '';
  public static $router_default_action = '';

  public static $routes = array(
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
  public static $controller_postfix = 'Controller';
  public static $action_postfix = 'Action';
  public static $model_postfix = 'Model'; //not used (reserved)

  public static $locales = array(
    'ru' => 'ru_RU.utf-8',
    'en' => 'en_US.utf-8',
    'de' => 'de_DE.utf-8',
  );

  public static function set_locale($language) {
    // в Windows эта константа может быть не определена
    if (!defined('LC_MESSAGES')) {
      define('LC_MESSAGES', 5);
    }
    setlocale(LC_MESSAGES, self::$locales[$language]); // устанавливаем локаль
    
    putenv('LANG='.self::$locales[$language]);
        
    // устанавливаем кодировку файла messages.mo
    bind_textdomain_codeset('translate', strtoupper(self::$encoding));

    // подключаем файлы локализации
    bindtextdomain('translate', self::base_dir().'/locale');
    textdomain('translate');
  }

}

?>