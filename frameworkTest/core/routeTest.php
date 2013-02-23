<?php
namespace IrisPHPFramework;

require_once 'framework/core/route.php';

/**
 * Route
 *
 * @license MIT, http://opensource.org/licenses/mit-license.php
 */

class CoreRouteTest extends \PHPUnit_Framework_TestCase {

  public function __construct()
  {
    if (!session_id()) {
      session_start();
    }
  }

  public function testis_matched()
  {
    $r = new CoreRoute(array(
      'pattern' =>'/',
      'controller' => 'site',
      'action' => 'home',
      'caching' => true,
    ), '/user');
    $this->assertEquals(false, $r->is_matched());

    $r = new CoreRoute(array(
      'pattern' =>'/',
      'controller' => 'site',
      'action' => 'home',
      'caching' => true,
    ), '/');
    $this->assertEquals(true, $r->is_matched());

    $r = new CoreRoute(array(
      'pattern' =>'/vehicles/{vin}',
      'controller' => 'vehicle',
      'action' => 'history',
      'requirements' => array(
        'vin' => '[\dA-Z]{7}|[\dA-Z]{17}',
      )), '/vehicles/0A12312');
    $this->assertEquals(true, $r->is_matched());

    $r = new CoreRoute(array(
      'pattern' =>'/vehicles/{vin}',
      'controller' => 'vehicle',
      'action' => 'history',
      'requirements' => array(
        'vin' => '[\dA-Z]{7}|[\dA-Z]{17}',
      )), '/vehicles/0A-2312');
    $this->assertEquals(false, $r->is_matched());

    $r = new CoreRoute(array(
      'pattern' =>'/vehicles/{vin}',
      'controller' => 'vehicle',
      'action' => 'history',
      'requirements' => array(
      )), '/vehicles/0A-2312');
    $this->assertEquals(true, $r->is_matched());
  }

  public function testget_route()
  {
    $route = array(
      'pattern' =>'/vehicles/{vin}',
      'controller' => 'vehicle',
      'action' => 'history',
      'requirements' => array(
    ));
    $r = new CoreRoute($route, '/vehicles/0A-2312');
    $this->assertEquals($route, $r->get_route());
  }

  public function testget_params()
  {
    $route = array(
      'pattern' =>'/vehicles/{vin}',
      'controller' => 'vehicle',
      'action' => 'history',
      'requirements' => array(
    ));
    $r = new CoreRoute($route, '/vehicles/0A-2312');
    $this->assertEquals(array('vin' => '0A-2312'), $r->get_params());

    $route = array(
      'pattern' =>'/vehicles/{vin}',
      'controller' => 'vehicle',
      'action' => 'history',
      'requirements' => array(
    ));
    $r = new CoreRoute($route, '/vehicle/0A-2312');
    $this->assertEquals(array(), $r->get_params());

    $route = array(
      'pattern' =>'/vehicles',
      'controller' => 'vehicle',
      'action' => 'history',
    );
    $r = new CoreRoute($route, '/vehicles');
    $this->assertEquals(array(), $r->get_params());
  }

  public function testget_controller_name()
  {
    $route = array(
      'pattern' =>'/vehicles/{vin}',
      'controller' => 'vehicle',
      'action' => 'history',
      'requirements' => array(
    ));
    $r = new CoreRoute($route, '/vehicles/0A-2312');
    $this->assertEquals('vehicle', $r->get_controller_name());
  }

  public function testget_action_name()
  {
    $route = array(
      'pattern' =>'/vehicles/{vin}',
      'controller' => 'vehicle',
      'action' => 'history',
      'requirements' => array(
    ));
    $r = new CoreRoute($route, '/vehicles/0A-2312');
    $this->assertEquals('history', $r->get_action_name());
  }
}

?>