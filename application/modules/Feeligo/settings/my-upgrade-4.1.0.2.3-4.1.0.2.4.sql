/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Feeligo
 * @copyright  Copyright 2012 Feeligo
 * @license    
 * @version    
 * @author     Daniel Ross <tech@feeligo.com>
 */


-- --------------------------------------------------------

--
-- Dumping data for table `engine4_core_menuitems`
--

INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `order`) VALUES
('flg_send_gift_user_home', 'core', 'Send a gift', '', '{"uri":"#_flg-send-gift","icon":"","target":"","enabled":"1"}', 'user_home', '', 6),
('flg_send_gift_user_profile', 'core', 'Send them gift', '', '{"uri":"#_flg-send-gift-to-subject","icon":"","target":"","enabled":"1"}', 'user_profile', '', 3)
;