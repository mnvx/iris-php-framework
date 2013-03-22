<?php
namespace IrisPHPFramework;

/**
 * CoreModule Class
 *
 * Tools for module manage
 *
 * @license MIT, http://opensource.org/licenses/mit-license.php
 */

class CoreModule {

  use Singleton;

  // List of modules
  protected $_modules = array();
  
  // Tree of module inheritance
  protected $_class_tree = array();

  // Final class names
  protected $_class_final = array();

  /**
   * Add new module dependence
   * @param $module Array with information about module (from Config)
   * @param $path_name = null Name of the module path (null only for Core)
   * @param $config_class_name = null Name of the the class with configuration 
   *   (with routes description)
   */
  final public function add_module($module, $path_name = null, $config_class_name = null, $init_function = null)
  {
    if (!isset($this->_modules[$module['name']])) {
      $this->_modules[$module['name']] = $module;
      $this->_modules[$module['name']]['path_name'] = $path_name;
      $this->_modules[$module['name']]['config_class_name'] = $config_class_name;
      $this->_modules[$module['name']]['init_function'] = $init_function;
    }
    else {
      throw new \Exception(_('Modules with dublicate name was found'));
    }
  }

  /**
   * Build tree of dependences
   * @param &$tree_elem = null Array (tree) for building tree of dependences
   * @param $base_class_name Name of class without prefix
   * @param $parent_class_name Name of parent class
   */
  final public function prepare(&$tree_elem = null, $base_class_name = null, $parent_class_name = null)
  {
    if ($base_class_name == null) {
      $tree_elem = &$this->_class_tree;
    }
    foreach ($this->_modules as $module_name => $module) {
      if ($base_class_name == null) {
        foreach ($module['classes'] as $base_class_key => $class_info) {
          if ($class_info) {
            if ((((isset($class_info['parent']) 
                && $class_info['parent'] == $parent_class_name))
              || (!isset($class_info['parent']) && $parent_class_name == null))
            && class_exists('IrisPHPFramework\\'.$class_info['class'])) {
              if (!isset($tree_elem[$base_class_key][$class_info['class']])) {
                $tree_elem[$base_class_key][$class_info['class']] = null;//array(
                  //'child' => array(),
                  //'loaded' => class_exists('IrisPHPFramework\\'.$class_info['class']),
                //);
                if ($base_class_key) {
                  $this->_class_final[$base_class_key] = $class_info['class'];
                }
              }
              else {
                throw new \Exception(_('Classes with dublicate name was found').': "'.
                  $class_info['class'].'"');
              }
              $this->prepare(
                $tree_elem[$base_class_key][$class_info['class']],//['child'], 
                $base_class_key, 
                $class_info['class']
              );    
            }
          }
          elseif ($parent_class_name == null) {
            $tree_elem[$base_class_key] = null;//array();
            if ($base_class_key) {
              $this->_class_final[$base_class_key] = $base_class_key;
            }
          }
        }
      }
      elseif (isset($module['classes'][$base_class_name])) {
        if (((isset($module['classes'][$base_class_name]['parent']) 
            && $module['classes'][$base_class_name]['parent'] == $parent_class_name)
          || (!isset($module['classes'][$base_class_name]['parent'])
            && $parent_class_name == null))
        && class_exists('IrisPHPFramework\\'.$module['classes'][$base_class_name]['class'])) {
          if (!isset($tree_elem[$module['classes'][$base_class_name]['class']])) {
            $tree_elem[$module['classes'][$base_class_name]['class']] = null;//array(
              //'child' => array(),
              //'loaded' => class_exists('IrisPHPFramework\\'.$module['classes'][$base_class_name]['class']),
            //);
            $this->_class_final[$base_class_name] = $module['classes'][$base_class_name]['class'];
          }
          else {
            throw new \Exception(_('Classes with dublicate name was found').': "'.
              $module['classes'][$base_class_name]['class'].'"');
          }
          $this->prepare(
            $tree_elem[$module['classes'][$base_class_name]['class']],//['child'], 
            $base_class_name, 
            $module['classes'][$base_class_name]['class']
          );    
        }
      }
    }
  }

  /**
   * Execute initialisation methods of registered classes
   */
  final public function execute()
  {
    foreach ($this->_modules as $module_name => $module) {
      if ($module['init_function']) {
        call_user_func($module['init_function']);
      }
    }
  }

  /**
   * Get final class name
   * @param string $base_class_name Base name of class
   * @return string $class_name
   */
  final public function get_final_class_name($base_class_name)
  {
    if (isset($this->_class_final[$base_class_name])) {
      return 'IrisPHPFramework\\'.$this->_class_final[$base_class_name];
    }
    return null;
  }

  /**
   * Check what class is final
   * @return boolean
   */
  final public function is_final($class_name)
  {
    foreach ($this->_class_final as $parent_name => $final_name) {
      if ($final_name == $class_name) {
        return true;
      }
    }
    return false;
  }

  /**
   * Return list of classes
   * @return array (ClassName => PathName)
   */
  final public function get_config_class_names()
  {
    $config_classes = array();
    foreach ($this->_modules as $module_name => $module) {
      if (isset($module['config_class_name'])) {
        if ($this->is_final($module['config_class_name'])) {
          $config_classes[$module['config_class_name']] = $module['path_name'];
        }
      }
    }
    return $config_classes;
  }

  /**
   * Return path of module, where specified class
   * @return array (ClassName => PathName)
   */
  final public function get_class_path_name($class_name)
  {
    $short_class_name = str_replace('IrisPHPFramework\\', '', $class_name);
    foreach ($this->_modules as $module_name => $module) {
      foreach ($module['classes'] as $class_info) {
        if (isset($class_info['class']) && $class_info['class'] == $short_class_name) {
          return $module['path_name'];
        }
      }
    }
    return null;
  }
}

?>