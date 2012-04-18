<?php

/**
 * Feeligo_Plugin_Core class
 *
 * @package Feeligo
 * @author Alexandre Duros
 * @author Davide Bonapersona
 **/

class Feeligo_Plugin_Core
{
  
  public function __construct () {
    // store reference to API
    $this->api = Feeligo_Api_Feeligo::_();
  }
 
  /**
   * SocialEngine hook
   * - adds links to app stylesheets to <head>
   * - writes the JS data for the app (viewer, subject ... ) in the head
   *
   * @return null
   */
  public function onRenderLayoutDefault($event)
  {
    /*$giftbar = new Feeligo_Model_ClientApp_Giftbar();
    
    if ($giftbar->should_be_displayed()) {

      $headScript = new Zend_View_Helper_HeadScript();
      $headScript
        ->appendScript($this->startup_js_code())
      ;

      // add links to stylesheets
      $headLink = new Zend_View_Helper_HeadLink();
      $headLink
        ->appendStylesheet($giftbar->app_stylesheet_url(), 'screen')
        ->appendStylesheet($giftbar->app_stylesheet_url('ie7'), 'screen', 'lt IE 7')
      ;
    }
    */
  }
  
  /**
   * returns a string with the JS code of a function which when called would start the app
   *
   * @return string
   */
  private function startup_js_code() {
    return '(function(){if(!this.flg){this.flg={};}if(!this.flg.config){this.flg.config={};}if(!this.flg.context){this.flg.context={}};flg.config.api_key="'.$this->api->community_api_key().'";flg.context='.json_encode($this->context_as_json()).';flg.auth='.json_encode($this->auth_as_json()).'}).call(this);';
  }
  
  /**
   * returns the data required to start the App, as an Array which can be json_encoded
   * (the returned Array must contain only json_encodable types)
   *
   * @return Array
   */
  public function context_as_json() {
    return array(
      'viewer' => $this->api->adapter_viewer()->as_json(),
      'subject' => $this->api->has_subject() ? $this->api->adapter_subject()->as_json() : null,
      'users' => $this->_users_as_json_object($this->api->adapter_viewer(), $this->api->adapter_subject())
    );
  }
  
  /**
   * returns authentication data
   *
   * @return Array
   */
  public function auth_as_json() {
    $auth = $this->api->auth();
    return array(
      'time' => $auth->time(),
      'password' => $auth->remote_api_user_token($this->api->adapter_viewer())
    );
  }
  
  /**
   * returns all viewer's friend and all subject's friends as JSON objects
   * returns an array of arrays, each of the same type returned by _user_as_json_object above
   *
   * @param User_Model_User $user_viewer
   * @param User_Model_User $subject or null
   * @return array
   */
  private function _users_as_json_object($adapter_viewer, $adapter_subject = null) {
    
    $all_users_json_obj = array();
    
    $user_viewer = $adapter_viewer->user();
    $user_subject = $adapter_subject !== null ? $adapter_subject->user() : null;
    
    // friends of the viewer
    foreach($user_viewer->membership()->getMembers($user_viewer) as $row) {
      // add all friends of the viewer to the list. They are all allowed to receive gifts from the viewer.
      $adapter = new Feeligo_Model_Adapter_User($row, true);
      $all_users_json_obj[] = $adapter->as_json();
    }
    
    // friends of the subject
    if (null !== $user_subject) {
      foreach($user_subject->membership()->getMembers($user_subject) as $row) {
        // if the $row user is friends with the viewer, it has already been added
        // if it is NOT friends with the viewer, and is NOT the viewer itself, add it (but can receive no gifts)
        if (!$user_viewer->membership()->isMember($row, $user_viewer, true) && !$user_viewer->isSelf($row)) {
          $adapter = new Feeligo_Model_Adapter_User($row, false);
          $all_users_json_obj[] = $adapter->as_json();
        }
      }
    }

    return $all_users_json_obj;
  }

}