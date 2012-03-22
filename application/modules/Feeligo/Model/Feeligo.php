<?php

/**
 * Feeligo_Model_Feeligo class
 *
 * @package Feeligo
 * @author Davide Bonapersona
 **/
 
 
class Feeligo_Model_Feeligo {
  
  const __api_key = FLG__community_api_key;
  const __community_secret = FLG__community_secret;
  const __server_url = FLG__server_url;
  
  function __construct() {
    // store the viewer
    $this->_user_viewer = Engine_Api::_()->user()->getViewer();
    
    // store the subject
    $this->_user_subject = null;
    if (Engine_Api::_()->core()->hasSubject('user')) {
      $u_subj = Engine_Api::_()->core()->getSubject('user');
      // ensure the subject is not the same User as the viewer
      if (!$this->user_viewer()->isSelf($u_subj)) {
        $this->_user_subject = $u_subj;
      }
    }
    
  }
  
  public function user_viewer() {
    return $this->_user_viewer;
  }
  public function user_subject() {
    return $this->_user_subject;
  }
  
  /** 
   * whether the app should be rendered
   *
   * @return boolean
   */
  public function should_render_app() {
    $identity = $this->user_viewer()->getIdentity(); // get viewer's identity
    return !(null === $identity || 0 === $identity); // if identity is set and not null, the user is logged in : render the bar
  }
  
  /**
   * accessors for Feeligo params
   */
  public function api_key() { 
    return self::__api_key;
  }
  public function community_secret() {
    return self::__community_secret;
  }
  public function app_stylesheet_url($version = null) {
    $version_str = $version === null ? '' : ('-'.$version);
    return self::__server_url."c/".$this->api_key()."/apps/giftbar".$version_str.".css";
  }
  public function app_loader_js_url() {
    $user_str = $this->user_viewer() === null ? '' : ('-'.$this->user_viewer()->user_id);
    return self::__server_url."c/".$this->api_key()."/apps/giftbar-loader".$user_str.".js";
  }
  
  /**
   * returns the data required to start the App, as an Array which can be json_encoded
   * (the returned Array must contain only json_encodable types)
   *
   * @return Array
   */
  public function context() {
    return array(
      'viewer' => $this->_user_as_json_object($this->user_viewer()),
      'subject' => $this->_user_as_json_object($this->user_subject(), true),
      'users' => $this->_friends_as_json_object($this->user_viewer(), $this->user_subject())
    );
  }
  
  /**
   * returns authentication data
   *
   * @return Array
   */
  public function auth() {
    $time = time();
    return array(
      'time' => $time,
      'password' => sha1("user:".$this->community_secret().":".$this->user_viewer()->user_id.":".(intval($time/100)*100))
    );
  }
  
  /**
   * returns an array with some properties of the user.
   * The returned array can be json_encoded
   *
   * @param User_Model_User $user
   * @return array
   */
  private function _user_as_json_object($user, $can_receive_gifts_from_viewer = false) {
    if (null !== $user && isset($user->user_id) && isset($user->displayname) ) {
      return array(
        'id' => $user->user_id,
        'name' => $user->displayname,
        'index_url' => $user->getHref(),
        'image_url' => $user->getPhotoUrl('thumb.icon'),
        'crgfv' => $can_receive_gifts_from_viewer
      );
    }
    return null;
  }
  
  
  /**
   * returns all viewer's friend and all subject's friends as JSON objects
   * returns an array of arrays, each of the same type returned by _user_as_json_object above
   *
   * @param User_Model_User $user_viewer
   * @param User_Model_User $subject or null
   * @return array
   */
  private function _friends_as_json_object($user_viewer, $user_subject = null) {
    
    $all_users_json_obj = array();
    
    // friends of the viewer
    foreach($user_viewer->membership()->getMembers($user_viewer) as $row) {
      // add all friends of the viewer to the list. They are all allowed to receive gifts from the viewer.
      $all_users_json_obj[] = $this->_user_as_json_object($row, true);
    }
    
    // friends of the subject
    if (null !== $user_subject) {
      foreach($user_subject->membership()->getMembers($user_subject) as $row) {
        // if the $row user is friends with the viewer, it has already been added
        // if it is NOT friends with the viewer, and is NOT the viewer itself, add it (but can receive no gifts)
        if (!$user_viewer->membership()->isMember($row, $user_viewer, true) && !$user_viewer->isSelf($row)) {
          $all_users_json_obj[] = $this->_user_as_json_object($row, false);
        }
      }
    }

    return $all_users_json_obj;
  }
  
}

?>