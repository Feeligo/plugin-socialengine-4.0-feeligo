<?php
/**
 * Feeligo
 *
 * @category   Feeligo
 * @package    Feeligo_Model_ClientApp
 * @copyright  Copyright 2012 Feeligo
 * @license    
 * @author     Davide Bonapersona <tech@feeligo.com>
 */

/**
 * @category   Feeligo
 * @package    Feeligo_Model_ClientApp
 * @copyright  Copyright 2012 Feeligo
 * @license    
 */
abstract class Feeligo_Model_ClientApp {
  
  /**
   * constructor
   */
  public function __construct() {
    $this->api = Feeligo_Api_Feeligo::_();
  }    
  
  /**
   * Urls
   */
  public function app_file_url($app_file_path) {
    return $this->api->remote_api_endpoint_url()."c/".$this->api->community_api_key()."/apps/".$app_file_path;
  }
  
  /**
   * Should the app be displayed in the current context?
   */
  abstract public function should_be_displayed();
  
}