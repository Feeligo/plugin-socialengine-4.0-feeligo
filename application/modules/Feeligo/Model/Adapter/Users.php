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

require_once(str_replace('//','/',dirname(__FILE__).'/').'../../Common/models/users.php'); 
require_once(str_replace('//','/',dirname(__FILE__).'/').'../../Common/models/entity/element/collection/set.php');
require_once(str_replace('//','/',dirname(__FILE__).'/').'../../Common/models/entity/not_found_exception.php');
 
class Feeligo_Model_Adapter_Users implements FeeligoUsers {
 
  public function __construct($table = null, $select = null) {
    $this->_table = $table !== null ? $table : Engine_Api::_()->getItemTable('user');
    $this->_select = $select !== null ? $select : $this->table()->select();
    
    $this->_api = Engine_Api::_()->getItemApi('user'); // User_Api_Core
  }
  
  public function table() {
    return $this->_table;
  }
  
  public function select() {
    return $this->_select;
  }
  
  public function find($id, $throw = true) {
    if (($user = $this->_api->getUser($id)) !== null && $user->getIdentity() == $id) {
      return new Feeligo_Model_Adapter_User($user, null, $this);
    }
    if ($throw) throw new FeeligoEntityNotFoundException('type', 'could not find '.'user'.' with id='.$id);
    return null;
  }
  
  public function find_all($ids) {
    return $this->_collect_users($this->_api->getUserMulti($ids));
  }
 
  public function all($limit = null, $offset = 0) {
    $select = $this->select();
    // pagination
    if ($limit !== null) $select->limit($limit, $offset);
    // retrieve data
    return $this->_collect_users($select->getTable()->fetchAll($select));
  }
  
  public function search($query, $limit = null, $offset = 0) {
    // Searchable users only
    $select = $this->select()->where('search = ?', 1);
    // WHERE clause for search
    $select->where('`'.$this->table()->info('name').'`.`displayname` LIKE ?', '%'. $query .'%');
    // pagination
    if ($limit !== null) $select->limit($limit, $offset);
    // retrieve data
    return $this->_collect_users($select->getTable()->fetchAll($select));
  }
  
  private function _collect_users($users) {
    $collection = new FeeligoEntityElementCollectionSet('user');
    if (sizeof($users) > 0) {
      foreach($users as $user) {
        $collection->add(new Feeligo_Model_Adapter_User($user, null));
      }
    }
    return $collection;
  }
 
}