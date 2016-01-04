<?php
/**
 * @copyright	Copyright (C) 2005 - 2010 Michael Richey. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controller');
require_once(JPATH_COMPONENT.DIRECTORY_SEPARATOR.'helpers'.DIRECTORY_SEPARATOR.'favicon.php');

/**
 * Favicon master display controller.
 *
 * @package		Favicon
 * @subpackage	com_favicon
 * @since		1.6
 */
class FaviconController extends JControllerLegacy
{
	/**
	 * Method to display a view.
	 */
	public function display()
	{
		if(!JRequest::getVar('view')) JRequest::setVar('view','favicons');
		parent::display();

		// Load the submenu.
		FaviconHelper::addSubmenu(JRequest::getWord('view','favicons'));
	}
}
