<?php
namespace IrisPHPFramework;

/**
 * Start point of Framework (project section)
 *
 * Contains list of included modules
 *
 * @license MIT, http://opensource.org/licenses/mit-license.php
 */

// Configuration
$s = CoreConfig::get_slash();
if (file_exists(CoreConfig::project_path().$s.'config.php')) {
  require_once(CoreConfig::project_path().$s.'config.php');
}

// Debug mode
if (ProjectConfig::$debug) {

  // Debug module, if it was not be included in core
  if (ProjectConfig::$debug && !CoreConfig::$debug) {
    require_once('debug.php');
  }

  if (file_exists(ProjectConfig::module_path().$s.'debug.php')) {
    require_once(ProjectConfig::module_path().$s.'debug.php');
  }
}

require_once(ProjectConfig::module_path().$s.'model'.$s.'user.php');
require_once(ProjectConfig::module_path().$s.'controller.php');
require_once(ProjectConfig::module_path().$s.'application.php');
require_once(ProjectConfig::module_path().$s.'db.php');

// Register current module
$module = CoreModule::singleton();
$module->add_module(ProjectConfig::$module_structure, 
  basename(ProjectConfig::module_path()), 'ProjectConfig');

?>