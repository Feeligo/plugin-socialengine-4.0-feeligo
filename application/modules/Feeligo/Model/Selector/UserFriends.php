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
 
class Feeligo_Model_Selector_UserFriends extends Feeligo_Model_Selector_Users {
 
  public function __construct($adapter_user, $table = null, $select = null) {
    parent::__construct($table, $select);
    $this->_adapter_user = $adapter_user;
  }
  
  public function _user() {
    return $this->_adapter_user->user();
  }
  
  public function find($id, $throw = true) {
    return $this->_all_where('`'.$this->table()->info('name').'`.`user_id` = ?', $id)->find($id, $throw);
  }
  
  public function find_all($ids) {
    return $this->_all_where('`'.$this->table()->info('name').'`.`user_id` IN', $ids);
  }
 
  public function all($limit = null, $offset = 0) {
    return $this->_collect_users($this->_user()->membership()->getMembers($this->_user()));
  }
  
  public function search($query, $limit = null, $offset = 0) {
    $where = '`'.$this->table()->info('name').'`.`displayname` LIKE ?';
    $arg = '%'. $query .'%';
    return $this->_all_where($where, $arg, $limit, $offset);
  }
  
  protected function _all_where($where, $arg, $limit = null, $offset = 0) {
    $select = $this->_user()->membership()->getMembersObjectSelect()->where($where, $arg);
    if ($limit !== null) $select->limit($limit, $offset);
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