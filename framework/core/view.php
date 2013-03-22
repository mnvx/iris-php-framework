<?php
namespace IrisPHPFramework;

/**
 * View parameters and methods
 *
 * This class contains all of the functions used for rendering the HTML templates from
 * the various view files.
 *
 * @license MIT, http://opensource.org/licenses/mit-license.php
 */

class CoreView {
  use Singleton;

  protected $_variables = array();
  protected $_custom_objets = array();
  protected $_title;
  protected $_msg;
  protected $_msg_type;
  protected $_inner_file;

  /**
   * Register custom object to access them from views
   *
   * @param   string      $name Name, which will be used to access the object 
   *                      from views
   * @param   $object     Custom object
   */
  public function register_custom_object($name, $object)
  {
    $this->_custom_objets[$name] = $object;
  }

  /**
   * Get custom object (for tests)
   *
   * @param   string      $name Name of the object 
   */
  public function get_custom_object($name)
  {
    if (array_key_exists($name, $this->_custom_objets)) {
      return $this->_custom_objets[$name];
    }
    return null;
  }

  /**
   * Get file name for view
   *
   * @param   $module_path_name      The name of the module path
   * @param   $model      The name of the view/model file
   * @param   $action     When specified, name of the file ($model used as dir name).
   *                      This param is optional.
   */
  public function get_view_file_name($module_path_name, $model, $action = null) 
  {
    $class_config = get_final_class_name('Config');

    $slash = $class_config::get_slash();
    $module_full_path = $module_path_name 
      ? $class_config::base_module_path().$slash.$module_path_name 
      : CoreConfig::module_path();

    // If an action is specified, include the specific action.
    $file = $module_full_path.$slash."view".$slash.$model;
    if ($action) {
      $file .= $slash.strtolower($action);
    }

    $class_router = get_final_class_name('Router');
    $Router = $class_router::singleton();
    $format = $Router->get_url_prefix_param_value('format');
    $file_with_format = $file.($format ? '-'.$format : '').'.html.php';
    $file .= '.html.php';

    // Try to return a file that is common to different formats
    if (file_exists($file)) {
      return $file;
    }
    // Else try to return file to the desired format defined
    elseif (file_exists($file_with_format)) {
      return $file_with_format;
    }

    return null;
  }

  /**
   * Set file name and controller for view
   *
   * @param   $file       The name of the view/model file
   * @param   $action     When specified, name of the file ($file used as dir name).
   *                      This param is optional.
   */
  protected function _set_view_params($module_path_name, $model, $action = null) 
  {
    $this->_inner_file = $this->get_view_file_name($module_path_name, $model, $action);

    if ($action && !file_exists($this->_inner_file)) {
      $class_config = get_final_class_name('Config');
      $this->_inner_file = $this->get_view_file_name(
        null, $class_config::$router_default_controller, 'error');
    }
  }

  /**
   * Load template views.  If only a view/model is specified,
   * load only the file from the base template directory.  If an action is specified,
   * the file/model name is expected to also be the name of the folder in which the
   * actual view file is being held.
   *
   * @param   $file       The name of the view/model file
   * @param   $action     When specified, name of the file ($file used as dir name).
   *                      This param is optional.
   */
  protected function _load($module_path, $file, $action = null) 
  {
    $class_router = get_final_class_name('Router');
    $class_debug = get_final_class_name('Debug');
    $class_config = get_final_class_name('Config');

    // Custom objects using in including view
    foreach ($this->_custom_objets as $name => $object) {
      $$name = $object;
    }

    // Core objects using in including view
    $view = $this;
    $Router = $class_router::singleton();
    $Debug = $class_debug::singleton();

    // Template file
    $slash = $class_config::get_slash();
    $format = $Router->get_url_prefix_param_value('format');
    $theme = $class_config::$theme;
    $file = $class_config::base_path().$slash.'theme'.$slash.$theme.$slash.$file.'-'.$format.'.html.php';

    // Load the view file only if it exists.
    if (file_exists($file) && file_exists($this->_inner_file) && $this->_inner_file != $file) { 
      include_once $file;
    }
    else {
      include_once $class_config::core_path(). "/view/404.html.php";
    }
  }

  /**
   * Renders default template views, based on the model and action supplied (including
   * header and footer views).
   *
   * @param   $model              The name of the model file
   * @param   $action             When specified, name of the file ($file used as dir name)
   * @param   $caching_enabled    (optional): When specified, name of the file ($file used as dir name)
   */
  public function render($path_name, $model, $action = null) 
  {
    $this->_set_view_params($path_name, $model, $action);
    $this->_load($path_name, "layout");
  }

  /**
   * Used to assign variables that can be used in the template files.
   *
   * @param   $name       Name of the variable to be assigned
   * @param   $value      String or Array object
   */
  public function assign($name, $value) 
  {
    $this->_variables[$name] = $value;

    //Если устанавливаем язык, то выполним переключение языка
    if ($name == 'locale') {
      $class_config = get_final_class_name('Config');
      $class_config::set_locale($value);
    }
  }

  /**
   * Get value assigned to template
   *
   * @param   $name       Name of the assigned variable
   */
  public function get($name)
  {
    if (array_key_exists($name, $this->_variables)) {
      return $this->_variables[$name];
    }
    return null;
  }

  /**
   * Used to assign the page title of the rendered HTML file.
   *
   * @param   $title      Title of the rendered HTML file (<title></title>)
   */
  public function set_title($title) 
  {
    $this->_title = $title;
  }

  /**
   * This function prints the page title that has been set in the controller,
   * should only be used in the header view.
   */
  public function page_title() 
  {
    $Config = get_final_class_name('Config');
    $str = ($this->_title ? _($this->_title).' - ' : '')._($Config::$app_name);
    return $str;
  }

  /**
   * Set any status or error messages to be passed into the view files.
   *
   * @param   $the_msg    The message to be displayed in the status box.
   * @param   $type       Type of message, either 'success' or 'error' -
   *                      passed into the DIV object as a class (used for styling).
   */
  public function set_msg($the_msg, $type = null) 
  {
    $this->_msg = $the_msg;
    $this->_msg_type = $type;
  }

  /**
   * Displays the status or error message in the template.
   */
  public function get_msg() 
  {
    if ($this->_msg) {
      $class_router = get_final_class_name('Router');
      $Router = $class_router::singleton();
      $format = $Router->get_url_prefix_param_value('format');
      $class = $format == 'd' ? 'alert' : 'status message';
      
      if ($this->_msg_type) {
        $style = ($format == 'd' ? 'alert-' : '').'success';
      } 
      else {
        $style = ($format == 'd' ? 'alert-' : '').'error';
      }

      return '<div class="' . $class . ' ' . $style . '">'.$this->escape($this->_msg)."</div>\n";
    }
    return null;
  }

  /**
   * Return safe for output html text
   * 
   * @param   $html       HTML text
   */
  public function escape($html)
  {
    return htmlspecialchars($html);
  }

  /**
   * Get file name, which must be outputted inside template
   */
  public function get_inner_file_name() 
  {
    return $this->_inner_file;
  }

}

?>