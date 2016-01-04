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

//-- Import Joomla modelform library
jimport('joomla.application.component.modelform');

/**
 * M2C Model.
 *
 * @package M2C
 */
class M2CModelM2C extends JModelForm
{
    /**
     * @var array data
     */
    protected $data = null;

    /**
     * Method to auto-populate the model state.
     */
    protected function populateState()
    {
        $app = JFactory::getApplication('administrator');

        //-- Load the User state.
        $pk = (int)$app->getUserState('com_m2c.edit.m2c.id');

        if( ! $pk)
        {
            $pk = JRequest::getInt('id');
        }

        $this->setState('m2c.id', $pk);
    }//function

    /**
     * Method to get the data.
     *
     * @return array
     */
    public function &getData()
    {
//        if (empty($this->data))
//        {
//            $app = JFactory::getApplication();

            if(empty($this->data))
            {
                $data = JRequest::getVar('jform');

                if(empty($data))
                {
                    $selected = $this->getState('m2c.id');
                    $data = $this->getTable();
                    $data->load((int)$selected);
                }

                $this->data = $data;
//                $query = $this->_db->getQuery(true);
//
//                // Select all fields from the m2c table.
//                $query->select('*');
//                $query->from('`#__m2c`');
//                $query->where('id = ' . (int)$selected);
//
//                $this->_db->setQuery((string)$query);
//                $data = $this->_db->loadAssoc();
            }

//            if (empty($data))
//            {
//                // Check the session for previously entered form data.
//                $data = $app->getUserState('com_m2c.edit.M2C.data', array());
//                unset($data['id']);
//            }
//
//            $app->setUserState('com_m2c.edit.M2C.data', $data);
//            $this->data = $data;
//        }

        return $this->data;
    }//function

    /**
     * Method to get the M2C form.
     *
     * @return mixed [JForm object on success | false on failure]
     */
    public function getForm($data = array(), $loadData = true)
    {
        $form = $this->loadForm('com_m2c.M2C', 'M2C'
        , array('control' => 'jform', 'load_data' => $loadData));

        $form->addRulePath(JPATH_COMPONENT.DS.'models'.DS.'rules');

        return $form;
    }//function

    /**
     * Method to get the javascript attached to the form
     *
     * @return string URL to the script.
     */
    public function getScript()
    {
        return 'administrator/components/com_m2c/models/forms/m2c.js';
    }//function

    /**
     * Method to save a record.
     *
     * @param array $data array of data
     *
     * @return boolean True on success
     */
    public function save($data)
    {
        //-- Database processing
        $row = $this->getTable();

        //-- Bind the form fields to the m2c table
        if( ! $row->save($data))
        {
            $this->setError($row->getErrorMsg());

            return false;
        }

        return true;
    }//function
}//class
