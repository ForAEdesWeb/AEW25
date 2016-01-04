<?php
/**
 * @version SVN: $Id: header.php 18 2010-11-08 01:10:19Z elkuku $
 * @package    M2C
 * @subpackage Base
 * @author     Douglas Machado {@link http://idealextensions.com}
 * @author     Created on 14-Apr-2011
 * @license    GNU/GPL
 */

//-- No direct access
defined('_JEXEC') || die('=;)');

//-- Import Joomla controller library
jimport('joomla.application.component.controller');

/**
 * General Controller M2C component.
 *
 * @package M2C
 */
class M2CController extends JControllerLegacy 
{
    /**
     * Display task.
     *
     * @return void
     */
    public function display($cachable = false)
    {
        //-- Set default view if not set
        JRequest::setVar('view', JRequest::getCmd('view', 'cpanel'));
        $app		= JFactory::getApplication();
		$app->redirect('index.php?option=com_menus&view=item&layout=edit&menutype=mainmenu'
			,'You have to create a menu item in order to use this component.<br />
			Select <em>Menu Item Type -> M2C</em> and change the <em>Basic Options</em>');
		
        //-- Call parent behavior
        parent::display($cachable);

        //-- Add submenu and icons
        require_once JPATH_COMPONENT.DS.'helpers'.DS.'m2c.php';

        M2CHelper::addSubmenu('M2C');
        $this->config();
    }//function

	/**
	* Compiles a list of categories for a section
	* @param string The name of the category section
	*/
	public function config( $option='com_m2c' )
	{
		$app		= JFactory::getApplication();
		
		
		$db					=& JFactory::getDBO();
		
		$query = "SELECT id "
		. "\nFROM #__menu "
		. "\n WHERE link = 'index.php?option=".$option."'";
		$db->setQuery($query);
		if(!$result = $db->query()) {
		    echo $db->stderr();
		    return false;
		}
		$menuItem	= $db->loadResult();	
	    
	    if($menuItem)
	    	$app->redirect('administrator/index.php?option=com_menus&view=item&layout=edit&menutype=mainmenu','You have to create a menu item in order to use this component and change its configuration');
	    else
	    	$app->redirect('administrator/index.php?option=com_menus&view=item&layout=edit&menutype=mainmenu','You have to create a menu item in order to use this component and change its configuration');
	}
}//class
