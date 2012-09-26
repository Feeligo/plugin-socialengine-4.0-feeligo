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

require_once(str_replace('//','/',dirname(__FILE__).'/').'../../Common/models/selector/actions.php'); 
require_once(str_replace('//','/',dirname(__FILE__).'/').'../../Common/models/entity/element/collection/set.php');
require_once(str_replace('//','/',dirname(__FILE__).'/').'../../Common/models/entity/not_found_exception.php');
 
class Feeligo_Model_Selector_Actions implements FeeligoSelectorActions {
 
  public function __construct($table = null, $select = null) {
    $this->_table = $table !== null ? $table : Engine_Api::_()->getItemTable('user');
    $this->_select = $select !== null ? $select : $this->table()->select();
    
    $this->_api = Engine_Api::_()->getItemApi('user'); // User_Api_Core
  }
  
  public function table() {
    return $this->_table;
  }
  
  /* creates a new action */
  public function create($data) {
    return Feeligo_Model_Adapter_Action::create($data);
  }
  
}