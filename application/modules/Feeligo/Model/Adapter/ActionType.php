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
 * @package    Feeligo_Model_Adapter_ActionType
 * @copyright  Copyright 2012 Feeligo
 * @license    
 */
 
class Feeligo_Model_Adapter_ActionType {
  
  function __construct($se_action_type) {
    $this->_adaptee = $se_action_type;
  }
  
  public function type() {
    return $this->_adaptee->type;
  }
  
  public function save() {
    return $this->_adaptee->save() !== null;
  }
  
  
  private static function table() {
    return Engine_Api::_()->getDbtable('actionTypes', 'activity');
  }
  
  public static function find_or_create($data, $message) {
    $at = self::table()->getActionType('flg_'.$data['name']);
    if ($at !== null) {
      return new self($at);
    }
    return self::create($data, $message);
  }
  
  public static function create($data, $message) {
    $adapter = self::build($data, $message);
    if ($adapter !== null && $adapter->save()) {
      return $adapter;
    }
  }
  
  public static function build($data, $message) {
    // build a ActionType adapter
    $row = self::table()->createRow();
    $row->setFromArray(array(
      'type' => 'flg_' . $data['name'],
      'module' => 'feeligo',
      'body' => $message->action_type_body(),
      'enabled' => true,
      'displayable' => 5, //TODO
      'attachable' => isset($data['attachable']) ? !!$data['attachable'] : true,
      'commentable' => isset($data['commentable']) ? !!$data['commentable'] : true,
      'shareable' => isset($data['shareable']) ? !!$data['shareable'] : true,
      'is_generated' => true
    ));
    return new self($row, $message);
  }
  
  
}
