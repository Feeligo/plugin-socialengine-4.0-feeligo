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
    return $this->_all_where('`'.$this->table()->info('name').'`.`user_id` = ?', $id);
    if ($throw) throw new FeeligoNotFoundException('user', 'could not find '.'user'.' with id='.$id);
    return null;
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
  public function search_by_name($query, $limit = null, $offset = 0) {
    $where = '`'.$this->table()->info('name').'`.`displayname` LIKE ?';
    $arg = '%'. $query .'%';
    return $this->_all_where($where, $arg, $limit, $offset);
  }

  /**
   * returns an array containing all the friends whose birth date matches the
   * arguments.
   * The $year number can be null, which should return all users whose birthday
   * is on the specified $day and $month, regardless of their birth year.
   * Assumes $year, $month, $date is a valid date.
   *
   * @param int $day the day number, from 1 to 31
   * @param int $month the month number, from 1 = January to 12 = December
   * @param int $year the year number (as a 4-digit integer), such as 2013
   * @param int $limit argument for the SQL LIMIT clause
   * @param int $offset argument for the SQL OFFSET clause
   * @return FeeligoUserAdapter[] array
   */
  public function search_by_birth_date($day, $month, $year = null, $limit = null, $offset = 0) {
    // join with engine4_user_fields_values to get birthdate
    $select = $this->_user()->membership()->getMembersObjectSelect()
      ->join(array('fields' => 'engine4_user_fields_values'),
        '',
        array())
      ->where('engine4_users.user_id = fields.item_id') // join
      ->where('search = ?', 1) // searchable users only
      ->where('fields.field_id = 6') // has birthdate field
      ->order("displayname");
    if ($year !== null) {
      // if a $year is provided we want an exact match
      // NOTE: SE4 stores this date without leading zeroes for 1-digit!
      $select->where("fields.value = ?", $year."-".$month."-".$day);
    } else {
      // otherwise, we match $month and $day only
      $select->where("fields.value LIKE ?", "%-".$month."-".$day);
    }
    // pagination
    if ($limit !== null) $select->limit($limit, $offset);
    // retrieve data
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