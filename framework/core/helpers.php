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
  $class_config = get_final_class_name('Config');
  $class_name = str_replace('IrisPHPFramework\\', '', $name);
  if (!class_exists($class_name)) {
    $file_name = $class_config::project_path().$class_config::get_slash().'controller'.
      $class_config::get_slash().strtolower($class_name).'.php';
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
  $class_config = get_final_class_name('Config');
  $hash = hash($class_config::$hash_function, $value);
  if ($class_config::$hash_lowercase === true) {
    $hash = strtoupper($hash);
  }
  elseif ($class_config::$hash_lowercase === false) {
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
  $module = CoreModule::singleton();
  return $module->get_final_class_name($class_short_name);
}

?>