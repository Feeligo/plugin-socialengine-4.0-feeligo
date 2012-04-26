<?php
/**
 * Use the FeeligoApiController from the Common library
 */
 
require_once(str_replace('//','/',dirname(__FILE__).'/').'../Common/controllers/controller.php');

class Feeligo_ApiController extends Core_Controller_Action_Standard {
  
  public function indexAction() {

    // disable layout
    $layoutHelper = $this->_helper->layout;
    if( $layoutHelper->isEnabled()) {
      $layoutHelper->disableLayout();#setLayout('none');
    }
    
    // is there a callback
    if (($callback = $this->_getParam('callback')) !== null) {
      $this->view->callback = $callback;
    }

    $flg_ctrl = new FeeligoController(Feeligo_Api_Feeligo::_());
    $flg_response = $flg_ctrl->run();

    $r = $this->getResponse();
    $r->clearBody();
    $r->setHttpResponseCode($flg_response->code());
    foreach($flg_response->headers() as $k => $v) {
      $r->setHeader($k, $v, true);
    }
    $r->setBody($flg_response->body());
  }

}
