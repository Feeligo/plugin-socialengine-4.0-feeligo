<?php

/**
 * @category   Application_Widget
 * @package    FeeligoGiftbar
 * @copyright  Copyright 2012 Feeligo
 * @author     Davide Bonapersona
 */
class Feeligo_Widget_GiftbarController extends Engine_Content_Widget_Abstract
{
  public function indexAction()
  {
    $giftbar = new Feeligo_Model_ClientApp_Giftbar();
    
    if ($this->view->viewer = Feeligo_Api_Feeligo::_()->user_viewer()) {
      
      if ($this->view->should_render_app = $giftbar->should_be_displayed()) {
        $this->view->app_loader_js_url = $giftbar->app_loader_js_url();
      }
    }
  }
  
  
}