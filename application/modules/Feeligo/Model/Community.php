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
 
  /*
   * Accessor to the Users adapter
   */
  public function users() {
    if (!isset($this->_users)) {
      $this->_users = new Feeligo_Model_Adapter_Users();
    }
    return $this->_users;
  }
  
  /*
   * Accessor to the Actions adapter
   */
  public function actions() {
    if (!isset($this->_actions)) {
      $this->_actions = new Feeligo_Model_Adapter_Actions();
    }
    return $this->_actions;
  }
 
}