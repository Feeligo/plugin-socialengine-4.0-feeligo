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

require_once(str_replace('//','/',dirname(__FILE__).'/').'../Common/apps/giftbar.php');  
 
class Feeligo_Model_Giftbar extends FeeligoAppGiftbar {
  
  /**
   * Accessor for the API object
   *
   * @return FeeligoApi
   */
  public function api() {
    return Feeligo_Api_Feeligo::_();
  }
  
}