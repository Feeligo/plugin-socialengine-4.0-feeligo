/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Feeligo
 * @copyright  Copyright 2012 Feeligo
 * @license    
 * @version    
 * @author     Davide Bonapersona <tech@feeligo.com>
 */


-- --------------------------------------------------------

--
-- Table structure for table `engine4_feeligo_gifts`
--

DROP TABLE IF EXISTS `engine4_feeligo_gifts`;
CREATE TABLE IF NOT EXISTS `engine4_feeligo_gifts` (
  `gift_id` int(11) NOT NULL auto_increment,
  `name` varchar(128) default NULL,
  `message` text default NULL,
  `medium_url` varchar(128) default NULL,
  PRIMARY KEY  (`gift_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE utf8_unicode_ci ;

-- --------------------------------------------------------

--
-- Dumping data for table `engine4_core_modules`
--

INSERT IGNORE INTO `engine4_core_modules` (`name`, `title`, `description`, `version`, `enabled`, `type`) VALUES  ('feeligo', 'Feeligo', 'Monetize and Engage your community by allowing your members to send virtual gifts to each other!', '4.1.0.2.3', 1, 'extra') ;


-- --------------------------------------------------------

--
-- Dumping data for table `engine4_core_menuitems`
--

INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `order`) VALUES
('flg_send_gift_user_home', 'core', 'Send a gift', 'Feeligo', '{"uri":"#_flg-send-gift","icon":"","target":"","enabled":"1"}', 'user_home', '', 6),
('flg_send_gift_user_profile', 'core', 'Send them gift', 'Feeligo', '{"uri":"#_flg-send-gift-to-subject","icon":"","target":"","enabled":"1"}', 'user_profile', '', 3)
;