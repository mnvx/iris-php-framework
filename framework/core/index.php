<?php
namespace IrisPHPFramework;

/**
 * Start point of Framework (core section)
 *
 * Contains list of included modules
 *
 * @license MIT, http://opensource.org/licenses/mit-license.php
 */


// Traits
require_once('singleton.php');

// Classes
require_once('module.php');
require_once('cache.php');
require_once('view.php');
require_once('route.php');
require_once('router.php');
require_once('db.php');
require_once('controller.php');
require_once('application.php');

// Load helper functions and the model classes.
require_once('helpers.php');

// Configuration
require_once('config.php');
$s = CoreConfig::get_slash(); // Slash symbol

// Debug mode
if (CoreConfig::$debug) {
  require_once('debug.php');
}


$module = CoreModule::singleton();
$module->add_module(CoreConfig::$module_structure, null, 'CoreConfig');
// Load modules
// TODO: include only these chains of modules, what using in current route
// TODO: add routes
if (is_dir(CoreConfig::base_module_path())) {
  $file_list = scandir(CoreConfig::base_module_path());
  if ($file_list) {
    foreach ($file_list as $module_path_name) {
      if (is_dir(CoreConfig::base_module_path().$s.$module_path_name) 
      && $module_path_name != '.' && $module_path_name != '..') {
        if (file_exists(CoreConfig::base_module_path().$s.$module_path_name.$s.'index.php')) {
          require_once(CoreConfig::base_module_path().$s.$module_path_name.$s.'index.php');
        }
      }
    }
  }
}
$module->execute();
//print_r($module);
// Start application
$class_application = get_final_class_name('Application');
$app = new $class_application();

?>