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

//-- Import the Joomla! view library
jimport('joomla.application.component.view');

/**
 * Standard view.
 *
 * @package M2C
 *
 */
class M2CViewM2C extends JViewLegacy
{
    /**
     * M2C view display method.
     *
     * @param string $tpl The name of the template file to parse;
     *
     * @return void
     */
    public function display($tpl = null)
    {
    	$app		= JFactory::getApplication();
    	
    	// Get the parameters of the active menu item
		$menus	= $app->getMenu();
		$menu	= $menus->getActive();
		$params	= $app->getParams();
		//echo '<pre>' ; print_r($menu); exit;
    	
		$this->assignRef('params',		$params);

        //-- Display the view
        parent::display($tpl);
    }//function
}//class
