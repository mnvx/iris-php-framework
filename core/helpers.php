<?php

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
function __autoload($name) 
{
  require_once CoreConfig::lib_dir().'/controller/'.strtolower($name).'.php';
}

/**
 * Get the hash in the required register 
 *
 * @param $value Hashed value.
 */
function hash_case($value)
{
  $hash = hash(Config::$hash_function, $value);
  if (Config::$hash_lowercase === true) {
    $hash = strtoupper($hash);
  }
  elseif (Config::$hash_lowercase === false) {
    $hash = strtolower($hash);
  }
  return $hash;
}

?>