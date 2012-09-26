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
require_once(str_replace('//','/',dirname(__FILE__).'/').'../../Common/models/adapter/user.php');
require_once(str_replace('//','/',dirname(__FILE__).'/').'../../Common/models/entity/element/collection/set.php');
 
class Feeligo_Model_Adapter_User extends FeeligoAdapterUser {
 
  public function __construct($se_user) {
    parent::__construct($se_user, isset($se_user->user_id) ? $se_user->user_id : 0);
  }
  
  /**
   * Whether the adaptee actually exists in the community (not a new object and not an invalid ID)
   */
  public function user_exists() {
    return ($identity = $this->user()->getIdentity()) !== null && $identity != 0;
  }
  
  /**
   * Accessors for the adaptee's properties
   */
  public function id() {
    return $this->user()->user_id;
  }
  
  public function name() {
    return $this->user()->displayname;
  }
  
  public function username() {
    return $this->user()->username;
  }
  
  public function link() {
    return $this->user()->getHref();
  }
  
  public function selector_friends() {
    return new Feeligo_Model_Selector_UserFriends($this);
  }
  
  public function picture_url() {
    return $this->user()->getPhotoUrl('thumb.icon');
  }
  
}