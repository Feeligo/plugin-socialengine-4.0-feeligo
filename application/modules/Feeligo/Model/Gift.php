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
 * @package    Feeligo_Model_Gift
 * @copyright  Copyright 2012 Feeligo
 * @license    
 */

/**
 * Feeligo_Model_Gift
 *
 * used to store a single sent Gift after confirmation from Feeligo server
 */
 
class Feeligo_Model_Gift extends Core_Model_Item_Abstract
{
  public function getPhotoUrl() {
    return $this->medium_url;
  }
  
  public function getHref() {
    return "#";
  }
  
  public function getDescription() {
    $message = $this->message !== null ? trim($this->message) : null;
    if ($message === null || strlen($message) == 0) return null;
    return "&laquo;&nbsp;".htmlentities(utf8_decode($message))."&nbsp;&raquo;";
  }

}