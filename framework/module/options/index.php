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

$module_path = OptionsConfig::module_path();

// Register current module
$Module = CoreModule::singleton();
$Module->add_module(OptionsConfig::$module_structure, 
  basename($module_path), 'OptionsConfig');

?>