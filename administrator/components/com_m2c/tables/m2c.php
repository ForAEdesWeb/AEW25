<?php
/**
 * @version SVN: $Id: header.php 18 2010-11-08 01:10:19Z elkuku $
 * @package    M2C
 * @subpackage Tables
 * @author     Douglas Machado {@link http://idealextensions.com}
 * @author     Created on 14-Apr-2011
 * @license    GNU/GPL
 */

//-- No direct access
defined('_JEXEC') || die('=;)');

jimport('joomla.database.table');

/**
 * m2c Table class.
 *
 * @package M2C
 */
class TableM2C extends JTable
{
    /**
     * Constructor.
     *
     * @param object Database connector object
     */
    public function __construct(&$db)
    {
        parent::__construct('#__m2c', 'id', $db);
    }//function
}//class
