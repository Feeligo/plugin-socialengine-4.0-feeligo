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
require_once(str_replace('//','/',dirname(__FILE__).'/').'../sdk/lib/api.php');

class Feeligo_Api_Feeligo extends FeeligoApi {
  
  /**
   * tells whether a viewer is available in the current context
   * the viewer is the User which is currently logged in, when applicable
   *
   * @return bool
   */
  public function has_viewer() {
    return $this->viewer() !== null;
  }

  /**
   * Accessor for the viewer
   *
   * @return bool
   */    
  public function viewer() {
    if (!isset($this->_adapter_viewer) && ($id = Engine_Api::_()->user()->getViewer()->getIdentity()) !== null && $id != 0) {
      $this->_adapter_viewer = new Feeligo_Model_Adapter_User(Engine_Api::_()->user()->getViewer());
    }
    return $this->_adapter_viewer;
  }
  
  /**
   * tells whether a subject is available in the current context
   * the subject is the user which is currently being viewed, when applicable
   *
   * @return bool
   */
  public function has_subject() {
    return $this->subject() !== null;
  }

  /**
   * Accessor for the subject
   *
   * @return FeeligoUserAdapter
   */
  public function subject() {
    if (!isset($this->_adapter_subject)) {
      $this->_adapter_subject = null;
      if (Engine_Api::_()->core()->hasSubject('user')) {
        $u_subj = Engine_Api::_()->core()->getSubject('user');
        // ensure the subject is not the same User as the viewer
        if (!$this->viewer()->user()->isSelf($u_subj)) {
          $this->_adapter_subject = new Feeligo_Model_Adapter_User($u_subj);
        }
      }
    }
    return $this->_adapter_subject;
  }

  /**
   * Accessor for the website users
   *
   * @return FeeligoUsersSelector
   */
  public function users() {
    return new Feeligo_Model_Selector_Users();
  }

  /**
   * Accessor for user Actions
   *
   * @return FeeligoActionsSelector
   */
  public function actions() {
    return new Feeligo_Model_Selector_Actions();
  }
  
  /**
   * Singleton pattern: gets or creates a single instance of this class
   * 
   * @return Feeligo_Api_Feeligo
   */
  public static function getInstance() {
    if( is_null(self::$_instance) ) {
      self::$_instance = new self();
    }
    return self::$_instance;
  }

  /**
   * Shorthand for getInstance, allows to call Feeligo_Api_Feeligo::_()
   *
   * @return Feeligo_Api_Feeligo
   */
  public static function _() {
    return self::getInstance();
  }
  
}