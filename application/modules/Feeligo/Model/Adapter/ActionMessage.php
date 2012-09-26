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
 * @package    Feeligo_Model_Adapter_ActionMessage
 * @copyright  Copyright 2012 Feeligo
 * @license    
 */
 
class Feeligo_Model_Adapter_ActionMessage {
  
  protected function __construct($action_name, $message, $args) {
    $this->_action_name = $action_name;
    $this->_message = $message;
    $this->_args = $args;
    
    $this->_arg_subject = $this->_find_arg_by_function(array('subject'), 'community', 'user');
    // tries to use the direct_object if it matches domain = community
    $this->_arg_object = $this->_find_arg_by_function(array('direct_object'), 'community', 'user');
    // the other object is set as the indirect_object by default, regardless of type/domain
    $this->_arg_other_object = $this->_find_arg_by_function(array('indirect_object'));
    
    // if the object was not found, try swapping
    if ($this->_arg_object == null) {
      // if not, tries to use the indirect_object
      $this->_arg_object = $this->_find_arg_by_function(array('indirect_object'), 'community', 'user');
      if ($this->_arg_object !== null) {
        $this->_arg_other_object = $this->_find_arg_by_function(array('direct_object'));
      }
    }
  }
  
  
  public function key() {
    $i18n = $this->_message['i18n'];
    return $i18n['en'];
  }
  
  public function subject() {
    return $this->_find_user_from_arg($this->_arg_subject);
  }
  
  public function object() {
    return $this->_find_user_from_arg($this->_arg_object);
  }
  
  public function action_type_body() {
    $body = $this->_message['i18n']['en'];
    if ($this->_arg_subject !== null) {
      $body = str_replace('${'.$this->_arg_subject['properties']['function'].'}', '{item:$subject}', $body);
    }
    if ($this->_arg_object !== null) {
      $body = str_replace('${'.$this->_arg_object['properties']['function'].'}', '{item:$object}', $body);
    }
    // for the moment, use {body:$body} for the other_object
    if ($this->_arg_other_object !== null) {
      $body = str_replace('${'.$this->_arg_other_object['properties']['function'].'}', '{body:$body}', $body);
    }
    return $body;
  }
  
  public function body() {
    // for the moment, {body:$body} is used for the other_object
    $value = null;
    $item_type = '';
    $item_id = '';
    $action_name = $this->_action_name;
    if (($arg = $this->_arg_other_object) !== null) {
      $item_type = $this->_arg_type($arg, $item_type);
      $value = $this->_arg_prop($arg, 'name', $value);
      $item_id = $this->_arg_prop($arg, 'id', $item_id);
    }
    if ($value !== null) {
      return $value = "<flg origin='action' action='$action_name' item-type='$item_type' item-id='$item_id'>$value</flg>";
    }
  }
  
  protected function _arg_prop($arg, $prop, $default = null) {
    if ($arg !== null && isset($arg['properties']) && isset($arg['properties'][$prop])) {
      return $arg['properties'][$prop];
    }
    return $default;
  }
  protected function _arg_type($arg, $default = null) {
    if ($arg !== null && isset($arg['type'])) {
      return $arg['type'];
    }
    return $default;
  }
  
  public static function build($data) {
    if (isset($data['message']) && isset($data['arguments']) &&
    is_array($data['message']) && is_array($data['arguments']) &&
    isset($data['message']['i18n']) && is_array($data['message']['i18n'])) {
      return new self($data['name'], $data['message'], $data['arguments']);
    }
    return null;
  }
  
  protected function _find_arg_by_function($functions, $domain = null, $type = null) {
    foreach ($functions as $fun) {
      foreach ($this->_args as $arg) {
        if (($type == null || ($arg['type'] == $type)) && ($domain === null || ($arg['domain'] == $domain)) && $arg['properties']['function'] == $fun) {
          return $arg;
        }
      }
    }
    return null;
  }
  
  protected function _find_user_from_arg($arg) {
    if ($arg === null) return null;
    $users = new Feeligo_Model_Selector_Users();
    $user = $users->find($arg['properties']['id'], false); // does not throw exception
    if ($user !== null) return $user;
    return null;
  }
  
}