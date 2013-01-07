<?php

/**
 * User Model
 *
 * This class contains all of the functions used for creating, managing and deleting
 * users.
 *
 * @package    jQuery Mobile PHP MVC Micro Framework
 * @author     Monji Dolon <md@devgrow.com>
 * @copyright  2011-2012 Monji Dolon
 * @license    http://www.gnu.org/licenses/gpl.html  GNU General Public License (GPL) v3
 * @link       http://devgrow.com/jquery-mobile-php-mvc-framework/
 */

class UserModel {

  use Singleton;

  protected $user_id;
  public $name;
  public $email;
  protected $password;
  public $ok;
  public $msg;
  public $is_logged;

  /**
   * Set all internal variables to 'Guest' status, then check to see if
   * a user session or cookie exists.
   */
  function __construct(){
    $this->user_id = 0;
    $this->email = "Guest";
    $this->name = "Guest";
    $this->ok = false;

    $this->check();

    return $this->ok;
  }

  /**
   * Create a user and by default, log them in once the account has been created.
   *
   * @param   $info       An array that contains the following info about the user:
   *                       - name, email, password, password2 (password repeated), status (optional)
   * @param   $login      Bool, whether or not to log the user in after creating account.
   */
  function create($info, $login = true){
    $db = DataBases::singleton();

    // Escape the info fields and hash the password using the salt specified in config.php
    $name = $info['name'];
    $email = $info['email'];
    $password = hash(Config::$hash_function, $info['password'] . Config::$password_salt);

    // If user status isn't set, assume default status (1)
    $status = array_key_exists('status', $info) && $info['status'] ? $info['status'] : 1;

    // Store the IP address that the user create's the account with.
    $create_ip = $_SERVER['REMOTE_ADDR'];

    // Reset flag used for error detection.
    $this->ok = false;
    
    // Validate all of the user input fields.
    if(!$info['name'] || !$info['email'] || !$info['password'] || !$info['password2']){
      $this->msg = _("Error! All fields are required.");
      return false;
    }
    elseif($info['password'] != $info['password2']){
      $this->msg = _("Error! Passwords do not match.");
      return false;
    }
    elseif(!$this->validEmail($email)){
      $this->msg = _("Error! Please enter a valid e-mail address.");
      return false;
    }
    
    // Check to see if a user with that email address already exists.       
    $query = $db->get_user_info('db', $email);
    if (!$query) {
      $this->msg = $db->get_msg();
      return false;
    }
    
    $rows = $query->fetchAll();
    if (count($rows) > 0){
      $this->msg = _("Error! E-mail address is already in use.");
    }
    else {
      // User doesn't exist, so create a new account!
      if (!$db->insert_user('db', array(
        'name' => $name,
        'email' => $email,
        'password' => $password,
        'status' => $status,
        'create_ip' => $create_ip,
      ))) {
        $this->msg = _("Error! Can't create the user.").' '.$db->get_msg();
        return false;
      }
      $this->msg = _("User successfully added.");
      $this->ok = true;
      if ($login) {
        $this->login($info['email'], $info['password']);
      }
      return true;
    }
    return false;
  }
  
  /**
   * Update a user's information.
   *
   * @param   $info       An array that contains the following info about the user:
   *                       - name, email, password, password2 (password repeated), status (optional)
   */
  function update($info) {
    $db = DataBases::singleton();

    // Reset our error detection flag, which is used to set the status message later on.
    $this->ok = false;
    
    // Escape variables that are present by default.
    $name = $info['name'];
    $email = $info['email'];
    
    // Validate email address again.
    if(!$this->validEmail($info['email'])) {
      $this->msg = _("Error! Please enter a valid e-mail address.");
      return false;
    }

    // Start building the SQL query with the data submitted so far.
    $sql = "name='$name', email='$email'";
    $fields = array(
      'name' => $name,
      'email' => $email,
    );

    // If a password has been entered, validate it, re-hash it and add it to the SQL query.
    if ($info['password']) {
      if ($info['password'] != $info['password2']) {
        $this->msg = _("Error! Passwords do not match.");
        return false;
      }
      $password = hash(Config::$hash_function, $info['password'] . Config::$password_salt);
      $fields['password'] = $password;
    }

    // Successfully updated the user data.
    if ($db->update_user('db', $this->user_id, $fields)) {
      // Let the user know via a cheeky message (OK not really cheeky).
      $this->msg = _("Info successfully updated.");

      // Set user status flag back to true, peace has been restored.
      $this->ok = true;

      // Set new email and password info in the session and cookies.
      $_SESSION['auth_email'] = $email;
      if ($info['password']) {
        $_SESSION['auth_secret'] = $password;
      }

      // Update local variables to reflect new changes.
      $this->name = $name;
      $this->email = $email;

      return true;
    } 
    else {
      // There seems to have been a problem with the query somewhere.
      $this->msg = _("There was a problem, please try again.").' '.$db->get_msg();
    }
    return false;
  }

