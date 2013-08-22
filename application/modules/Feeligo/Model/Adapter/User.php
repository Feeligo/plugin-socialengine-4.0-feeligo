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
 * Feeligo_Model_Adapter_User
 *
 * this class implements the Adapter pattern to adapt the interface
 * of the local User model as expected by the Feeligo API
 */

require_once(str_replace('//','/',dirname(__FILE__).'/').'../../sdk/interfaces/user_adapter.php');


class Feeligo_Model_Adapter_User implements FeeligoUserAdapter {

  /**
   * constructor
   * expects an instance of User_Model_User as the adaptee
   *
   * @param User_Model_User $adaptee
   */
  public function __construct(User_Model_User $user) {
    $this->_adaptee = $user;
  }

  /**
   * accessor for the adaptee
   *
   * @return User_Model_User
   */
  public function user() {
    return $this->_adaptee;
  }

  /**
   * Whether the adaptee actually exists in the community (not a new object and not an invalid ID)
   *
   * @return bool
   */
  public function user_exists() {
    return ($identity = $this->user()->getIdentity()) !== null && $identity != 0;
  }

  /**
   * returns the unique identifier of the user
   *
   * @return string
   */
  public function id() {
    return $this->user()->user_id . '';
  }

  /**
   * the user's display name
   *
   * human-readable name which is shown to other users
   *
   * @return string
   */
  public function name() {
    return $this->user()->displayname;
  }

  /**
   * the user's email
   *
   * @return string
   */
  public function email() {
    return $this->user()->email;
  }

  /**
   * the URL of the user's profile page (full URL, not only the path)
   *
   * @return string
   */
  public function link() {
    return $this->user()->getHref();
  }

  /**
   * the URL of the user's profile picture
   *
   * @return string
   */
  public function picture_url() {
    return $this->user()->getPhotoUrl('thumb.icon');
  }

  /**
   * returns a Feeligo_Model_Selector_UserFriends to select friends of this user
   *
   * @return Feeligo_Model_Selector_UserFriends
   */
  public function friends_selector() {
    return new Feeligo_Model_Selector_UserFriends($this);
  }

  /**
   * returns the birth date of this user as an Array containing three integers
   * indexed by 'year', 'month' and 'day'.
   *
   * @return array
   */
  public function birth_date() {
    $aliasValues = Engine_Api::_()->fields()->getFieldsValuesByAlias($this->user());
    $bd = array('year' => null, 'month' => null, 'day' => null );
    if( is_array($aliasValues) ) {
      if( !empty($aliasValues['birthdate']) ) {
        list($y, $m, $d) = preg_split('/[\/.-]/', $aliasValues['birthdate']);
        // format day and month
        $bd['year'] = ($y = intval($y)) > 0 ? $y : null;
        $bd['month'] = ($m = intval($m)) > 0 && $m <= 12 ? $m : null;
        $bd['day'] = ($d = intval($d)) > 0 && $d <= 31 ? $d : null;
      }
    }
    return $bd;
  }

}