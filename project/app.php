<?php

/**
 * App Class
 *
 * Initialisation of objects
 *
 * @license MIT, http://opensource.org/licenses/mit-license.php
 */

class App extends CoreApp {

  protected function custom_after_routing()
  {
    try {
      // Connecting to database
      $db = new PDO(
        str_replace('[#base_dir#]', Config::base_dir(), Config::$db['dsn']), 
        Config::$db['username'], 
        Config::$db['password'], 
        Config::$db['driver_options']
      );
      $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $databases = DataBases::singleton();
      $databases->offsetSet('db', $db);
      // Check the database structure
      $databases->check_db('db');
    } 
    catch (Exception $e) {
      //die($e);
    }
    
    // User model
    $user = UserModel::singleton();

    // Template
    $template = TemplateModel::singleton();
    $template->register_custom_object('user', $user);
  }
  
}

?>