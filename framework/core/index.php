<?php
namespace IrisPHPFramework;

/**
 * Start point of Framework (core section)
 *
 * Contains list of included modules
 *
 * @license MIT, http://opensource.org/licenses/mit-license.php
 */


// Load helper functions and the model classes.
require_once('helpers.php');

// Traits
require_once('singleton.php');

// Configuration
require_once('config.php');
if (file_exists(CoreConfig::project_path().'/config.php')) {
  require_once(CoreConfig::project_path().'/config.php');
}
$class_config = get_final_class_name('Config');

// Debug mode
if ($class_config::$debug) {
  require_once('debug.php');
  if (file_exists($class_config::project_path().'/debug.php')) {
    require_once($class_config::project_path().'/debug.php');
  }
  $class_debug = get_final_class_name('Debug');
  $debug = $class_debug::singleton();
}

require_once('cache.php');
require_once('view.php');
require_once('route.php');
require_once('router.php');
require_once('db.php');
require_once('controller.php');
require_once('application.php');

// Custom logic
require_once($class_config::project_path().'/index.php');

// Start application
$class_application = get_final_class_name('Application');
$app = new $class_application();

?>