<?php
namespace IrisPHPFramework;

/**
 * Singleton Trait
 *
 * With support of late binding, http://habrahabr.ru/post/85852/
 *
 * @license MIT, http://opensource.org/licenses/mit-license.php
 */

trait Singleton {
 
  /**
   * Collection of instances
   * @var array
   */
  private static $_aInstance = array();
 
  /**
   * Get instance of class
   */
  public static function singleton() 
  {
    // Get name of current class
    $sClassName = get_called_class();
 
    // Create new instance if necessary
    if (!isset(self::$_aInstance[$sClassName])) {
      self::$_aInstance[ $sClassName ] = new $sClassName();
    }
    $oInstance = self::$_aInstance[$sClassName];
    
    return $oInstance;
  }
 
  /**
   * Private final clone method
   */
  final private function __clone() 
  {
  }
}

?>