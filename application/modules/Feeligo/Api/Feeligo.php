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
   * Accessor for the SE viewer
   */
  public function adapter_viewer() {
    if (!isset($this->_adapter_viewer)) {
      $this->_adapter_viewer = new Feeligo_Model_Adapter_User(Engine_Api::_()->user()->getViewer());
    }
    return $this->_adapter_viewer;
  }
  
  /**
   * Accessor for the SE subject (only if exists and is different from viewer)
   */
  public function adapter_subject() {
    if (!isset($this->_adapter_subject)) {
      $this->_adapter_subject = null;
      if (Engine_Api::_()->core()->hasSubject('user')) {
        $u_subj = Engine_Api::_()->core()->getSubject('user');
        // ensure the subject is not the same User as the viewer
        if (!$this->adapter_viewer()->user()->isSelf($u_subj)) {
          $this->_adapter_subject = new Feeligo_Model_Adapter_User($u_subj);
        }
      }
    }
    return $this->_adapter_subject;
  }
  
  /**
   * Whether there is a viewer (existing, logged in user)
   */
  public function has_viewer() {
    return $this->adapter_viewer()->user_exists();
  }
  
  /**
   * Whether there is a subject (exists and different from the viewer)
   */
  public function has_subject() {
    return $this->adapter_subject() !== null;
  }
  
  /**
   * Get or create the current api instance
   * 
   * @return FeeligoApi
   */
  public static function getInstance() {
    if( is_null(self::$_instance) ) {
      self::$_instance = new self();
    }
    return self::$_instance;
  }

  /**
   * Shorthand for getInstance
   *
   * @return FeeligoApi
   */
  public static function _() {
    return self::getInstance();
  }
  
}