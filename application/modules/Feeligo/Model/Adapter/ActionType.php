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
 * @package    Feeligo_Model_Adapter_ActionType
 * @copyright  Copyright 2012 Feeligo
 * @license    
 */

/**
 * Adapter class for SocialEngine actiontypes.
 */
 
class Feeligo_Model_Adapter_ActionType {
  
  function __construct($se_action_type) {
    $this->_adaptee = $se_action_type;
  }
  

  /**
   * Accessor for the `type` attribute of the underlying SocialEngine
   * actiontype
   *
   * @return string
   */
  public function type () {
    return $this->_adaptee->type;
  }
  

  /**
   * Persists the underlying SocialEngine actiontype to the database
   *
   * @return bool whether saving was successful
   */
  public function save () {
    return $this->_adaptee->save() !== null;
  }
  
}
