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
require_once(str_replace('//','/',dirname(__FILE__).'/').'ActionData.php');
require_once(str_replace('//','/',dirname(__FILE__).'/').'../../sdk/interfaces/action_adapter.php');
 
 
class Feeligo_Model_Adapter_Action implements FeeligoActionAdapter {
 
  public function __construct($se_action) {
    $this->_adaptee = $se_action;
  }
  
  public function id() {
    return $this->_adaptee->action_id;
  }
  
  public function action() {
    return $this->_adaptee;
  }
  
  public static function table() {
    return Engine_Api::_()->getDbtable('actions', 'activity');
  }
  
  public static function create($payload) {
    if (!isset($payload['type']) || $payload['type'] !== 'action') return null;
    
    // build a ActionData object with the action payload
    $data = Feeligo_Model_Adapter_ActionData::build($payload);
    if ($data === null) return null;

    // find or build the ActionType adapter
    $action_type = Feeligo_Model_Adapter_ActionType::find_or_create($data);
    if ($action_type === null) return null;
    
    // build the action
    $se_action = self::table()->addActivity(
      ($data->se_subject_user_adapter() === null ? null : $data->se_subject_user_adapter()->user()),
      ($data->se_object_user_adapter() === null ? null : $data->se_object_user_adapter()->user()),
      $action_type->type(),
      $body = $data->se_action_body(),
      $params = null
    );
    
    // part specific to the gifts action
    // we try to keep the rest of the actions code as generic as possible
    if ($data->name() == 'user_sent_gift_to_user') {
      // build Gift
      $giftsTable = Engine_Api::_()->getDbtable('gifts', 'feeligo');
      $gift = $giftsTable->createRow();
      // $gift->sender_id = $message->subject()->user()->user_id;
      // $gift->recipient_id = $message->object()->user()->user_id;
      $gift->message = isset($payload['text']) ? $payload['text'] : null;
      $gift->name = $data->se_other_object_arg()!==null ? $data->se_other_object_arg()->property('name', null) : null;
      if (isset($payload['media']) && count($payload['media']) > 0) {
        foreach($payload['media'] as $m) {
          if ($m['name'] == 'medium') {
            // the gift's medium
            if (isset($m['sizes'])) {
              if (isset($m['sizes']['60x72'])) {
                $gift->medium_url = $m['sizes']['60x72'];
              }
            }
          }
        }
      }
      $gift->save();  
      
      // attach Gift to action
      $se_action->attach($gift);
    }
    
    if ($se_action !== null) {
      return new self($se_action);
    }
  }

}