<?php

/**
 * Feeligo_Plugin_Core class
 *
 * @package Feeligo
 * @author Davide Bonapersona <tech@feeligo.com>
 **/

class Feeligo_Plugin_Core
{
 
  /**
   * SocialEngine hook
   * - writes the Javascript initialiser code in the <head>
   *
   * @return null
   */
  public function onRenderLayoutDefault($event)
  {
    $giftbar = new Feeligo_Model_App_Giftbar();
    
    if ($giftbar->is_enabled()) {
      
      // add startup Javascript code to the <head>
      $headScript = new Zend_View_Helper_HeadScript();
      $headScript->appendScript($giftbar->initialization_js());
    }
  }

}