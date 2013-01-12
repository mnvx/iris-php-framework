<?php
namespace IrisPHPFramework;

/**
 * Start point of Framework (lib section)
 *
 * Contains list of included modules
 *
 * @license MIT, http://opensource.org/licenses/mit-license.php
 */

require_once(Config::lib_dir().'/model/user.php');
require_once(Config::lib_dir().'/controller.php');
require_once(Config::lib_dir().'/app.php');
require_once(Config::lib_dir().'/db.php');


$app = new App();

?>