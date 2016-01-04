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

jimport('joomla.application.component.modellist');

/**
 * M2CList Model.
 *
 * @package M2C
 */
class M2CModelM2CList extends JModelList
{
    /**
     * Model context string.
     *
     * @var string
     */
    protected $_context = 'com_m2c.M2CList';

    /**
     * Method to remove the selected items.
     *
     * @return boolean true on success
     */
    public function remove()
    {
        // Get the selected items
        $selected = $this->getState('selected');

        // Get a weblink row instance
        $table = $this->getTable('M2C');

        foreach($selected as $id)
        {
            // Load the row and check for an error.
            if( ! $table->load($id))
            {
                $this->setError($table->getError());

                return false;
            }

            // Delete the row and check for an error.
            if( ! $table->delete())
            {
                $this->setError($table->getError());

                return false;
            }
        }//foreach

        return true;
    }//function

    /**
     * Method to build an SQL query to load the list data.
     *
     * @return string SQL query
     */
    protected function getListQuery()
    {
//        $db = JFactory::getDBO();

        //-- Get a JDatabasequery object.
        $query = $this->_db->getQuery(true);

        //-- Select the fields
        $query->select('id, greeting');

        //-- From the m2c table
        $query->from('#__m2c');

        return $query;
    }//function

    /**
     * Method to auto-populate the model state.
     *
     * This method should only be called once per instantiation and is designed
     * to be called on the first call to the getState() method unless the model
     * configuration flag to ignore the request is set.
     *
     * @return void
     */
    protected function populateState()
    {
        // Initialize variables.
        $app = JFactory::getApplication('administrator');

        // Load the list state.
        $this->setState('list.start', $app->getUserStateFromRequest($this->_context
        . '.list.start', 'limitstart', 0, 'int'));

        $this->setState('list.limit', $app->getUserStateFromRequest($this->_context
        . '.list.limit', 'limit', $app->getCfg('list_limit', 25), 'int'));

        $this->setState('selected', JRequest::getVar('cid', array()));
    }//function
}//class
