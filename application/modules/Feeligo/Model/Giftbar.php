<?php
/**
 * Feeligo
 *
 * @category   Feeligo
 * @package    Feeligo_Model_Giftbar
 * @copyright  Copyright 2012 Feeligo
 * @license    
 * @author     Davide Bonapersona <tech@feeligo.com>
 */

/**
 * @category   Feeligo
 * @package    Feeligo_Model_Giftbar
 * @copyright  Copyright 2012 Feeligo
 * @license    
 */
class Feeligo_Model_Giftbar {
  
  public function __construct() {
    $this->api = Feeligo_Api_Feeligo::_();
  }    
  
  /**
   * Accessor for the API object
   *
   * @return FeeligoApi
   */
  public function api() {
    return $this->api;
  }
  
  /**
   * URL of the CSS stylesheet
   *
   * the <link> tag referencing this file should be placed in the page's <head>
   *
   * @return string
   */
  public function app_stylesheet_url($version = null) {
    return $this->app_file_url('giftbar'.(!!$version ? '-'.$version : '').'.css');
  }
  
  /**
   * URL of the JS file which loads the Gift Bar
   *
   * the <script> tag referencing this file should be placed close to the bottom of the page,
   * (and not in the <head>) so that it does not block page load
   *
   * @return string
   */
  public function app_loader_js_url() {
    return $this->app_file_url('giftbar-loader-'.$this->api()->adapter_viewer()->id().'.js');
  }
  
  /**
   * Helper function which builds URL's of Feeligo app files
   */
  protected function app_file_url($app_file_path) {
    return $this->api()->remote_server_url()."c/".$this->api()->community_api_key()."/apps/".$app_file_path;
  }
  
  
  /**
   * Tells whether the Gift Bar should be displayed based on current context
   *
   * on SocialEngine we check if the viewer has an Identity which is not null and > 0
   * (meaning that the user exists)
   *
   * @return bool
   */
  public function should_be_displayed() {
    $identity = $this->api()->adapter_viewer()->user()->getIdentity();
    return $identity !== null && $identity > 0;
  }
  
  /**
   * Sets some JS variables that the Gift Bar expects to find in order to run
   * - context : the viewer and the subject
   * - auth : the tokens used to authenticate requests to the Feeligo API
   *
   * @return string
   */
  public function startup_js_code() {
    return '(function(){if(!this.flg){this.flg={};}if(!this.flg.config){this.flg.config={};}if(!this.flg.context){this.flg.context={}};flg.config.api_key="'.$this->api()->community_api_key().'";flg.context='.json_encode($this->context_as_json()).';flg.auth='.json_encode($this->auth_as_json()).'}).call(this);';
  }
  
  /**
   * Returns the json_encodable data for the Context
   *
   * @return Array
   */
  public function context_as_json() {
    return array(
      'viewer' => $this->api->adapter_viewer()->as_json(),
      'subject' => $this->api->has_subject() ? $this->api()->adapter_subject()->as_json() : null,
    );
  }
  
  /**
   * Returns json_encodable Authentication data
   *
   * @return Array
   */
  public function auth_as_json() {
    $auth = $this->api()->auth();
    return array(
      'time' => $auth->time(),
      'password' => $auth->remote_api_user_token($this->api()->adapter_viewer()),
      'community_api_user_token' => $auth->community_api_user_token($this->api()->adapter_viewer())->encode()
    );
  }
  
}