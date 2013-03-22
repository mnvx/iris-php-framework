<?php
namespace IrisPHPFramework;

/**
 * Start point of Framework (project section)
 *
 * Contains list of included modules
 *
 * @license MIT, http://opensource.org/licenses/mit-license.php
 */

$slash = CoreConfig::get_slash();

// Configuration
require_once(__DIR__.$slash.'config.php');

$module_path = ProjectConfig::module_path();

// Debug mode
if (ProjectConfig::$debug) {

  // Debug module, if it was not be included in core
  if (ProjectConfig::$debug && !CoreConfig::$debug) {
    require_once('debug.php');
  }

  if (file_exists($module_path.$slash.'debug.php')) {
    require_once($module_path.$slash.'debug.php');
  }
}

//require_once($module_path.$slash.'model'.$slash.'user.php');
require_once($module_path.$slash.'controller.php');
require_once($module_path.$slash.'application.php');

// Register current module
$Module = CoreModule::singleton();
$Module->add_module(ProjectConfig::$module_structure, 
  basename($module_path), 'ProjectConfig');

?>