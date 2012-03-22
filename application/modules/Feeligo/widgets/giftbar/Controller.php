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
    $flg = new Feeligo_Model_Feeligo();
    
    if ($this->view->viewer = $flg->user_viewer()) {
      
      if ($this->view->should_render_app = $flg->should_render_app()) {
        $this->view->app_loader_js_url = $flg->app_loader_js_url();
      }
    }
  }
  
  
}