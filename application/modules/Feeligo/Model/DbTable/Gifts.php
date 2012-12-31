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

/**
 * Feeligo_Model_DbTable_Gifts
 *
 * definition of the DB table which holds Gifts
 */
 
class Feeligo_Model_DbTable_Gifts extends Engine_Db_Table
{
  protected $_name = 'feeligo_gifts';

  protected $_rowClass = 'Feeligo_Model_Gift';
}