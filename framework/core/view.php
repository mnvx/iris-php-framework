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

  protected $variables = array();
  protected $custom_objets = array();
  protected $title;
  protected $msg;
  protected $msg_type;
  protected $inner_file;

  /**
   * Register custom object to access them from views
   *
   * @param   string      $name Name, which will be used to access the object 
   *                      from views
   * @param   $object     Custom object
   */
  public function register_custom_object($name, $object)
  {
    $this->custom_objets[$name] = $object;
  }

  /**
   * Get custom object (for tests)
   *
   * @param   string      $name Name of the object 
   */
  public function get_custom_object($name)
  {
    if (array_key_exists($name, $this->custom_objets)) {
      return $this->custom_objets[$name];
    }
    return null;
  }

  /**
   * Get file name for view
   *
   * @param   $model      The name of the view/model file
   * @param   $action     When specified, name of the file ($model used as dir name).
   *                      This param is optional.
   */
  public function get_view_file_name($model, $action = null) 
  {
    $class_config = get_final_class_name('Config');

    // If an action is specified, include the specific action.
    $file = $class_config::project_path() . "/view/" . $model;
    if ($action) {
      $file .= '/'.strtolower($action);
    }

    $class_router = get_final_class_name('Router');
    $router = $class_router::singleton();
    $format = $router->get_url_prefix_param_value('format');
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
  protected function set_view_params($model, $action = null) 
  {
    $this->inner_file = $this->get_view_file_name($model, $action);
    
    if ($action && !file_exists($this->inner_file)) {
      $class_config = get_final_class_name('Config');
      $this->inner_file = $this->get_view_file_name($class_config::$router_default_controller, 'error');
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
  protected function load($file, $action = null) 
  {
    $class_router = get_final_class_name('Router');
    $class_debug = get_final_class_name('Debug');
    $class_config = get_final_class_name('Config');

    // Custom objects using in including view
    foreach ($this->custom_objets as $name => $object) {
      $$name = $object;
    }

    // core objects using in including view
    $view = $this;
    $router = $class_router::singleton();
    $debug = $class_debug::singleton();

    $file = $this->get_view_file_name($file, $action);

    // Load the view file only if it exists.
    if (file_exists($file) && file_exists($this->inner_file) && $this->inner_file != $file) { 
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
  public function render($model, $action = null) 
  {
    $this->set_view_params($model, $action);
    $this->load("layout");
  }

  /**
   * Used to assign variables that can be used in the template files.
   *
   * @param   $name       Name of the variable to be assigned
   * @param   $value      String or Array object
   */
  public function assign($name, $value) 
  {
    $this->variables[$name] = $value;
    
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
    if (array_key_exists($name, $this->variables)) {
      return $this->variables[$name];
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
    $this->title = $title;
  }

  /**
   * This function prints the page title that has been set in the controller,
   * should only be used in the header view.
   */
  public function page_title() 
  {
    $str = ($this->title ? _($this->title).' - ' : '')._(Config::$app_name);
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
    $this->msg = $the_msg;
    $this->msg_type = $type;
  }

  /**
   * Displays the status or error message in the template.
   */
  public function get_msg() 
  {
    if ($this->msg) {
      $class_router = get_final_class_name('Router');
      $router = $class_router::singleton();
      $format = $router->get_url_prefix_param_value('format');
      $class = $format == 'd' ? 'alert' : 'status message';
      
      if ($this->msg_type) {
        $style = ($format == 'd' ? 'alert-' : '').'success';
      } 
      else {
        $style = ($format == 'd' ? 'alert-' : '').'error';
      }

      return '<div class="' . $class . ' ' . $style . '">'.$this->escape($this->msg)."</div>\n";
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
    return $this->inner_file;
  }

}

?>