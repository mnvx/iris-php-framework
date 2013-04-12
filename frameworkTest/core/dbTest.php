<?php
namespace IrisPHPFramework;

require_once 'framework/core/singleton.php';
require_once 'framework/core/dbinterface.php';
require_once 'framework/core/dblist.php';
require_once 'framework/core/config.php';

require_once 'framework/module/project/config.php';

/**
 * CoreDB Class
 *
 * Queries for interactions with database
 *
 * @license MIT, http://opensource.org/licenses/mit-license.php
 */

class CoreDBTest extends \PHPUnit_Framework_TestCase {

  /**
   * Get database connection by sinonym (by key in db list)
   */
  public function test_get_db_connection()
  {
    $db = CoreDB::singleton();

    $db_connect = new \PDO(
      str_replace('[#base_path#]', Config::base_path(), Config::$db['dsn']), 
      Config::$db['username'], 
      Config::$db['password'], 
      Config::$db['driver_options']
    );
    $db_connect->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

    $db->offsetSet('db', $db_connect);
    $this->assertEquals($db_connect, $db->get_db_connection('db'));
    $this->assertEquals(null, $db->get_db_connection('not_exists_db'));
    $this->assertEquals('IrisPHPFramework\\CoreDB::get_db_connection: '.
      _('Database with index').' "not_exists_db" '._('was not found').'.', 
      $db->get_msg());
    $this->assertEquals($db_connect, $db->get_db_connection());

    $db->offsetSet('db2', $db_connect);
    $this->assertEquals(null, $db->get_db_connection());
    $this->assertEquals(
      'Database info was not found in query IrisPHPFramework\\CoreDB::get_db_connection', 
      $db->get_msg());
    $this->assertEquals($db_connect, $db->get_db_connection('db'));
    $this->assertEquals($db_connect, $db->get_db_connection('db2'));

    $db->destroy();
    $db = CoreDB::singleton();
  }
  
  /**
   * Execute query on selected db
   */
  public function test_run_query()
  {
    $db = CoreDB::singleton();

    $this->assertEquals(null, 
      $db->run_query('select name from users where id=:id', array('id' => 1)));
    $this->assertEquals(_('Database was not found').': ""; IrisPHPFramework\\CoreDB::run_query', 
      $db->get_msg());

    $db_connect = new \PDO(
      str_replace('[#base_path#]', Config::base_path(), Config::$db['dsn']), 
      Config::$db['username'], 
      Config::$db['password'], 
      Config::$db['driver_options']
    );
    $db_connect->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

    $db->offsetSet('db', $db_connect);
    $this->assertEquals(array(array('name' => 'mnv', 0 => 'mnv')), 
      $db->run_query('select name from users where id=:id', array('id' => 1))->fetchAll());

    $db->destroy();
    $db = CoreDB::singleton();
  }
  
  /**
   * @expectedException PDOException
   */
  public function test_exception_execution()
  {
    $db = CoreDB::singleton();

    $db_connect = new \PDO(
      str_replace('[#base_path#]', Config::base_path(), Config::$db['dsn']), 
      Config::$db['username'], 
      Config::$db['password'], 
      Config::$db['driver_options']
    );
    $db_connect->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

    $db->offsetSet('db', $db_connect);
    $db->run_query('select name from users where id=:id', array('id1' => 1));

    $db->destroy();
    $db = CoreDB::singleton();
  }
  
  /**
   * @expectedException PDOException
   */
  public function test_exception_prepare()
  {
    $db = CoreDB::singleton();

    $db_connect = new \PDO(
      str_replace('[#base_path#]', Config::base_path(), Config::$db['dsn']), 
      Config::$db['username'], 
      Config::$db['password'], 
      Config::$db['driver_options']
    );
    $db_connect->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

    $db->offsetSet('db', $db_connect);
    $db->run_query('select1 name from users where id=:id', array('id' => 1));

    $db->destroy();
    $db = CoreDB::singleton();
  }
  
  
}

?>