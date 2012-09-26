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

require_once(str_replace('//','/',dirname(__FILE__).'/').'../../sdk/apps/giftbar.php');  
 
class Feeligo_Model_App_Giftbar extends FeeligoGiftbarApp {
  
  /**
   * constructor
   * passes an instance of Feeligo_Api_Feeligo
   */
  function __construct() {
    parent::__construct(Feeligo_Api_Feeligo::_());
  }
  
}