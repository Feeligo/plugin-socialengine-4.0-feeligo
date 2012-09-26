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
 * @package    Feeligo_Model_Adapter_Action
 * @copyright  Copyright 2012 Feeligo
 * @license    
 */
require_once(str_replace('//','/',dirname(__FILE__).'/').'../../Common/models/adapter/action.php');
 
class Feeligo_Model_Adapter_Action extends FeeligoAdapterAction {
 
  public function __construct($se_action) {
    parent::__construct($se_action, isset($se_action->action_id) ? $se_action->action_id : 0);
    $this->_adaptee = $se_action;
  }
  
  public function id() {
    return $this->_adaptee->action_id;
  }
  
  public static function table() {
    return Engine_Api::_()->getDbtable('actions', 'activity');
  }
  
  public static function create($data) {
    if (!isset($data['type']) || $data['type'] !== 'action') return null;
    
    // build a ActionMessage adapter
    $message = Feeligo_Model_Adapter_ActionMessage::build($data);
    if ($message === null || $message->subject() === null) return null;

    // find or build the ActionType adapter
    $action_type = Feeligo_Model_Adapter_ActionType::find_or_create($data, $message);
    if ($action_type === null) return null;
    
    // build the action
    $se_action = self::table()->addActivity(
      $message->subject()->user(),
      $message->object()->user(),
      $action_type->type(),
      $body = $message->body(),
      $params = null
    );
    
    if ($se_action !== null) {
      return new self($se_action);
    }
  }

}