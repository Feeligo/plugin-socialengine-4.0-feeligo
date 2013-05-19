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
 * @package    Feeligo_Model_Adapter_User
 * @copyright  Copyright 2012 Feeligo
 * @license    
 */

require_once(str_replace('//','/',dirname(__FILE__).'/').'../../sdk/interfaces/actions_selector.php');
 
 
class Feeligo_Model_Selector_Actions
  implements FeeligoActionsSelector {

  /**
   * The SocialEngine table holding actions
   *
   * @return Engine_Db_Table
   */
  public function table () {
    return Engine_Api::_()->getDbtable('actions', 'activity');
  }
  

  /**
   * Creates a new action and returns an adapter for it
   * This method is responsible for determining the appropriate Selector
   * to use as a Factory for the action
   * 
   * @return Feeligo_Model_Adapter_Action
   */
  public function create ($payload) {
    // at the moment, only the `user_sent_gift_to_user` type is supported
    if ($payload->name() == 'user_sent_gift_to_user') {
      $selector = new Feeligo_Model_Selector_UserSentGiftToUserActions();
      return $selector->create($payload);
    }
    return null;
  }
  
}