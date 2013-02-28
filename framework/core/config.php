<?php
namespace IrisPHPFramework;

/**
 * Configuration values
 *
 * Configuration static parameters.
 *
 * @license MIT, http://opensource.org/licenses/mit-license.php
 */

class CoreConfig {

  // Structure of the core
  public static $module_structure = array(
    // Current module name
    'name' => 'Core',
    // What modules are required for current module
    'require_module' => array(''),
    // Format: BaseClassName => array('class' => ClassName[, 'parent' => PaerntClassName])
    'classes' => array(
      'Config' => array(
        'class' => 'CoreConfig'
      ),
      'Application' => array(
        'class' => 'CoreApplication'
      ),
      'Controller' => array(
        'class' => 'CoreController'
      ),
      'DB' => array(
        'class' => 'CoreDB'
      ),
      'Cache' => array(
        'class' => 'CoreCache'
      ),
      'Debug' => array(
        'class' => 'CoreDebug'
      ),
      'Module' => array(
        'class' => 'CoreModule'
      ),
      'Route' => array(
        'class' => 'CoreRoute'
      ),
      'Router' => array(
        'class' => 'CoreRouter'
      ),
      'View' => array(
        'class' => 'CoreView'
      ),
    ),
  );
  
  // Change for every commit in master branch 
  //(string, http://habrahabr.ru/post/118756/)
  public static $version = '1.0-rc2';
  // Change for every commit in develop branch (int)
  public static $release = 11;

  public static $app_name = 'Iris PHP Framework';
  public static $app_description = 
    'Fast MVC framework, multilingual, with adaptive desigh support';
  public static $app_keywords = 'framework, mvc, php';

  // '' if files disposed in root catalog of web files. 
  // This property will be overriden in Config
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

  /**
   * Return slash in current OS format
   */
  public static function get_slash()
  {
    return strtolower(substr(PHP_OS, 0, 3)) == 'win' ? '\\' : '/';
  }

  /**
   * Base path
   */
  public static function base_path()
  {
    return dirname(__DIR__);
  }

  /**
   * Core path
   */
  public static function core_path()
  {
    return static::base_path().static::get_slash().'core';
  }

  /**
   * Base module path (path, what contains all modules)
   */
  public static function base_module_path()
  {
    return static::base_path().static::get_slash().'module';
  }

  /**
   * Project path
   * @deprecated
   */
  public static function project_path()
  {
    return static::base_path().static::get_slash().'module'.static::get_slash().'project';
  }

  /**
   * Module path (concrete module path)
   */
  public static function module_path()
  {
    return static::core_path();
  }

  //Link prefix format: ([/format][/language]/...) - order is important
  public static $url_prefix_format = array(
    // Format: d - desktop, m - mobile, t - tablet, 
    // File names format for views: file[-format].html.php, 
    //   Example: test-m.html.php, home.html.php
    'format' => array(
      'mask' => '/^(d|m)$/', 
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

  /**
   * Set locale
   * @param $language Language (ru, en, de, ...), what defined in CoreConfig::locales
   */
  public static function set_locale($language)
  {
    // In Windows this constant can be not defined
    if (!defined('LC_MESSAGES')) {
      define('LC_MESSAGES', 5);
    }

    // Set locale
    if (array_key_exists($language, self::$locales)) {
      $locale = self::$locales[$language];
    }
    else {
      $locale = self::$locales[self::$url_prefix_format['locale']['default']];
    }
    setlocale(LC_MESSAGES, $locale);

    putenv('LANGUAGE='.$locale);
    putenv('LANG='.$locale);

    // устанавливаем кодировку файла messages.mo
    bind_textdomain_codeset('translate', strtoupper(self::$encoding));

    // подключаем файлы локализации
    bindtextdomain('translate', self::base_path().static::get_slash().'locale');
    textdomain('translate');
  }

}

?>