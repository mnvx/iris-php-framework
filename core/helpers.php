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
 * @param   $name  The name of the controller.
 */
function __autoload($name) 
{
  require_once CoreConfig::lib_dir().'/controllers/'.strtolower($name).'.php';
}

?>