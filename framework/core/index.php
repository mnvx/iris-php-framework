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
if (!isset($test)) {
  require_once('config.php');
}
$slash = CoreConfig::get_slash(); // Slash symbol
// For testing, this file including from other path
$core_path_slash = CoreConfig::module_path().$slash;

// Traits
require_once($core_path_slash.'singleton.php');

// Classes
require_once($core_path_slash.'module.php');
require_once($core_path_slash.'cache.php');
require_once($core_path_slash.'view.php');
require_once($core_path_slash.'route.php');
require_once($core_path_slash.'router.php');
require_once($core_path_slash.'dblist.php');
require_once($core_path_slash.'dbinterface.php');
require_once($core_path_slash.'controller.php');
require_once($core_path_slash.'application.php');

// Load helper functions
require_once($core_path_slash.'helpers.php');

// Debug mode
if (CoreConfig::$debug) {
  require_once($core_path_slash.'debug.php');
}

// Load modules
$Module = CoreModule::singleton();
$Module->add_module(CoreConfig::$module_structure, null, 'CoreConfig');

// TODO: include only these chains of modules, what using in current route
$base_module_path = CoreConfig::base_module_path();
if (is_dir($base_module_path)) {
  $file_list = scandir($base_module_path);
  if ($file_list) {
    foreach ($file_list as $module_path_name) {
      if (is_dir($base_module_path.$slash.$module_path_name) 
      && $module_path_name != '.' && $module_path_name != '..') {
        if (file_exists($base_module_path.$slash.$module_path_name.$slash.'index.php')) {
          require_once($base_module_path.$slash.$module_path_name.$slash.'index.php');
        }
      }
    }
  }
}
$Module->prepare();
//print_r($Module);exit;

// Start application
$application_class_name = get_final_class_name('Application');
$Application = new $application_class_name();
if (!isset($test)) {
  $Application->execute();
}

?>