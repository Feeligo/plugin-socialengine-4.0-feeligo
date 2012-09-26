<?php
/**
 * Api endpoint controller
 *
 * requests made to the Feeligo API endpoint are routed to this controller's `index` action
 * which will invoke the FeeligoController to handle the request, and return its response.
 */
 
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
 * @package    Feeligo_ApiController
 * @copyright  Copyright 2012 Feeligo
 * @license    
 */

// Use the FeeligoApiController from the SDK
require_once(str_replace('//','/',dirname(__FILE__).'/').'../sdk/lib/controllers/controller.php');

class Feeligo_ApiController extends Core_Controller_Action_Standard {
  
  public function indexAction() {

    // disable layout
    $layoutHelper = $this->_helper->layout;
    if( $layoutHelper->isEnabled()) {
      $layoutHelper->disableLayout();
    }
    
    // is there a callback
    if (($callback = $this->_getParam('callback')) !== null) {
      $this->view->callback = $callback;
    }

    // invoke the FeeligoController to handle the request
    $flg_ctrl = new FeeligoController(Feeligo_Api_Feeligo::_());
    $flg_response = $flg_ctrl->run();

    // output the response (body, headers, status code)
    $r = $this->getResponse();
    $r->clearBody();
    $r->setHttpResponseCode($flg_response->code());
    foreach($flg_response->headers() as $k => $v) {
      $r->setHeader($k, $v, true);
    }
    $r->setBody($flg_response->body());
  }

}