   /**
   * Function used to let hte user login, checking their email and password against
   * what's stored in the database.
   *
   * @param   $email      The user's email address.
   * @param   $password   The user's password, directly from POST.
   */
  function login($email, $password) {
    $db = DataBases::singleton();

    // One of the fields is missing, deliver an error message.
    if (!$email || !$password) {
      $this->msg = _("Error! Both E-mail and Password are required to login.");
      return false;
    }
    
    // Set our user flag to false.
    $this->ok = false;

    // Get user data using the email address supplied.
    $query = $db->get_user_info('db', $email);
    if (!$query) {
      $this->msg = $db->get_msg();
      return false;
    }

    // Fetch all results and process the data if the row exists.
    $results = $query->fetchAll();
    
    if (count($results) == 1) {
      // Get the salted and hashed password stored in the database.
      $db_password = $results[0]['password'];

      // Salt the current password and if it matches the stored password,
      // proceed with logging in the user.
      if (hash(Config::$hash_function, $password . Config::$password_salt) == $db_password) {

        // Set session and cookie information.
        $_SESSION['auth_email'] = $email;
        $_SESSION['auth_secret'] = hash(Config::$hash_function, $results[0]['id'] . $results[0]['email']);

        // Set local variables with the user's info.
        $this->user_id = $results[0]['id'];
        $this->name = $results[0]['name'];
        $this->email = $email;
        $this->ok = true;
        $this->is_logged = true;
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
    return false;
  }
  
  /**
   * This function checks the session/cookie info to see if it's real by comparing it
   * to what is stored in the database.
   *
   * @param   $email      The user's email address stored in session/cookie.
   * @param   $secret     The user's secret hash, a combination of their user id (from DB)
   *                      and their email address.
   */
  function check() {
    if (!array_key_exists('auth_email', $_SESSION) || !array_key_exists('auth_secret', $_SESSION)) {
      $this->msg = _('Authorization is necessary');
      return false;
    }
    
    $email = $_SESSION['auth_email'];
    $secret = $_SESSION['auth_secret'];
    if (empty($email) || empty($secret)) {
      $this->msg = _('Authorization is necessary');
      return false;
    }

    $db = DataBases::singleton();

    // Get user data using the email address supplied.
    $query = $db->get_user_info('db', $email);
    if (!$query) {
      $this->msg = $db->get_msg();
      return false;
    }

    $results = $query->fetchAll();
    if (count($results) == 1) {
      if (hash(Config::$hash_function, $results[0]['id'] . $results[0]['email']) == $secret) {
        $this->user_id = $results[0]['id'];
        $this->email = $email;
        $this->name = $results[0]['name'];
        $this->ok = true;
        $this->is_logged = true;
        return true;
      }
    }
    return false;
  }

  /**
   * Check to see if the user is logged in based on their session data.
   */
  function is_logged() {
    if ($_SESSION['auth_email']) {
      return true;
    }
    return false;
  }
  
  /**
   * Log out the current user by setting all the local variables to their
   * default values and resetting our PHP session and cookie info.
   */ 
  function logout(){
    $this->user_id = 0;
    $this->email = "Guest";
    $this->name = "Guest";
    $this->ok = true;
    $this->msg = _("You have been logged out!");
    $this->is_logged = false;
    
    $_SESSION['auth_email'] = "";
    $_SESSION['auth_secret'] = "";
  }

  /**
   * Validate the user's email address.
   * Courtesy LinuxJournal.com : http://www.linuxjournal.com/article/9585?page=0,3
   *
   * @param   $email      The email address to validate.
   */
  function validEmail($email){
    return filter_var($email, FILTER_VALIDATE_EMAIL);
  }
}

?>