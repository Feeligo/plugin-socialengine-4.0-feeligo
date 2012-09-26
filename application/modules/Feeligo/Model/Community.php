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
 * @package    Feeligo_Model_Adapter_User
 * @copyright  Copyright 2012 Feeligo
 * @license    
 */

require_once(str_replace('//','/',dirname(__FILE__).'/').'../Common/models/community.php'); 
 
class Feeligo_Model_Community extends FeeligoCommunity {
 
  /**
   * Accessor for the SE viewer
   */
  public function viewer() {
    if (!isset($this->_adapter_viewer)) {
      $this->_adapter_viewer = new Feeligo_Model_Adapter_User(Engine_Api::_()->user()->getViewer());
    }
    return $this->_adapter_viewer;
  }
  
  /**
   * Accessor for the SE subject (only if exists and is different from viewer)
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
 
  /*
   * Accessor to the Users adapter
   */
  public function users() {
    if (!isset($this->_users)) {
      $this->_users = new Feeligo_Model_Selector_Users();
    }
    return $this->_users;
  }
  
  /*
   * Accessor to the Actions adapter
   */
  public function actions() {
    if (!isset($this->_actions)) {
      $this->_actions = new Feeligo_Model_Selector_Actions();
    }
    return $this->_actions;
  }
 
}