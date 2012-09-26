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

/**
 * Feeligo_Model_Selector_Users
 *
 * this class implements methods to find users in the
 * database and pass them as Adapters to the Feeligo API.
 */
require_once(str_replace('//','/',dirname(__FILE__).'/').'../../sdk/interfaces/users_selector.php');
 
 
class Feeligo_Model_Selector_Users implements FeeligoUsersSelector {
 
  /**
   * Constructors
   * accepts optional $table and $select arguments to provide a scope for user selection
   *
   * @param Engine_Db_Table $table
   * @param Zend_Db_Select $select
   */
  public function __construct($table = null, $select = null) {
    $this->_table = $table !== null ? $table : Engine_Api::_()->getItemTable('user');
    $this->_select = $select !== null ? $select : $this->table()->select();
    
    $this->_api = Engine_Api::_()->getItemApi('user'); // User_Api_Core
  }
  
  /**
   * Accessor for the table object
   *
   * @return Engine_Db_Table
   */
  public function table() {
    return $this->_table;
  }
  
  /**
   * Accessor for the select object
   *
   * @return Zend_Db_Select
   */
  public function select() {
    return $this->_select;
  }
  
  /**
   * returns an array containing all the Users
   *
   * @param int $limit argument for the SQL LIMIT clause
   * @param int $offset argument for the SQL OFFSET clause
   * @return Feeligo_Model_Adapter_User[] array
   */
  public function all($limit = null, $offset = 0) {
    $select = $this->select();
    // pagination
    if ($limit !== null) $select->limit($limit, $offset);
    // retrieve data
    return $this->_collect_users($select->getTable()->fetchAll($select));
  }
  
  /**
   * finds a specific User by its id
   *
   * @param mixed $id argument for the SQL id='$id' condition
   * @return Feeligo_Model_Adapter_User
   */
  public function find($id, $throw = true) {
    if (($user = $this->_api->getUser($id)) !== null && $user->getIdentity() == $id) {
      // check whether the user actually exists in the database
      if (isset($user->user_id)) {
        // the user exists : return it
        return new Feeligo_Model_Adapter_User($user, null, $this);
      }
    }
    if ($throw) throw new FeeligoNotFoundException('user', 'could not find '.'user'.' with id='.$id);
    return null;
  }
  
  /**
   * finds a list of Users by their id's
   *
   * @param mixed array $ids
   * @return Feeligo_Model_Adapter_User[] array
   */
  public function find_all($ids) {
    return $this->_collect_users($this->_api->getUserMulti($ids));
  }
 
  /**
   * returns an array containing all the Users whose name matches the query
   *
   * @param string $query the search query, argument to a SQL LIKE '%$query%' clause
   * @param int $limit argument for the SQL LIMIT clause
   * @param int $offset argument for the SQL OFFSET clause
   * @return FeeligoUserAdapter[] array
   */ 
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
  
  
  /**
   * helper method to wrap each element of a list of User_Model_User
   * in a Feeligo_Model_Adapter_User adapter
   *
   * @param User_Model_User[] $users array
   * @return Feeligo_Model_Adapter_User[] array
   */
  protected function _collect_users($users) {
    $adapters = array();
    if (sizeof($users) > 0) {
      foreach($users as $user) {
        $adapters[] = new Feeligo_Model_Adapter_User($user, null);
      }
    }
    return $adapters;
  }
 
}