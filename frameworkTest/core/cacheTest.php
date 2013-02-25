<?php
namespace IrisPHPFramework;

require_once 'framework/core/config.php';
require_once 'framework/project/config.php';
require_once 'framework/core/helpers.php';
require_once 'framework/core/cache.php';

/**
 * Cache Model
 *
 * This class contains all of the functions used for caching the templates to static files,
 * in a bid to minimize DB requests and dynamic calls as much as possible.
 *
 * NOTE: This probably isn't super effective for some types of sites, however it seems to
 * work decently for simple applications like this. It may be worth changing to something
 * more robust later on.
 *
 * @license MIT, http://opensource.org/licenses/mit-license.php
 */

class CoreCacheTest extends \PHPUnit_Framework_TestCase {

  /**
   * Create an hash of the currently requested URL, set the filename based on the hash.
   */
  public function test_caching() {
    $class_config = get_final_class_name('Config');
    $class_router = get_final_class_name('Router');
    
    $r = $class_router::singleton();
    foreach ($class_config::$routes as $name => $route) {
      $r->map($name, $route);
    }
    $r->execute();

    // With login
    $_SERVER['REQUEST_URI'] = $class_config::$base_url.'/about';
    $login = array_key_exists('login', $_SESSION) ? $_SESSION['login'] : null;
    $file_name = hash_case($class_config::$hash_function, $_SERVER['REQUEST_URI'].
        ($login ? '*'.$login : ''));
    $full_file_name = $class_config::base_path().$class_config::get_slash().'cache'.
      $class_config::get_slash().
      $file_name.'.cache';

    if (file_exists($class_config::base_path().$class_config::get_slash().
      'cache'.$class_config::get_slash().'.htaccess')) {
      unlink($class_config::base_path().$class_config::get_slash().
        'cache'.$class_config::get_slash().'.htaccess');
    }
    if (is_dir($class_config::base_path().$class_config::get_slash().'cache')) {
      rmdir($class_config::base_path().$class_config::get_slash().'cache');
    }

    $cache = new CoreCache(
      $class_config::$cache_time, 
      $class_config::base_path().$class_config::get_slash().'cache', 
      'guest',
      $class_config::$hash_function
    );
    $cache->purge_all();
    $this->assertFileExists($class_config::base_path().$class_config::get_slash().
      'cache'.$class_config::get_slash().'.htaccess');
    $this->assertTrue(is_dir($class_config::base_path().$class_config::get_slash().'cache'));
    
    unlink($class_config::base_path().$class_config::get_slash().
      'cache'.$class_config::get_slash().'.htaccess');
    rmdir($class_config::base_path().$class_config::get_slash().'cache');
    $cache->purge_all();
    
    $cache = new CoreCache(
      $class_config::$cache_time, 
      $class_config::base_path().$class_config::get_slash().'cache', 
      'user',
      $class_config::$hash_function
    );
    $cache->start();
    echo '.';
    $cache->end();    
    $file_contents = file_get_contents($full_file_name);
    $this->assertEquals('.', $file_contents);
    $cache->purge();
    unset($cache);
    
    // Without login
    $login = array_key_exists('login', $_SESSION) ? $_SESSION['login'] : null;
    $file_name = hash_case($class_config::$hash_function, $_SERVER['REQUEST_URI']);
    $full_file_name = $class_config::base_path().$class_config::get_slash().'cache'.
      $class_config::get_slash().
      $file_name.'.cache';
    
    $cache = new CoreCache(
      $class_config::$cache_time, 
      $class_config::base_path().$class_config::get_slash().'cache', 
      'indiscriminately',
      $class_config::$hash_function
    );
    $cache->start();
    echo '.';
    $cache->end();    
    $file_contents = file_get_contents($full_file_name);
    $this->assertEquals('.', $file_contents);
    $cache->purge_all();
    
    // No cache
    $login = array_key_exists('login', $_SESSION) ? $_SESSION['login'] : null;
    $file_name = hash_case($class_config::$hash_function, $_SERVER['REQUEST_URI']);
    $full_file_name = $class_config::base_path().$class_config::get_slash().'cache'.
      $class_config::get_slash().
      $file_name.'.cache';
    $cache->purge_all();
    
    $cache = new CoreCache(
      $class_config::$cache_time, 
      $class_config::base_path().$class_config::get_slash().'cache', 
      'Not_cache_not_exists',
      $class_config::$hash_function
    );
    $cache->start();
    echo '.';
    $cache->end();    
    $this->assertFileNotExists($full_file_name);
  }
  
  /**
   * Starts the cache object; must call this function at the beginning of the content/page
   * you are trying to cache, then call the end function at the (duh) end of it.
   */
  public function start() {
    if (!$this->do_cache) {
      $this->caching = false;
      return;
    }
    // Get the Router object
    $class_router = get_final_class_name('Router');
    $current_route = $class_router::singleton()->get_current_route();
    $route = $current_route != null ? $current_route->get_route() : array();
    $cache_this_page = array_key_exists('caching', $route) ? $route['caching'] : false;

    // If this page isn't in the "Do not cache" list, and caching is enabled, either
    // start the cache process if the previous cache is older than cacheTime or doesn't exist,
    // or else just render the existing cache file.
    if ($cache_this_page && Config::$cache_enable) {
      if (file_exists($this->cacheFilePathName) 
      && (time() - filemtime($this->cacheFilePathName)) < $this->cacheTime) {
        $this->caching = false;
        echo file_get_contents($this->cacheFilePathName);
        exit();
      }
      else {
        $this->caching = true;
        ob_start();
      }
    }
  }
  
  /**
   * Starts the cache object; must call this function at the beginning of the content/page
   * you are trying to cache, then call the end function at the (duh) end of it.
   */
  public function end() {
    if ($this->caching) {
      file_put_contents($this->cacheFilePathName, ob_get_contents());
      ob_end_flush();
    }
  }

}

?>