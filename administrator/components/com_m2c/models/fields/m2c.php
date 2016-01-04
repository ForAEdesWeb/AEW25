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

// import the list field type
jimport('joomla.html.html.list');

/**
 * M2C Form Field class for the M2C component.
 *
 * @package M2C
 */
class JFormFieldM2C extends JFormFieldList
{
    /**
     * The field type.
     *
     * @var string
     */
    protected $type = 'M2C';

    /**
     * Method to get a list of options for a list input.
     *
     * @return array An array of JHtml options.
     */
    protected function getOptions()
    {
        $query = $this->_db->getQuery(true);
        $query->select('a.id, a.greeting, a.catid');
        $query->select('cat.title AS category');
        $query->from('#__m2c AS a');
        $query->leftJoin('#__categories AS cat on a.catid=cat.id');

        $db->setQuery($query);
        $messages = $db->loadObjectList();

        $options = array();

        foreach($messages as $message)
        {
            $options[] = JHtml::_('select.option'
            , $message->id
            , $message->greeting.($message->catid ? ' ('.$message->category.')' : '')
            );
        }//foreach

        $options = array_merge(parent::getOptions(), $options);

        return $options;
    }//function
}//class
