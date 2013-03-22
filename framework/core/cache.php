<?php
namespace IrisPHPFramework;

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

class CoreCache {

  protected $_cache_dir;
  protected $_cache_time;
  protected $_caching = false;
  protected $_cache_file;
  protected $_cache_file_path_name;
  protected $_do_cache = false;

  /**
   * Create an hash of the currently requested URL, set the filename based on the hash.
   */
  public function __construct($cache_time, $cache_path, $cache_pages, $hash_function) {
    $this->_cache_time = $cache_time;
    $this->_cache_dir = $cache_path;

    $login = array_key_exists('login', $_SESSION) ? $_SESSION['login'] : null;

    // Hash the requested URI
    if ($cache_pages == 'indiscriminately'
    || ($cache_pages == 'guest' && !$login)) {
      $this->_cache_file = hash_case($hash_function, $_SERVER['REQUEST_URI']);
      $this->_do_cache = true;
    }
    elseif ($cache_pages == 'user') {
      $this->_cache_file = hash_case($hash_function, $_SERVER['REQUEST_URI'].
        ($login ? '*'.$login : ''));
      $this->_do_cache = true;
    }
    else {
      $this->_cache_file = '';
      $this->_do_cache = false;
    }
    
    // Set the filename using the hash.
    $class_config = get_final_class_name('Config');
    $this->_cache_file_path_name = $this->_cache_dir.$class_config::get_slash().
      $this->_cache_file.'.cache';
    // If the cache directory doesn't exist, create it and set correct permissions.
    if (!is_dir($this->_cache_dir)) {
      mkdir($this->_cache_dir, 0755);
      file_put_contents($this->_cache_dir.$class_config::get_slash().'.htaccess', 'Deny from all');
    }
  }
  
  /**
   * Starts the cache object; must call this function at the beginning of the content/page
   * you are trying to cache, then call the end function at the (duh) end of it.
   */
  public function start() {
    if (!$this->_do_cache) {
      $this->_caching = false;
      return;
    }
    // Get the Router object
    $class_router = get_final_class_name('Router');
    $CurrentRoute = $class_router::singleton()->get_current_route();
    $route = $CurrentRoute != null ? $CurrentRoute->get_route() : array();
    $cache_this_page = array_key_exists('caching', $route) ? $route['caching'] : false;

    // If this page isn't in the "Do not cache" list, and caching is enabled, either
    // start the cache process if the previous cache is older than _cache_time or doesn't exist,
    // or else just render the existing cache file.
    if ($cache_this_page) {
      if (file_exists($this->_cache_file_path_name) 
      && (time() - filemtime($this->_cache_file_path_name)) < $this->_cache_time) {
        $this->_caching = false;
        echo file_get_contents($this->_cache_file_path_name);
        exit();
      }
      else {
        $this->_caching = true;
        ob_start();
      }
    }
  }
  
  /**
   * Starts the cache object; must call this function at the beginning of the content/page
   * you are trying to cache, then call the end function at the (duh) end of it.
   */
  public function end() {
    if ($this->_caching) {
      file_put_contents($this->_cache_file_path_name, ob_get_contents());
      ob_end_flush();
    }
  }
  
  /**
   * This function deletes the cache file for the current URI.
   */
  public function purge() {
    if (file_exists($this->_cache_file_path_name) && is_writable($this->_cache_dir)) {
      unlink($this->_cache_file_path_name);
    }
  }
  
  /**
   * This function deletes all of the cache files in the cache directory.
   */
  public function purge_all() {
    if (!$dirhandle = @opendir($this->_cache_dir)) {
      return;
    }
    $class_config = get_final_class_name('Config');
    while (false != ($filename = readdir($dirhandle))) {
      if (substr($filename, -6) == '.cache') {
        unlink($this->_cache_dir.$class_config::get_slash().$filename);
      }
    }
    closedir($dirhandle);
  }
}

?>