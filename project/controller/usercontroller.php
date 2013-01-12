<?php
namespace IrisPHPFramework;

/**
 * User Controller
 *
 * This controller contains all the actions the user may perform that deals with their
 * account.
 */

class UserController extends Controller {

  /**
   * Default user profile page.
   */
  function indexAction() {
    $user = UserModel::singleton();
    $template = TemplateModel::singleton();
    $this->login_required();

    $template->assign('user_name', $user->name);
    $template->assign('user_login', $user->login);
    $template->set_title('My Profile');
    $template->render("user", "profile");
  }

  /**
   * Login page.
   *
   * Sends the user to the homepage if they're already logged in. If they try to
   * login, validates their info and redirects them to homepage.
   */
  function loginAction() {
    $user = UserModel::singleton();
    $template = TemplateModel::singleton();
    if ($user->is_logged) {
      $this->return_to('');
    }
    else {
      if (array_key_exists('task', $_POST) && $_POST['task'] == 'login') {
        $user->login($_POST['login'],$_POST['password']);
        $template->set_msg($user->msg, $user->ok);
        if ($user->ok && $_POST['return_to']) {
          $this->return_to($_POST['return_to']);
        }
        elseif ($user->ok) {
          $this->return_to('user');
        }
      }
    }
    $template->set_title('Login');
    $template->render("user", "login");
  }

  /**
   * Logout page.
   *
   * Simply logs the user out if they're logged in, then renders the login page.
   */
  function logoutAction() {
    $user = UserModel::singleton();
    $template = TemplateModel::singleton();
    if($user->is_logged()) {
      $user->logout();
      $template->set_msg($user->msg, $user->ok);
    }
    $this->return_to('login');
  }

  /**
   * Edit profile page.
   */
  function editAction() {
    $user = UserModel::singleton();
    $template = TemplateModel::singleton();
    $this->login_required();
    if ($_POST) {
      if ($user->update($_POST)) {
        $template->set_msg($user->msg, $user->ok);
      }
      $template->set_msg($user->msg, $user->ok);
    }
    $template->assign('user_login',$user->login);
    $template->assign('user_name',$user->name);
    $template->set_title('Update Information');
    $template->render("user","edit");
  }

  /**
   * User registration page.
   */
  function registerAction($params = null) {
    $user = UserModel::singleton();
    $template = TemplateModel::singleton();

    if ($params) {
      $template->render("site", "error");
      return;
    }

    if ($_POST) {
      if ($user->create($_POST)) {
        $template->set_msg($user->msg, $user->ok);
        if (array_key_exists('return_to', $_POST))
          $this->return_to($_POST['return_to']);
        else
          $this->return_to('');
      }
      $template->set_msg($user->msg, $user->ok);
      $template->assign('login',$_POST['login']);
      $template->assign('name',$_POST['name']);
    }
    $template->set_title('Register');
    $template->render("user", "register");
  }

}

?>