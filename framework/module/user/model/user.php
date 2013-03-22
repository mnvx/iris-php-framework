<?php
namespace IrisPHPFramework;

/**
 * User Model
 *
 * This class contains all of the functions used for creating, managing and deleting
 * users.
 */

class UserModel {

  use Singleton;

  protected $user_id;
  protected $login;
  protected $name;
  protected $password;
  protected $msg;
  protected $ok;

  /**
   * Set all internal variables to 'Guest' status, then check to see if
   * a user session exists.
   */
  function __construct() 
  {
    $this->user_id = 0;
    $this->login = "Guest";
    $this->name = "Guest";
    $this->ok = false;

    $this->check();

    return $this->ok;
  }
  
  public function get_password_hash($password)
  {
    $Config = get_final_class_name('Config');
    return hash_case($password.$Config::$password_salt);
  }

  /**
   * Create a user and by default, log them in once the account has been created.
   *
   * @param   $info       An array that contains the following info about the user:
   *                       - name, login, password, password2 (password repeated), 
   *                         status (optional)
   * @param   $login      Bool, whether or not to log the user in after creating 
   *                      account.
   */
  function create($info, $login = true) 
  {
    $db_class_name = get_final_class_name('UserDB');
    $DB = $db_class_name::singleton();

    // Escape the info fields and hash the password using the salt specified 
    // in Config class
    $name = $info['name'];
    $login = $info['login'];
    $password = $this->get_password_hash($info['password']);

    // If user status isn't set, assume default status (1)
    $status = array_key_exists('status', $info) 
      && $info['status'] ? $info['status'] : 1;

    // Store the IP address that the user create's the account with.
    $create_ip = $_SERVER['REMOTE_ADDR'];

    // Reset flag used for error detection.
    $this->ok = false;
    
    // Validate all of the user input fields.
    if (!$info['name'] 
    || !$info['login'] 
    || !$info['password'] 
    || !$info['password2']) {
      $this->msg = _("Error! All fields are required.");
      return false;
    }
    elseif ($info['password'] != $info['password2']) {
      $this->msg = _("Error! Passwords do not match.");
      return false;
    }
    elseif (!$this->validEmail($login)) {
      $this->msg = _("Error! Please enter a valid e-mail address.");
      return false;
    }
    
    // Check to see if a user with that login address already exists.       
    $query = $DB->get_user_info('db', $login);
    if (!$query) {
      $this->msg = $DB->get_msg();
      return false;
    }
    
    $rows = $query->fetchAll();
    if (count($rows) > 0) {
      $this->msg = _("Error! E-mail address is already in use.");
    }
    else {
      // User doesn't exist, so create a new account!
      if (!$DB->insert_user('db', array(
        'name' => $name,
        'login' => $login,
        'password' => $password,
        'status' => $status,
        'create_ip' => $create_ip,
      ))) {
        $this->msg = _("Error! Can't create the user.").' '.$DB->get_msg();
        sleep(1); // Sleep one second (protection against crasy bots)
        return false;
      }
      $this->msg = _("User successfully added.");
      $this->ok = true;
      sleep(1); // Sleep one second (protection against crasy bots)
      if ($login) {
        $this->login($info['login'], $info['password']);
      }
      return true;
    }
    return false;
  }
  
  /**
   * Update a user's information.
   *
   * @param   $info       An array that contains the following info about the user:
   *                       - name, login, password, password2 (password repeated), 
   *                         status (optional)
   */
  function update($info) 
  {
    $db_class_name = get_final_class_name('UserDB');
    $DB = $db_class_name::singleton();

    // Reset our error detection flag, which is used to set the status message 
    // later on.
    $this->ok = false;
    
    // Escape variables that are present by default.
    $name = $info['name'];
    $login = $info['login'];
    
    // Validate login address again.
    if(!$this->validEmail($info['login'])) {
      $this->msg = _("Error! Please enter a valid e-mail address.");
      return false;
    }

    // Start building the SQL query with the data submitted so far.
    $sql = "name='$name', login='$login'";
    $fields = array(
      'name' => $name,
      'login' => $login,
    );

    // If a password has been entered, validate it, re-hash it and add it 
    // to the SQL query.
    if ($info['password']) {
      if ($info['password'] != $info['password2']) {
        $this->msg = _("Error! Passwords do not match.");
        return false;
      }
      $password = $this->get_password_hash($info['password']);
      $fields['password'] = $password;
    }

    // Successfully updated the user data.
    if ($DB->update_user('db', $this->user_id, $fields)) {
      // Let the user know via a cheeky message (OK not really cheeky).
      $this->msg = _("Info successfully updated.");

      // Set user status flag back to true, peace has been restored.
      $this->ok = true;

      // Set new login and password info in the session.
      $_SESSION['auth_login'] = $login;
      if ($info['password']) {
        $_SESSION['auth_secret'] = $password;
      }

      // Update local variables to reflect new changes.
      $this->name = $name;
      $this->login = $login;

      return true;
    } 
    else {
      // There seems to have been a problem with the query somewhere.
      $this->msg = _("There was a problem, please try again.").' '.$DB->get_msg();
    }
    return false;
  }

