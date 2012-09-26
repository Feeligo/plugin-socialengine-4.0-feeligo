<?php
/**
 * Feeligo
 *
 * @category   Feeligo
 * @package    Feeligo_Api
 * @copyright  Copyright 2012 Feeligo
 * @license    
 * @author     Davide Bonapersona <tech@feeligo.com>
 */

/**
 * @category   Feeligo
 * @package    Feeligo_Api_Feeligo
 * @copyright  Copyright 2012 Feeligo
 * @license    
 */
require_once(str_replace('//','/',dirname(__FILE__).'/').'../Common/api.php'); 
 
class Feeligo_Api_Feeligo extends FeeligoApi
{
  
  protected function __construct() {
    parent::__construct();
    $this->_community = new Feeligo_Model_Community();
  }
  
  /**
   * accessor for the Community adapter
   */
  public function community() {
    return $this->_community;
  }
  
  /**
   * Whether there is a viewer (existing, logged in user)
   */
  public function has_viewer() {
    $identity = Engine_Api::_()->user()->getViewer()->getIdentity();
    return $identity !== null && $identity != 0;
  }
  
  /**
   * Whether there is a subject (exists and different from the viewer)
   */
  public function has_subject() {
    return Engine_Api::_()->core()->hasSubject('user');
  }
  
  /**
   * Get or create the current api instance
   * 
   * @return FeeligoApi
   */
  public static function _() {
    if( is_null(self::$_instance) ) {
      self::$_instance = new self();
    }
    return self::$_instance;
  }
  
}