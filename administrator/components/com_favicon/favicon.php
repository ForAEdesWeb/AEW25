<?php
/**
 * @subpackage	com_favicon
 * @copyright	Copyright (C) 2005 - 2010 Michael Richey. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// Access check.
if (!JFactory::getUser()->authorise('core.manage', 'com_favicon')) {
	return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}

// Include dependancies
jimport('joomla.application.component.controller');

$document =& JFactory::getDocument();
$document->addStyleSheet(JURI::root(true).'/media/com_favicon/assets/css/favicon.css');
// Execute the task.
$controller	= &JControllerLegacy::getInstance('Favicon');
$controller->execute(JRequest::getCmd('task','favicons'));
$controller->redirect();
