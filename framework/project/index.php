<?php
namespace IrisPHPFramework;

/**
 * Start point of Framework (project section)
 *
 * Contains list of included modules
 *
 * @license MIT, http://opensource.org/licenses/mit-license.php
 */

require_once(Config::project_path().'/model/user.php');
require_once(Config::project_path().'/controller.php');
require_once(Config::project_path().'/application.php');
require_once(Config::project_path().'/db.php');

?>