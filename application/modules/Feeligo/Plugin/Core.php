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
 
  /**
   * SocialEngine hook
   * - adds links to app stylesheets to <head>
   * - writes the JS data for the app (viewer, subject ... ) in the head
   *
   * @return null
   */
  public function onRenderLayoutDefault($event)
  {
    $flg = new Feeligo_Model_Feeligo();
    
    if ($flg->should_render_app()) {

      $headScript = new Zend_View_Helper_HeadScript();
      $headScript
        ->appendScript($this->startup_js_code($flg))
      ;

      // add links to stylesheets
      $headLink = new Zend_View_Helper_HeadLink();
      $headLink
        ->appendStylesheet($flg->app_stylesheet_url(), 'screen')
        ->appendStylesheet($flg->app_stylesheet_url('ie7'), 'screen', 'lt IE 7')
      ;
    }
    
  }
  
  /**
   * returns a string with the JS code of a function which when called would start the app
   *
   * @return string
   */
  private function startup_js_code($flg) {
    return '(function(){if(!this.flg){this.flg={};}if(!this.flg.config){this.flg.config={};}if(!this.flg.context){this.flg.context={}};flg.config.api_key="'.$flg->api_key().'";flg.context='.json_encode($flg->context()).';flg.auth='.json_encode($flg->auth()).'}).call(this);';
  }

}