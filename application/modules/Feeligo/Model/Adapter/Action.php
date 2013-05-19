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
 * @package    Feeligo_Model_Adapter_Action
 * @copyright  Copyright 2012 Feeligo
 * @license    
 */

require_once(str_replace('//','/',dirname(__FILE__).'/').'../../sdk/interfaces/action_adapter.php');
 
 
class Feeligo_Model_Adapter_Action implements FeeligoActionAdapter {
 
  public function __construct($se_action) {
    $this->_adaptee = $se_action;
  }
  

  /**
   * returns the unique identifier of the action
   *
   * @return string
   */
  public function id() {
    return $this->_adaptee->action_id;
  }
  

  /**
   * returns the adapted SocialEngine action
   */
  public function action() {
    return $this->_adaptee;
  }

}