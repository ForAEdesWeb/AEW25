<?php
/**
 * @version SVN: $Id: header.php 18 2010-11-08 01:10:19Z elkuku $
 * @package    M2C
 * @subpackage Views
 * @author     Douglas Machado {@link http://idealextensions.com}
 * @author     Created on 14-Apr-2011
 * @license    GNU/GPL
 */

//-- No direct access
defined('_JEXEC') || die('=;)');

//-- Import Joomla view library
jimport('joomla.application.component.view');

/**
 * M2C List View.
 *
 * @package M2C
 */
class M2CViewCpanel extends JView
{
    /**
     * items to be displayed
     */
    protected $items;

    /**
     * pagination for the items
     */
    protected $pagination;

    /**
     * M2C view display method.
     *
     * @param string $tpl The name of the template file to parse;
     *
     * @return void
     */
    public function display($tpl = null)
    {
        $this->setToolBar();
        //-- Display the template
        parent::display($tpl);

        //-- Set the document
        $this->setDocument();
    }//function

    /**
     * Setting the toolbar.
     *
     * @return void
     */
    protected function setToolBar()
    {
        JToolBarHelper::title(JText::_('M2C Manager'), 'com_m2c');

        //JToolBarHelper::preferences('com_m2c');
    }//function

    /**
     * Method to set up the document properties.
     *
     * @return void
     */
    protected function setDocument()
    {
        JFactory::getDocument()->setTitle(JText::_('M2C Administration'));
    }//function
}//class
