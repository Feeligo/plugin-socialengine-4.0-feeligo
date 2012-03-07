<?php

/**
 * FeeligoGiftbar_Plugin_Core class
 *
 * @package FeeligoGiftbar
 * @author Alexandre Duros
 * @author Davide Bonapersona
 **/

class FeeligoGiftbar_Plugin_Core
{
  
  /**
   * Feeligo parameters (should not be referred to directly, see accessors below)
   */
  
  const __api_key = FLG__community_api_key;
  const __community_secret = FLG__community_secret;
  const __server_url = FLG__server_url;

  const __headjs_url = FLG__headjs_url;
  const __socketio_url = FLG__socketio_url;
  
  /**
   * Accessors for Feeligo params
   */
  private function flg_API_key() { return self::__api_key; }
  private function flg_Community_Secret() { return self::__community_secret; }
  private function flg_HeadJsURL() { return self::__headjs_url; }
  private function flg_SocketIoURL() { return self::__socketio_url; }
  private function flg_appJsURL($api_key) { 
    return self::__server_url."c/".$api_key."/apps/GiftBar.js";
  }
  private function flg_appCssURL($api_key, $version = null) {
    $version_str = $version === null ? '' : ('-'.$version);
    return self::__server_url."c/".$api_key."/apps/GiftBar".$version_str.".css";
  }
 
  /**
   * SocialEngine hook, injects the app's loading code and data into the page
   *
   * @return null
   */
  public function onRenderLayoutDefault($event)
  {
    if ($this->shouldRender()) {
      $api_key = $this->flg_API_key();
      // helper to insert JS in the Head
      $headScript = new Zend_View_Helper_HeadScript();
      // add <script> tags
      $headScript
        ->appendFile($this->flg_HeadJsURL()) // add <script> tag which loads head.js
        ->appendScript('head.js(
          "'.$this->flg_SocketIoURL().'",
          "'.$this->flg_appJsURL($api_key).'",
          '.$this->getJsStartupFunctionCode($api_key).'
        );') // add <script> tag with call to FLG and boot data
      ;
      // add links to stylesheets
      $headLink = new Zend_View_Helper_HeadLink();
      $headLink
        ->appendStylesheet($this->flg_appCssURL($api_key), 'screen')
        ->appendStylesheet($this->flg_appCssURL($api_key, 'ie7'), 'screen', 'lt IE 7')
      ;
    }
    
  }
  
  
  
  /**
   * tells whether the app should be rendered in the current context
   *
   * @return boolean
   */
  private function shouldRender() {
    $identity = Engine_Api::_()->user()->getViewer()->getIdentity(); // get viewer's identity
    return !(null === $identity || 0 === $identity); // if identity is set and not null, the user is logged in : render the bar
  }
  
  /**
   * returns a string with the JS code of a function which when called would start the app
   *
   * @return string
   */
  private function getJsStartupFunctionCode($api_key) {
    return 'function(){__FLG("'.$api_key.'",'.json_encode($this->getStartupData()).')}';
  }
  
  /**
   * returns the data required to start the App, as an Array which can be json_encoded
   * (the returned Array must contain only json_encodable types)
   *
   * @return Array
   */
  private function getStartupData() {
    $time = time();
    
    // get users from SE
    $user_viewer = Engine_Api::_()->user()->getViewer();
    $user_subject = null;
    if (null !== $user_viewer && Engine_Api::_()->core()->hasSubject()) {
      $u_subj = Engine_Api::_()->core()->getSubject('user');
      // ensure that the subject and the viewer are not the same user (i.e. when viewing self's profile page)
      if (!$u_subj->isSelf($user_viewer)) {
        $user_subject = $u_subj;
      }
    }
    
    // add 'password' to viewer object
    $viewer_obj = $this->userAsJsonObject($user_viewer);
    if (is_array($viewer_obj)) {
      $viewer_obj['password'] = sha1("user:".$this->flg_Community_Secret().":".$user_viewer->user_id.":".(intval($time/100)*100));
    }
    
    // return an array which can be json_encoded
    return array(
      'viewer' => $viewer_obj,
      'subject' => $this->userAsJsonObject($user_subject, true),
      'friends' => $this->getFriendsObject($user_viewer, $user_subject),
      'time' => $time
    );
  }
  
  /**
   * returns an array with some properties of the user.
   * The returned array can be json_encoded
   *
   * @param User_Model_User $user
   * @return array
   */
  private function userAsJsonObject($user, $can_receive_gifts_from_viewer = false) {
    if (null !== $user && isset($user->user_id) && isset($user->displayname) ) {
      return array(
        'id' => $user->user_id,
        'name' => $user->displayname,
        'email' => isset($user->email) ? $user->email : null,
        'index_url' => $user->getHref(),
        'image_url' => $user->getPhotoUrl('thumb.icon'),
        'crgfv' => $can_receive_gifts_from_viewer
      );
    }
    return null;
  }
  
  
  /**
   * returns an array of arrays, each of the same type returned by userAsJsonObject above
   *
   * @param User_Model_User $user_viewer
   * @param User_Model_User $subject or null
   * @return array
   */
  private function getFriendsObject($user_viewer, $user_subject = null) {
    //TEMP
    //return array();
    
    $viewers_friends_ids = $this->getIdsOfFriends($user_viewer);
    $subjects_friends_ids = $this->getIdsOfFriends($user_subject);
    
    // intersection of two previous lists
    $common_friends_ids = array_unique(array_merge($viewers_friends_ids, $subjects_friends_ids));
    
    // array of common friends to be returned
    $common_friends_obj = array();
    
    // get a RowSet of user records with the ID's of common friends
    $common_friends_users = $user_viewer->getTable()->find($common_friends_ids);
    
    // iterate over the rowset. each row is cast to a User_Model_User instance, which can be passed to $this->userAsJsonObject
    if ($common_friends_users->count() > 0) {
      foreach($common_friends_users as $user) {
        $user_json = $this->userAsJsonObject($user, in_array($user->user_id, $viewers_friends_ids));
        if (null !== $user_json && is_array($user_json)) {
          $common_friends_obj[] = $user_json;
        }
      }
    }
    
    return $common_friends_obj;
  }
  
  /**
   * returns an array with the IDs of $user's friends
   *
   * @param User_Model_User $user
   * @return array
   */
  private function getIdsOfFriends($user) {
    $friend_ids = array();
    if ($user && $user->membership()) {
      /*$friends_resources = $user->membership()->getMembersOfSelect()->query()->fetchAll();
      for ($i=0; $i<sizeof($friends_resources); $i++) {
        $friend_ids[] = $friends_resources[$i]['resource_id'];
      }*/
      foreach( $user->membership()->getMembersInfo($user, true) as $row )
      {
        $friend_ids[] = $row->user_id;
      }
      //return Engine_Api::_()->getItemTable('user')->find($ids);
    }
    return $friend_ids;
  }
  

}