<?php

/**
 * Feeligo
 *
 * @category   Application_Widget
 * @package    Feeligo_Api
 * @copyright  Copyright 2012 Feeligo
 * @license    
 * @author     Davide Bonapersona <tech@feeligo.com>
 */

class Feeligo_Widget_GiftbarController extends Engine_Content_Widget_Abstract
{
  public function indexAction()
  {
    $giftbar = new Feeligo_Model_App_Giftbar();
    
    if ($giftbar->is_enabled()) {
      $this->view->is_enabled = true;
      $this->view->loader_js_url = $giftbar->loader_js_url();
    }
  }
}