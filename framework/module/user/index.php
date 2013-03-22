<?php
namespace IrisPHPFramework;

/**
 * Start point of Framework (project section)
 *
 * Contains list of included modules
 *
 * @license MIT, http://opensource.org/licenses/mit-license.php
 */

$slash = CoreConfig::get_slash();

// Configuration
require_once(__DIR__.$slash.'config.php');

$module_path = UserConfig::module_path();

require_once($module_path.$slash.'model'.$slash.'user.php');
require_once($module_path.$slash.'db.php');

// Register current module
$Module = CoreModule::singleton();
$Module->add_module(UserConfig::$module_structure, 
  basename($module_path), 'UserConfig', function() {
    $userdb_class_name = get_final_class_name('UserDB');
    $UserDB = $userdb_class_name::singleton();
    $UserDB->set_db_list();

    // Check the database structure
    $UserDB->check_db('db');

    // User model
    $UserModel = UserModel::singleton();

    // View
    $view_class_name = get_final_class_name('View');
    $View = $view_class_name::singleton();
    $View->assign('user_name', $UserModel->get_name());
    $View->assign('user_login', $UserModel->get_login());
    $View->register_custom_object('user', $UserModel);
  });

?>