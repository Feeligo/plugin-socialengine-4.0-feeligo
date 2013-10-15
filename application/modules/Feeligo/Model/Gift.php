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
    $message = ($m = $this->_message()) !== null ? $m : null;
    if ($message === null || strlen($message) == 0) return null;
    return "&laquo;&nbsp;".$message."&nbsp;&raquo;";
  }

  protected function _message() {
    if (($message = $this->message) !== null) {
      if (mb_detect_encoding($message, 'utf-8')) {
        $message = trim($message);
      } else {
        // no UTF-8
        $message = trim(utf8_encode($message));
      }
      return $message;
    }
  }

}