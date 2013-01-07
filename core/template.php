<?php

/**
 * Template Model
 *
 * This class contains all of the functions used for rendering the HTML templates from
 * the various view files.
 *
 * @license MIT, http://opensource.org/licenses/mit-license.php
 */

class TemplateModel {
  use Singleton;

  protected $variables = array();
  protected $custom_objets = array();
  protected $title;
  protected $msg;
  protected $msg_type;
  protected $inner_file;
  protected $controller;
  protected $action;

  public function register_custom_object($name, $object)
  {
    $this->custom_objets[$name] = $object;
  }

  /**
   * Get file name for view
   *
   * @param   $file       The name of the view/model file
   * @param   $action     When specified, name of the file ($file used as dir name).
   *                      This param is optional.
   */
  public function get_view_file_name($model, $action = null){
    // If an action is specified, include the specific action.
    $file = Config::lib_dir() . "/views/" . $model;
    if ($action) {
      $file .= '/'.strtolower($action);
    }

    $router = Router::singleton();
    $format = $router->get_url_prefix('format');
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
  public function set_view_params($model, $action = null){
    $this->inner_file = $this->get_view_file_name($model, $action);
    $this->controller = $model;
    $this->action = $action;
    
    if ($action && !file_exists($this->inner_file)) {
      $this->inner_file = $this->get_view_file_name(Config::$router_default_controller, 'error');
    }
  }

  /**
   * Simple function used to load template views.  If only a view/model is specified,
   * load only the file from the base template directory.  If an action is specified,
   * the file/model name is expected to also be the name of the folder in which the
   * actual view file is being held.
   *
   * @param   $file       The name of the view/model file
   * @param   $action     When specified, name of the file ($file used as dir name).
   *                      This param is optional.
   */
  public function load($file, $action = null){
    // Custom objects using in including view
    foreach ($this->custom_objets as $name => $object) {
      $$name = $object;
    }

    // core objects using in including view
    $template = TemplateModel::singleton();
    $router = Router::singleton();
    $debug = Debug::singleton();

    $file = $this->get_view_file_name($file, $action);

    // Load the view file only if it exists.
    if (file_exists($file) && file_exists($this->inner_file)) { 
      include_once $file;
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
  public function render($model, $action = null) {
    $this->set_view_params($model, $action);
    $this->load("layout");
  }

   /**
   * Used to assign variables that can be used in the template files.
   *
   * @param   $name       Name of the variable to be assigned
   * @param   $value      String or Array object
   */
  public function assign($name, $value){
    $this->variables[$name] = $value;
    
    //Если устанавливаем язык, то выполним переключение языка
    if ($name == 'locale') {
      Config::set_locale($value);
    }
  }
  
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
  public function set_title($title){
    $this->title = $title;
  }

   /**
   * This function prints the page title that has been set in the controller,
   * should only be used in the header view.
   */
  public function page_title(){
    $str = ($this->title ? _($this->title).' - ' : '')._(Config::$app_name);
    echo $str;
  }

   /**
   * Set any status or error messages to be passed into the view files.
   *
   * @param   $the_msg    The message to be displayed in the status box.
   * @param   $type       Type of message, either 'success' or 'error' -
   *                      passed into the DIV object as a class (used for styling).
   */
  public function set_msg($the_msg, $type = null){
    $this->msg = $the_msg;
    $this->msg_type = $type;
  }

   /**
   * Displays the status or error message in the template.
   */
  public function get_msg(){
    $router = Router::singleton();
    $format = $router->get_url_prefix('format');
    $class = $format == 'd' ? 'alert' : 'status message';

    if($this->msg_type) {
      $style = ($format == 'd' ? 'alert-' : '').'success';
    } else {
      $style = ($format == 'd' ? 'alert-' : '').'error';
    }

    if ($this->msg) {
      echo '<div class="' . $class . ' ' . $style . '">'.$this->msg."</div>\n";
    }
  }

  public function get_inner_file_name(){
    return $this->inner_file;
  }

  public function get_controller_name(){
    return $this->controller;
  }

  public function get_action_name(){
    return $this->action;
  }

}

?>