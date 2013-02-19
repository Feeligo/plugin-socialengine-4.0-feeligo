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
   * the user's number of friends
   *
   * @return integer
   */
  public function friends_count() {
    return $this->user()->member_count;
  }

}