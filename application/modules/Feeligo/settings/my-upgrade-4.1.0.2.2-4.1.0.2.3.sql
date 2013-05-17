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
CREATE TABLE `engine4_feeligo_gifts` (
  `gift_id` int(11) NOT NULL auto_increment,
  `name` varchar(128) default NULL,
  `message` text default NULL,
  `medium_url` varchar(128) default NULL,
  PRIMARY KEY  (`gift_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE utf8_unicode_ci ;
