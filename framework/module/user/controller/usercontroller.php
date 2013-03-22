<?php
namespace IrisPHPFramework;

/**
 * User Controller
 *
 * This controller contains all the actions the user may perform that deals 
 * with their account.
 */

class UserController extends ProjectController {

  /**
   * Default user profile page.
   */
  function indexAction() {
    $view_class_name = get_final_class_name('View');
    $View = $view_class_name::singleton();
    $this->login_required();

    $View->set_title(_('My Profile'));
    $View->render(CoreModule::singleton()->get_class_path_name(__CLASS__), 
      "user", "profile");
  }

  /**
   * Login page.
   *
   * Sends the user to the homepage if they're already logged in. If they try to
   * login, validates their info and redirects them to homepage.
   */
  function loginAction() {
    $UserModel = UserModel::singleton();
    $view_class_name = get_final_class_name('View');
    $View = $view_class_name::singleton();
    if ($UserModel->is_logged()) {
      $this->_redirect_to('');
    }
    else {
      if (array_key_exists('task', $_POST) && $_POST['task'] == 'login') {
        $UserModel->login($_POST['login'], $_POST['password']);
        $View->set_msg($UserModel->get_msg(), $UserModel->is_ok());
        if ($UserModel->is_ok() && $_POST['redirect_to']) {
          $this->_redirect_to($_POST['redirect_to']);
        }
        elseif ($UserModel->is_ok()) {
          $this->_redirect_to('user');
        }
      }
    }
    $View->set_title(_('Login'));
    $View->render(CoreModule::singleton()->get_class_path_name(__CLASS__), 
      "user", "login");
  }

  /**
   * Logout page.
   *
   * Simply logs the user out if they're logged in, then renders the login page.
   */
  function logoutAction() {
    $UserModel = UserModel::singleton();
    $view_class_name = get_final_class_name('View');
    $View = $view_class_name::singleton();
    if ($UserModel->is_logged()) {
      $UserModel->logout();
      $View->set_msg($UserModel->get_msg(), $UserModel->is_ok());
    }
    $this->_redirect_to('login');
  }

  /**
   * Edit profile page.
   */
  function editAction() {
    $UserModel = UserModel::singleton();
    $view_class_name = get_final_class_name('View');
    $View = $view_class_name::singleton();
    $this->login_required();
    if ($_POST) {
      if ($UserModel->update($_POST)) {
        $View->set_msg($UserModel->get_msg(), $UserModel->is_ok());
      }
      $View->set_msg($UserModel->get_msg(), $UserModel->is_ok());
    }
    $View->assign('user_login', $UserModel->get_login());
    $View->assign('user_name', $UserModel->get_name());
    $View->set_title(_('Update Information'));
    $View->render(CoreModule::singleton()->get_class_path_name(__CLASS__), 
      "user", "edit");
  }

  /**
   * User registration page.
   */
  function registerAction($params = null) {
    $UserModel = UserModel::singleton();
    $view_class_name = get_final_class_name('View');
    $View = $view_class_name::singleton();

    if ($params) {
      $View->render(CoreModule::singleton()->get_class_path_name(__CLASS__), 
        "site", "error");
      return;
    }

    if ($_POST) {
      if ($UserModel->create($_POST)) {
        $View->set_msg($UserModel->get_msg(), $UserModel->is_ok());
        if (array_key_exists('redirect_to', $_POST))
          $this->_redirect_to($_POST['redirect_to']);
        else
          $this->_redirect_to('');
      }
      $View->set_msg($UserModel->get_msg(), $UserModel->is_ok());
      $View->assign('login', $_POST['login']);
      $View->assign('name', $_POST['name']);
    }
    $View->set_title(_('Register'));
    $View->render(CoreModule::singleton()->get_class_path_name(__CLASS__), 
      "user", "register");
  }

  /**
   * User information page.
   */
  function infoAction() {
    //$this->login_required();

    $view_class_name = get_final_class_name('View');
    $View = $view_class_name::singleton();
    
    $Router = CoreRouter::singleton();
    $UserModel = UserModel::singleton();
    $user_info = $UserModel->get_user_info(
      $Router->get_current_route()->get_params()['id']);
    
    if (!$user_info) {
      $Config = get_final_class_name('Config');
      $default_controller_class_name = '\\IrisPHPFramework\\'.
        ucfirst($Config::$router_default_controller).
        $Config::$controller_postfix;
      $default_controller_file_name = strtolower(
        $Config::$router_default_controller.$Config::$controller_postfix);
      require_once ProjectConfig::module_path().'/controller/'.
        $default_controller_file_name.'.php';
      $default_controller_class_name::errorAction();
    }
    
    $View->set_title(_('User profile'));
    $View->assign('user_name', $user_info['name']);
    $View->assign('user_login', $user_info['login']);

    $View->render(CoreModule::singleton()->get_class_path_name(__CLASS__), 
      "user", "info");
  }
}

?>