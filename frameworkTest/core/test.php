<?php
namespace IrisPHPFramework;

// Do not include this file (for test_autoload())!
//require_once 'framework/project/controller/usercontroller.php';

/**
 * Configuration values
 *
 * Configuration static parameters.
 *
 * @license MIT, http://opensource.org/licenses/mit-license.php
 */

trait Test {

  protected $_Router;
  protected $_Application;

  protected function _init() {
    $class_router = get_final_class_name('Router');
    $this->_Router = $class_router::singleton();

    $application_class_name = get_final_class_name('Application');
    $this->_Application = new $application_class_name();
  }
  
  protected function _update_route($request_uri = null) {
    $this->_Router->destroy();
    if ($request_uri) {
      $_SERVER['REQUEST_URI'] = $request_uri;
    }
    $this->_Application->init();
    $this->_Application->execute_route();

    $class_router = get_final_class_name('Router');
    $this->_Router = $class_router::singleton();
  }

}

?>