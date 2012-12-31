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
 * @package    Feeligo_Model_Adapter_ActionData
 * @copyright  Copyright 2012 Feeligo
 * @license    
 */
 
/**
 * the purpose of this class is to make it easier to manipulate
 * the action's data received with a callback from Feeligo server callback
 */
 
class Feeligo_Model_Adapter_ActionDataArgument {
  
  public function __construct($arg_data) {
    $this->_data = $arg_data;
  }
  
  public function property($prop, $default = null) {
    return isset($this->_data['properties'][$prop]) ? $this->_data['properties'][$prop] : $default;
  }
  
  public function type() {
    return isset($this->_data['type']) ? $this->_data['type'] : null;
  }
  
  public function domain() {
    return isset($this->_data['domain']) ? $this->_data['domain'] : null;
  }
  
  public function funktion() {
    return $this->property('function');
  } 
}
 
 
class Feeligo_Model_Adapter_ActionData {
  
  // checks the data and builds an instance of this object if required fields are present
  public static function build($payload) {
    if (isset($payload['message']) && isset($payload['arguments']) &&
    is_array($payload['message']) && is_array($payload['arguments']) &&
    isset($payload['message']['i18n']) && is_array($payload['message']['i18n'])) {
      return new self($payload);
    }
    return null;
  }
  
  protected function __construct($payload) {
    $this->_name = $payload['name'];
    $this->_message = $payload['message'];
    $this->_args = $payload['arguments'];
    $this->_text = isset($payload['text']) ? $payload['text'] : null;
    $this->_init_arg_function_mapping();
  }
  
  // getter for the action name
  public function name() {
    return $this->_name;
  }
  
  // Maps argument functions between SocialEngine and the action payload:
  // In theory, the `subject`, `direct object` and `indirect object` may be any type of object,
  // whereas SocialEngine requires the `subject` and `object` of an action to be both Users
  // (intended as "the users involved in the action").
  // This function establishes a mapping between a SocialEngine argument function
  // and an argument of the actual action payload
  protected function _init_arg_function_mapping() {
    if (($arg_subject = $this->find_argument(array('subject'), 'community', 'user')) !== null) {
      $this->_se_subject_arg = $arg_subject;
      if (($arg_object = $this->find_argument(array('direct_object'), 'community', 'user')) !== null) {
        $this->_se_object_arg = $arg_object;
        $this->_se_other_object_arg = $this->find_argument(array('indirect_object'));
      } elseif (($arg_object = $this->find_argument(array('indirect_object'), 'community', 'user')) !== null) {
        $this->_se_object_arg = $arg_object;
        $this->_se_other_object_arg = $this->find_argument(array('direct_object'));
      }  
    } elseif (($arg_subject = $this->find_argument(array('direct_object'), 'community', 'user')) !== null) {
      $this->_se_subject_arg = $arg_subject;
      $this->_se_other_object_arg = $this->find_argument(array('subject'));
      if (($arg_object = $this->find_argument(array('indirect_object'), 'community', 'user')) !== null) {
        $this->_se_object_arg = $arg_object;
      }else{
        $this->_se_object_arg = null;
      }
    }
  }
  
  // getter for the argument mapped to SocialEngine's `subject` of the action
  public function se_subject_arg() {
    return $this->_se_subject_arg;
  }
  
  // getter for the argument mapped to SocialEngine's `object` of the action
  public function se_object_arg() {
    return $this->_se_object_arg;
  }
  
  // getter for the argument mapped to what we call the `other object`:
  // SocialEngine does not support indirect objects, therefore we hack around this by
  // using the `body` part of the action to represent the `other object` if there is one
  public function se_other_object_arg() {
    return $this->_se_other_object_arg;
  }
  
  // getter for the UserAdapter considered as SocialEngine's `subject` of the action
  public function se_subject_user_adapter() {
    return $this->find_user_from_argument($this->se_subject_arg());
  }
  
  // getter for the UserAdapter considered as SocialEngine's `object` of the action
  public function se_object_user_adapter() {
    return $this->find_user_from_argument($this->se_object_arg());
  }
    
  // looks for an argument matching function(s), domain, type
  public function find_argument($functions, $domain = null, $type = null) {
    foreach ($functions as $fun) {
      foreach ($this->_args as $arg) {
        if (($type == null || ($arg['type'] == $type)) && ($domain === null || ($arg['domain'] == $domain)) && $arg['properties']['function'] == $fun) {
          return new Feeligo_Model_Adapter_ActionDataArgument($arg);
        }
      }
    }
    return null;
  }
  
  // attempts to fetch the user represented by a given argument from the database
  public function find_user_from_argument($arg) {
    if ($arg === null || $arg->type() != 'user' || $arg->domain() != 'community') return null;
    $users = new Feeligo_Model_Selector_Users();
    $user = $users->find($arg->property('id'), false); // will not throw exception if not found
    if ($user !== null) return $user;
    return null;
  }

  // Returns a string to be used as the SocialEngine action's `body` field.
  public function se_action_body() {
    if ($this->se_other_object_arg() !== null) {
      
      // If an argument representing a other_object is available (e.g. intransitive action
      // with an indirect object), then the `body` is used to store information about the
      // `other object` since SocialEngine only supports one subject and one object.      
      $body = "<a data-flg-role='link' data-flg-origin='action'";
    
      if (($type = $this->se_other_object_arg()->type()) !== null) {
        $body .= " data-flg-type='$type'";
      }
      if (($id = $this->se_other_object_arg()->property('id')) !== null) {
        $body .= " data-flg-id='$id'";
      }
      if (($name = $this->se_other_object_arg()->property('name')) !== null) {
        $body .= '>'.$name.'</a>';
      }else{
        $body .= '/>';
      }
      return $body;  
      
    } else {
      // No `other object` : the value of the `text` key of the action payload is returned.
      return $this->_text;
    }
  }
  
  // Returns a string to be used as the SocialEngine action type's body field
  public function action_type_body() {
    $body = $this->_message['i18n']['en']; // TODO: i18n happens here!
    if ($this->se_subject_arg() !== null) {
      $body = str_replace('${'.$this->se_subject_arg()->funktion().'}', '{item:$subject}', $body);
    }
    if ($this->se_object_arg() !== null) {
      $body = str_replace('${'.$this->se_object_arg()->funktion().'}', '{item:$object}', $body);
    }
    if ($this->se_other_object_arg() !== null) {
      // If an other_object is available, use the {body:$body} tag to store the other_object's representation within
      // the action in the database: replace the ${<other object's function>} placeholder with {body:$body}
      $body = str_replace('${'.$this->se_other_object_arg()->funktion().'}', '{body:$body}', $body);
    } else { 
      // Otherwise, just append {body:$body} to the end of the string: it will be filled with the action text.
      $body .= '<br/>{body:$body}';
    }
    return $body;
  }
}