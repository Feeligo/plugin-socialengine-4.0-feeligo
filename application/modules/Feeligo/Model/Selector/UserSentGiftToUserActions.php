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
 * @package    Feeligo_Model_Selector_UserSentGiftToUserActions
 * @copyright  Copyright 2012 Feeligo
 * @license    
 */
 
/**
 * Allows to create an action about a gift sent by a user to another user
 */

class Feeligo_Model_Selector_UserSentGiftToUserActions
  extends Feeligo_Model_Selector_Actions {

  /**
   * constructs an instance of this class from a Payload obtained from the 
   * Feeligo SDK, and saves the action to the SE4 activity feed.
   * The payload is assumed to be of the correct type
   * `user_sent_gift_to_user`
   *
   * @param FeeligoActionUserSentGiftToUserPayload $payload
   * @return Feeligo_Model_Adapter_Action
   */
  public function create ($payload) {
    // find or create the actiontype and get an adapter for it
    $selector = new Feeligo_Model_Selector_UserSentGiftToUserActionTypes();
    $action_type = $selector->find_or_create($payload);
    if ($action_type === null) return null;

    // build the action
    $se_action = $this->table()->addActivity(
      // sender
      (($s = $payload->adapter_sender()) !== null ? $s->user() : null),
      // recipient
      (($r = $payload->adapter_recipient()) !== null ? $r->user() : null),
      // action type
      $action_type->type(),
      // action body
      $this->_body_from_payload($payload),
      // params
      $params = null
    );
    
    // gift attachment to display the picture of the gift and the message
    if ($se_action !== null) {
      // build Feeligo_Model_Gift
      $giftsTable = Engine_Api::_()->getDbtable('gifts', 'feeligo');
      $gift = $giftsTable->createRow();
      // set Feeligo_Model_Gift properties
      $gift->name = $payload->gift()->name;
      $gift->message = $payload->gift()->message;
      $gift->medium_url = $payload->medium_url('medium', '60x72');
      // save the Feeligo_Model_Gift
      $gift->save();  
      // attach Feeligo_Model_Gift to action
      $se_action->attach($gift);
    }
    
    // return an instance of this class wrapping the SE4 action
    if ($se_action !== null) {
      return new Feeligo_Model_Adapter_Action($se_action);
    }
    return null;
  }


  /**
   * String to be used as the SocialEngine action's `body` field
   *
   * @return string
   */
  protected final function _body_from_payload ($payload) {
    $body = "<a data-flg-role='link' data-flg-origin='action'";
    $body .= " data-flg-type='gift'";
    $body .= " data-flg-id='".$payload->gift()->id."'";
    $body .= ">".$payload->gift()->name."</a>";
    return $body;
  }

}