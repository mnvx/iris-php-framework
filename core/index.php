<?php
namespace IrisPHPFramework;

/**
 * Start point of Framework (core section)
 *
 * Contains list of included modules
 *
 * @license MIT, http://opensource.org/licenses/mit-license.php
 */

// Configuration
require_once('config.php');
require_once(CoreConfig::lib_dir().'/config.php');

// Traits
require_once('singleton.php');

// Debug mode
if (Config::$debug) {
  require_once('debug.php');
  require_once(Config::lib_dir().'/debug.php');
  $debug = Debug::singleton();
}

// Load helper functions and the model classes.
require_once('helpers.php');

require_once('cache.php');
require_once('template.php');
require_once('router.php');
require_once('db.php');
require_once('controller.php');
require_once('app.php');

// Custom logic
require_once(Config::lib_dir().'/index.php');

?>