<?php
/**
 * Feeligo
 *
 * @category   Feeligo
 * @package    Feeligo_Model_ClientApp_Giftbar
 * @copyright  Copyright 2012 Feeligo
 * @license    
 * @author     Davide Bonapersona <tech@feeligo.com>
 */

/**
 * @category   Feeligo
 * @package    Feeligo_Model_ClientApp_Giftbar
 * @copyright  Copyright 2012 Feeligo
 * @license    
 */
class Feeligo_Model_ClientApp_Giftbar extends Feeligo_Model_ClientApp {
  
  public function app_stylesheet_url($version = null) {
    return $this->app_file_url('giftbar'.(!!$version ? '-'.$version : '').'.css');
  }
  
  public function app_loader_js_url() {
    return $this->app_file_url('giftbar-loader-'.$this->api->adapter_viewer()->id().'.js');
  }
  
  /**
   * Should the app be displayed in the current context?
   */
  public function should_be_displayed() {
    return $this->api->has_viewer();
  }
  
}