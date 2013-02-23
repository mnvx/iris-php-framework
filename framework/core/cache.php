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

  protected $cacheDir;
  protected $cacheTime;
  protected $caching = false;
  protected $cacheFile;
  protected $cacheFilePathName;
  protected $do_cache = false;

  /**
   * Create an hash of the currently requested URL, set the filename based on the hash.
   */
  public function __construct($cache_time, $cache_path, $cache_pages, $hash_function) {
    $this->cacheTime = $cache_time;
    $this->cacheDir = $cache_path;

    $login = array_key_exists('login', $_SESSION) ? $_SESSION['login'] : null;

    // Hash the requested URI
    if ($cache_pages == 'indiscriminately'
    || ($cache_pages == 'guest' && !$login)) {
      $this->cacheFile = hash_case($hash_function, $_SERVER['REQUEST_URI']);
      $this->do_cache = true;
    }
    elseif ($cache_pages == 'user') {
      $this->cacheFile = hash_case($hash_function, $_SERVER['REQUEST_URI'].
        ($login ? '*'.$login : ''));
      $this->do_cache = true;
    }
    else {
      $this->cacheFile = '';
    }
    
    // Set the filename using the hash.
    $this->cacheFilePathName = $this->cacheDir.'/'.$this->cacheFile.'.cache';

    // If the cache directory doesn't exist, create it and set correct permissions.
    if (!is_dir($this->cacheDir)) {
      mkdir($this->cacheDir, 0755);
    }
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
  
  /**
   * This function deletes the cache file for the current URI.
   */
  public function purge() {
    if (file_exists($this->cacheFile) && is_writable($this->cacheDir)) {
      unlink($this->cacheFile);
    }
  }
  
  /**
   * This function deletes all of the cache files in the cache directory.
   */
  public function purge_all() {
    if (!$dirhandle = @opendir($this->cacheDir)) {
      return;
    }
    while (false != ($filename = readdir($dirhandle))) {
      if (substr($filename, -4) == '.cache') {
        $filename = $this->cacheDir. "/". $filename;
        unlink($filename);
      }
    }
  }
}

?>