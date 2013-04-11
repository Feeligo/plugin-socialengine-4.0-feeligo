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
* Feeligo_Model_Selector_UserFriends
*
* this class implements methods to find friends of a given user in
* the database and pass them as Adapters to the Feeligo API.
*/

require_once(str_replace('//','/',dirname(__FILE__).'/').'../../sdk/interfaces/users_selector.php');


class Feeligo_Model_Selector_UserFriends extends Feeligo_Model_Selector_Users implements FeeligoUsersSelector {

  /**
   * Constructor
   * expects an instance of Feeligo_Model_Adapter_User containing the reference user
   * optionally expects a table and select like the Feeligo_Model_Selector_Users class
   *
   * @param Feeligo_Model_Adapter_User $adapter_user
   * @param Engine_Db_Table $table
   * @param Zend_Db_Select $select
   */
  public function __construct(Feeligo_Model_Adapter_User $adapter_user, $table = null, $select = null) {
    parent::__construct($table, $select);
    $this->_adapter_user = $adapter_user;
  }

  /**
   * Accessor for the reference user (the adapted SocialEngine user, NOT the adapter!)
   *
   * @return Feeligo_Model_Adapter_User
   */
  protected function _user() {
    return $this->_adapter_user->user();
  }

  /**
   * returns an array containing all the user's friends
   *
   * @param int $limit argument for the SQL LIMIT clause
   * @param int $offset argument for the SQL OFFSET clause
   * @return Feeligo_Model_Adapter_User[] array
   */
  public function all($limit = null, $offset = 0) {
    return $this->_collect_users($this->_user()->membership()->getMembers($this->_user()));
  }

  /**
   * finds a specific friend by its id
   *
   * @param mixed $id argument for the SQL id='$id' condition
   * @return Feeligo_Model_Adapter_User
   */
  public function find($id, $throw = true) {
    return $this->_all_where('`'.$this->table()->info('name').'`.`user_id` = ?', $id)->find($id, $throw);
  }

  /**
   * finds a list of friends by their id's
   *
   * @param mixed array $ids
   * @return Feeligo_Model_Adapter_User[] array
   */
  public function find_all($ids) {
    return $this->_all_where('`'.$this->table()->info('name').'`.`user_id` IN (?)', $ids);
  }

  /**
   * returns an array containing all the friends whose name matches the query
   *
   * @param string $query the search query, argument to a SQL LIKE '%$query%' clause
   * @param int $limit argument for the SQL LIMIT clause
   * @param int $offset argument for the SQL OFFSET clause
   * @return Feeligo_Model_Adapter_User[] array
   */
  public function search($query, $limit = null, $offset = 0) {
    $this->search_by_name($query, $limit, $offset);
  }

  public function search_by_name($query, $limit = null, $offset = 0) {
    $where = '`'.$this->table()->info('name').'`.`displayname` LIKE ?';
    $arg = '%'. $query .'%';
    return $this->_all_where($where, $arg, $limit, $offset);
  }

  public function search_by_birth_date($bd, $limit = null, $offset = 0) {
    // format date from mm-dd to (m)m-(d)d
    list($month, $day) = preg_split('/[\/.-]/', $bd);
    if ( intval($day) > 0 && intval($day) < 10 ) $day = substr($day, 1, 1);
    if ( intval($month) > 0 && intval($month) < 10 ) $month = substr($month, 1, 1);

    $select = $this->_user()->membership()->getMembersObjectSelect()
    ->join(array('fields' => 'engine4_user_fields_values'),
      '',
      array())
    ->where('engine4_users.user_id = fields.item_id') // join condition
    ->where('search = ?', 1) // searchable users only
    ->where('fields.field_id = 6') // has birthdate field
    ->where("fields.value LIKE ?","%-".$month."-".$day) // birthdate value
    ->order("displayname");

    if ($limit !== null) $select->limit($limit, $offset);

    return $this->_collect_users($select->getTable()->fetchAll($select));
  }


  /**
   * Helper method to pass a SQL WHERE, LIMIT and OFFSET to query friends of the reference user
   *
   * @param string $where the SQL WHERE condition, with question marks (e.g. "`displayname` LIKE ?")
   * @param string $arg the argument of the SQL, which will replace the question marks
   * @param int $limit the SQL LIMIT
   * @param int $offset the SQL OFFSET
   */
  protected function _all_where($where, $arg, $limit = null, $offset = 0) {
    $select = $this->_user()->membership()->getMembersObjectSelect()->where($where, $arg);
    if ($limit !== null) $select->limit($limit, $offset);
    return $this->_collect_users($select->getTable()->fetchAll($select));
  }

}