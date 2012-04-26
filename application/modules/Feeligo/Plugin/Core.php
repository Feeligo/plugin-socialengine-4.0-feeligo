<?php

/**
 * Feeligo_Plugin_Core class
 *
 * @package Feeligo
 * @author Alexandre Duros
 * @author Davide Bonapersona <tech@feeligo.com>
 **/

class Feeligo_Plugin_Core
{
 
  /**
   * SocialEngine hook
   * - adds links to app stylesheets to <head>
   * - writes the JS data for the app (viewer, subject ... ) in the head
   *
   * @return null
   */
  public function onRenderLayoutDefault($event)
  {
    $giftbar = new Feeligo_Model_Giftbar();
    
    
    if ($giftbar->should_be_displayed()) {

      $headScript = new Zend_View_Helper_HeadScript();
      $headScript
        ->appendScript($giftbar->startup_js_code())
      ;

      // add links to stylesheets
      
      $headLink = new Zend_View_Helper_HeadLink();
      $headLink
        ->appendStylesheet($giftbar->app_stylesheet_url(), 'screen')
        ->appendStylesheet($giftbar->app_stylesheet_url('ie7'), 'screen', 'lt IE 7')
      ;
    }
  }

}