<?php
namespace IrisPHPFramework;

/**
 * Custom configuration values
 *
 * @license MIT, http://opensource.org/licenses/mit-license.php
 */

class OptionsConfig extends CoreConfig {

  // Structure of the current module
  public static $module_structure = array(
    // Current module name
    'name' => 'Options',
    // What modules are required for current module
    'require_module' => array('Core'),
    // Format: BaseClassName => array('class' => ClassName[, 'parent' => PaerntClassName])
    'classes' => array(
      'OptionsConfig' => array(
        'class' => 'OptionsConfig', 
      ),
      'OptionsController' => array(
        'class' => 'OptionsController'
      ),
    ),
  );

  public static $routes = array(
    'options' => array(
      'pattern' =>'/options', 
      'controller' => 'options', 
      'action' => 'index',
    ),
  );
  
  /**
   * Module path (current module path)
   */
  public static function module_path()
  {
    return __DIR__;
  }

}

?>