   /**
   * Function used to let hte user login, checking their login and password against
   * what's stored in the database.
   *
   * @param   $login      The user's login address.
   * @param   $password   The user's password, directly from POST.
   */
  function login($login, $password) 
  {
    $Config = get_final_class_name('Config');
    $db_class_name = get_final_class_name('UserDB');
    $DB = $db_class_name::singleton();
    
    // Set our user flag to false.
    $this->ok = false;

    // One of the fields is missing, deliver an error message.
    if (!$login || !$password) {
      $this->msg = _("Error! Both E-mail and Password are required to login.");
      return false;
    }

    // Get user data using the login address supplied.
    $query = $DB->get_user_info('db', $login);
    if (!$query) {
      $this->msg = $DB->get_msg();
      return false;
    }

    // Fetch all results and process the data if the row exists.
    $results = $query->fetchAll();
    
    if (count($results) == 1) {
      // Get the salted and hashed password stored in the database.
      $db_password = $results[0]['password'];

      // Salt the current password and if it matches the stored password,
      // proceed with logging in the user.
      if ($this->get_password_hash($password) == $db_password) {

        // Set session information.
        $_SESSION['auth_login'] = $login;
        $_SESSION['auth_secret'] = 
          hash_case($Config::$hash_function, $results[0]['id'].$results[0]['login']);

        // Set local variables with the user's info.
        $this->user_id = $results[0]['id'];
        $this->name = $results[0]['name'];
        $this->login = $login;
        $this->ok = true;
        // Set status message.
        $this->msg = _("Login Successful!");
        return true;
      } 
      else {
        $this->msg = _("Error! Password is incorrect.");
      }
    } 
    else {
      $this->msg = _("Error! User does not exist.");
    }
    sleep(1); // Sleep one second (protection against crasy bots)
    return false;
  }
  
  /**
   * This function checks the session info to see if it's real by comparing it
   * to what is stored in the database.
   *
   * @param   $login      The user's login address stored in session.
   * @param   $secret     The user's secret hash, a combination of their user id 
   *                      (from DB) and their login address.
   */
  function check() 
  {
    $this->ok = false;
    if (!isset($_SESSION) 
    || !array_key_exists('auth_login', $_SESSION) 
    || !array_key_exists('auth_secret', $_SESSION)) {
      $this->msg = _('Authorization is necessary');
      return false;
    }
    
    $login = $_SESSION['auth_login'];
    $secret = $_SESSION['auth_secret'];
    if (empty($login) || empty($secret)) {
      $this->msg = _('Authorization is necessary');
      return false;
    }

    $db_class_name = get_final_class_name('UserDB');
    $DB = $db_class_name::singleton();

    // Get user data using the login address supplied.
    $query = $DB->get_user_info('db', $login);
    if (!$query) {
      $this->msg = $DB->get_msg();
      return false;
    }

    $results = $query->fetchAll();
    if (count($results) == 1) {
      $Config = get_final_class_name('Config');
      if (hash_case($Config::$hash_function, $results[0]['id'].$results[0]['login']) 
      == $secret) {
        $this->user_id = $results[0]['id'];
        $this->login = $login;
        $this->name = $results[0]['name'];
        $this->ok = true;
        return true;
      }
    }
    return false;
  }

  /**
   * Check to see if the user is logged in based on their session data.
   */
  function is_logged() 
  {
    if (isset($_SESSION)
    && array_key_exists('auth_login', $_SESSION) 
    && $_SESSION['auth_login']) {
      return true;
    }
    return false;
  }
  
  /**
   * Log out the current user by setting all the local variables to their
   * default values and resetting our PHP session info.
   */ 
  function logout() 
  {
    $this->user_id = 0;
    $this->login = "Guest";
    $this->name = "Guest";
    $this->ok = true;
    $this->msg = _("You have been logged out!");
    
    $_SESSION['auth_login'] = "";
    $_SESSION['auth_secret'] = "";

    unset($_SESSION['auth_login']);
    unset($_SESSION['auth_secret']);
  }

  /**
   * Validate the user's login address.
   *
   * @param   $login      The login address to validate.
   */
  function validEmail($login)
  {
    return filter_var($login, FILTER_VALIDATE_EMAIL);
  }

  function get_msg()
  {
    return $this->msg;
  }

  function is_ok()
  {
    return $this->ok;
  }

  function get_login()
  {
    return $this->login;
  }

  function get_name()
  {
    return $this->name;
  }

  function get_user_info($id) 
  {
    $db_class_name = get_final_class_name('UserDB');
    $DB = $db_class_name::singleton();
    $query = $DB->get_user_info_by_id('db', $id);
    if (!$query) {
      $this->msg = $DB->get_msg();
      return false;
    }

    $rows = $query->fetchAll();
    if (array_key_exists(0, $rows)) {
      return $rows[0];
    }
    return null;
  }
}

?>