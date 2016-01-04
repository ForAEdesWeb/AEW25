<?php
/**
 * @version SVN: $Id: header.php 18 2010-11-08 01:10:19Z elkuku $
 * @package    M2C
 * @subpackage Models
 * @author     Douglas Machado {@link http://idealextensions.com}
 * @author     Created on 14-Apr-2011
 * @license    GNU/GPL
 */

//-- No direct access
defined('_JEXEC') || die('=;)');

//-- Import Joomla modelitem library
jimport('joomla.application.component.modelitem');

/**
 * M2C Model.
 *
 * @package M2C
 */
class M2CModelM2C extends JModelItem
{
    /**
     * @var object $item
     */
    protected $item;

    /**
     * @var string  $category
     */
    protected $category;

    /**
     * Get the item.
     *
     * @return string The item
     */
    public function getItem()
    {
        if( ! isset($this->item))
        {
            $id = JRequest::getInt('id');

            // Get a JTable instance
            $table = $this->getTable();

            // Load the message
            $table->load($id);

            //-- Add global parameters
            $params = clone JFactory::getApplication('site')->getParams();
            $params->merge($table->params);

            $table->params = $params;

            //-- Assign the item
            $this->item = $table;
        }

        return $this->item;
    }//function

    /**
     * Get the category.
     *
     * @return object The category assigned to the item
     */
    public function getCategory()
    {
        if( ! isset($this->category))
        {
            $catid = $this->getItem()->catid;

            //-- Get a JTable instance
            $table = $this->getTable('Category', 'JTable');

            //-- Load the category
            $table->load($catid);

            //-- Assign the category
            $this->category = $table;
        }

        return $this->category;
    }//function
}//class
