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
 * @package    Feeligo_Model_Selector_UserSentGiftToUserActionTypes
 * @copyright  Copyright 2012 Feeligo
 * @license    
 */

/**
 * Selector class for ActionType adapters, specific for the action of a user
 * sending a gift to another user.
 *
 * Provides the specialized implementation that gives the body of the 
 * SocialEngine actiontype for this specific type of action.
 */
 
class Feeligo_Model_Selector_UserSentGiftToUserActionTypes
  extends Feeligo_Model_Selector_ActionTypes {

  /**
   * The body attribute for the SocialEngine actiontype
   *
   * @return string
   */
  protected function _body_from_payload ($payload) {
    // get the site's default locale from the settings table
    $settings_api = Engine_Api::_()->getApi('settings', 'core');
    $locale = $settings_api->getSetting('core.locale.locale');
    if ($locale === null) $locale = 'en';
    // take the raw localized body string from the payload, which looks like
    // `"${subject} sent ${direct_object} to ${indirect_object}"`
    $body = $payload->localized_raw_body($locale);
    // the payload's `subject` is the action's `$subject` (sender)
    $body = str_replace('${subject}', '{item:$subject}', $body);
    // the payload's `indirect_object` is the action's `$object` (recipient)
    $body = str_replace('${indirect_object}', '{item:$object}', $body);
    // the payload's `indirect_object` is the action's `$body` (gift)
    $body = str_replace('${direct_object}', '{body:$body}', $body);
    // return
    return $body;
  }

}
