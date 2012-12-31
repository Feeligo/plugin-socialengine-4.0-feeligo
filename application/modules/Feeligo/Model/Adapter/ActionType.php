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
  
  public static function find_or_create($data) {
    $at = self::table()->getActionType('flg_'.$data->name());
    if ($at !== null) {
      return new self($at);
    }
    return self::create($data);
  }
  
  public static function create($data) {
    $adapter = self::build($data);
    if ($adapter !== null && $adapter->save()) {
      return $adapter;
    }
  }
  
  public static function build($data) {
    // build a ActionType adapter
    $row = self::table()->createRow();
    $row->setFromArray(array(
      'type' => 'flg_' . $data->name(),
      'module' => 'feeligo',
      'body' => $data->action_type_body(),
      'enabled' => true,
      'displayable' => 5, //TODO
      'attachable' => true,
      'commentable' => true,
      'shareable' => true,
      'is_generated' => true
    ));
    return new self($row);
  }
  
  
}
