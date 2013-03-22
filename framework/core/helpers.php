<?php
namespace IrisPHPFramework;

/**
 * Helper Functions
 *
 * This file contains a lot of miscellaneous functions that are used throughout the app.
 *
 * @license MIT, http://opensource.org/licenses/mit-license.php
 */

/**
 * Load the controller file automatically if it is referenced in a function.
 *
 * @param $name  The name of the controller.
 */
spl_autoload_register(function($name) 
{
  $Config = get_final_class_name('Config');
  if (!class_exists($name)) {
    $slash = $Config::get_slash();
    $router_class_name = get_final_class_name('Router');
    $Router = $router_class_name::singleton();
    $module_path_name = $Router->get_module_path_name();
    $class_name = str_replace('IrisPHPFramework\\', '', $name);
    $file_name = $Config::base_module_path().$slash.
      $module_path_name.$slash.
      'controller'.$slash.strtolower($class_name).'.php';
    if (file_exists($file_name)) {
      require_once $file_name;
    }
  }
});

/**
 * Get the hash in the required register 
 *
 * @param $value Hashed value.
 */
function hash_case($value)
{
  $Config = get_final_class_name('Config');
  $hash = hash($Config::$hash_function, $value);
  if ($Config::$hash_lowercase === true) {
    $hash = strtoupper($hash);
  }
  elseif ($Config::$hash_lowercase === false) {
    $hash = strtolower($hash);
  }
  return $hash;
}

/**
 * Get final class name in chace of inheritance (CoreClass - Class)
 *
 * @param $class_short_name String Short class name like "Config", "Controller".
 */
function get_final_class_name($class_short_name)
{
  $Module = CoreModule::singleton();
  return $Module->get_final_class_name($class_short_name);
}

?>