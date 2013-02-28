<?php
namespace IrisPHPFramework;

/**
 * User Controller
 *
 * This controller contains all the actions the user may perform that deals with their
 * account.
 */

class UserController extends ProjectController {

  /**
   * Default user profile page.
   */
  function indexAction() {
    $class_view = get_final_class_name('View');
    $View = $class_view::singleton();
    $this->login_required();

    $View->set_title('My Profile');
    $View->render("user", "profile");
  }

  /**
   * Login page.
   *
   * Sends the user to the homepage if they're already logged in. If they try to
   * login, validates their info and redirects them to homepage.
   */
  function loginAction() {
    $user = UserModel::singleton();
    $class_view = get_final_class_name('View');
    $View = $class_view::singleton();
    if ($user->is_logged()) {
      $this->_redirect_to('');
    }
    else {
      if (array_key_exists('task', $_POST) && $_POST['task'] == 'login') {
        $user->login($_POST['login'], $_POST['password']);
        $View->set_msg($user->get_msg(), $user->is_ok());
        if ($user->is_ok() && $_POST['redirect_to']) {
          $this->_redirect_to($_POST['redirect_to']);
        }
        elseif ($user->is_ok()) {
          $this->_redirect_to('user');
        }
      }
    }
    $View->set_title('Login');
    $View->render("user", "login");
  }

  /**
   * Logout page.
   *
   * Simply logs the user out if they're logged in, then renders the login page.
   */
  function logoutAction() {
    $user = UserModel::singleton();
    $class_view = get_final_class_name('View');
    $View = $class_view::singleton();
    if ($user->is_logged()) {
      $user->logout();
      $View->set_msg($user->get_msg(), $user->is_ok());
    }
    $this->_redirect_to('login');
  }

  /**
   * Edit profile page.
   */
  function editAction() {
    $user = UserModel::singleton();
    $class_view = get_final_class_name('View');
    $View = $class_view::singleton();
    $this->login_required();
    if ($_POST) {
      if ($user->update($_POST)) {
        $View->set_msg($user->get_msg(), $user->is_ok());
      }
      $View->set_msg($user->get_msg(), $user->is_ok());
    }
    $View->assign('user_login', $user->get_login());
    $View->assign('user_name', $user->get_name());
    $View->set_title('Update Information');
    $View->render("user", "edit");
  }

  /**
   * User registration page.
   */
  function registerAction($params = null) {
    $user = UserModel::singleton();
    $class_view = get_final_class_name('View');
    $View = $class_view::singleton();

    if ($params) {
      $View->render("site", "error");
      return;
    }

    if ($_POST) {
      if ($user->create($_POST)) {
        $View->set_msg($user->get_msg(), $user->is_ok());
        if (array_key_exists('redirect_to', $_POST))
          $this->_redirect_to($_POST['redirect_to']);
        else
          $this->_redirect_to('');
      }
      $View->set_msg($user->get_msg(), $user->is_ok());
      $View->assign('login', $_POST['login']);
      $View->assign('name', $_POST['name']);
    }
    $View->set_title('Register');
    $View->render("user", "register");
  }

  /**
   * User information page.
   */
  function infoAction() {
    //$this->login_required();

    $class_view = get_final_class_name('View');
    $View = $class_view::singleton();
    
    $router = CoreRouter::singleton();
    $user = UserModel::singleton();
    $user_info = $user->get_user_info($router->get_current_route()->get_params()['id']);
    
    if (!$user_info) {
      $class_config = get_final_class_name('Config');
      $default_controller_class_name = '\\IrisPHPFramework\\'.ucfirst($class_config::$router_default_controller).$class_config::$controller_postfix;
      $default_controller_file = strtolower($class_config::$router_default_controller.$class_config::$controller_postfix);
      require_once $class_config::project_path().'/controller/'.$default_controller_file.'.php';
      $default_controller_class_name::errorAction();
    }
    
    $View->set_title('User profile');
    $View->assign('user_name', $user_info['name']);
    $View->assign('user_login', $user_info['login']);

    $View->render("user", "info");
  }
}

?>