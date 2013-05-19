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
 * @package    Feeligo_Model_Selector_ActionTypes
 * @copyright  Copyright 2012 Feeligo
 * @license    
 */

/**
 * Selector class for ActionType adapters
 * Allows to build, create and find_or_create a new SocialEngine actiontype
 * based on a Payload provided by the Feeligo SDK.
 *
 * This class provides the implamentation of common methods and is meant to be
 * extended by specialized subclasses for each action type.
 */
 
abstract class Feeligo_Model_Selector_ActionTypes {
  
  /**
   * The SE4 table holding actiontypes
   *
   * @return Engine_Db_Table
   */ 
  public final function table () {
    return Engine_Api::_()->getDbtable('actionTypes', 'activity');
  }
  

  /**
   * Finds or creates an ActionType depending on the name() property of the
   * $payload argument, and returns an instance of this class wrapping it.
   *
   * @param $payload the payload from the Feeligo SDK
   * @return Feeligo_Model_Adapter_ActionTypeInterface
   */
  public final function find_or_create ($payload) {
    $found = $this->table()->getActionType($this->_type_from_payload($payload));
    return $found !== null ? $this->_adapt($found) : $this->create($payload);
  }
  

  /**
   * Creates an ActionType based on the $payload supplied, and returns an
   * instance of this class wrapping it.
   *
   * @return Feeligo_Model_Adapter_ActionType
   */
  public final function create ($payload) {
    $adapter = $this->build($payload);
    if ($adapter !== null && $adapter->save()) {
      return $adapter;
    }
  }
  

  /**
   * Constructs an instance of this class based on the supplied $payload.
   * Rather than overriding this function, subclasses should implement
   * specific methods to set each field of the actiontype, which would
   * get called by this method, such as `_body_from_payload`.
   * This ensures that some attributes such as `type` and `module` do not get
   * modified by subclasses.
   *
   * @return Feeligo_Model_Adapter_ActionType
   */
  public final function build ($payload) {
    $actiontype = $this->table()->createRow();
    $actiontype->setFromArray(array(
      'type' => $this->_type_from_payload($payload),
      'module' => 'feeligo',
      'body' => $this->_body_from_payload($payload),
      'enabled' => true,
      'displayable' => 5, //TODO
      'attachable' => true,
      'commentable' => true,
      'shareable' => true,
      'is_generated' => true
    ));
    return $this->_adapt($actiontype);
  }


  /**
   * The type attribute for the SocialEngine actiontype,
   * i.e. the `name()` property of the `$payload` prefixed with `flg_`
   *
   * @return string
   */
  protected final function _type_from_payload ($payload) {
    return 'flg_'.$payload->name();
  }


  /**
   * The body attribute for the SocialEngine actiontype
   * must be implemented by subclasses depending on the action type.
   *
   * @return string
   */
  protected abstract function _body_from_payload ($payload);


  /**
   * Wraps a SocialEngine actiontype with the appropriate Feeligo adapter
   *
   * @param actiontype
   * @return Feeligo_Model_Adapter_ActionType
   */
  protected function _adapt ($actiontype) {
    return new Feeligo_Model_Adapter_ActionType($actiontype);
  }

}
