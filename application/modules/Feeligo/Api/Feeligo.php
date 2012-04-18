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
require_once(str_replace('//','/',dirname(__FILE__).'/').'../Common/api/api.php'); 
 
class Feeligo_Api_Feeligo implements FeeligoApi
{
  const __community_api_key = FLG__community_api_key;
  const __community_secret = FLG__community_secret;
  const __remote_api_endpoint_url = FLG__server_url;
  
  /**
   * Accessors for Feeligo params
   */
  public function community_api_key() { 
    return self::__community_api_key;
  }
  public function community_secret() {
    return self::__community_secret;
  }
  public function remote_api_endpoint_url() {
    return self::__remote_api_endpoint_url;
  }
  
  /**
   * The singleton Api object
   *
   * @var Feeligo_Api_Feeligo
   */
  protected static $_instance;
  
  /**
   * Get or create the current api instance
   * 
   * @return Engine_Api
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
   * @return Engine_Api
   */
  public static function _() {
    return self::getInstance();
  }
  
  /**
   * constructor (cannot be called from outside)
   * 
   */
  protected function __construct() {
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
  public function user_viewer() {
    return $this->adapter_viewer()->user(); 
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
        if (!$this->user_viewer()->isSelf($u_subj)) {
          $this->_adapter_subject = new Feeligo_Model_Adapter_User($u_subj);
        }
      }
    }
    return $this->_adapter_subject;
  }
  public function user_subject() {
    return $this->has_subject() ? $this->adapter_subject()->user() : null;
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
  
}