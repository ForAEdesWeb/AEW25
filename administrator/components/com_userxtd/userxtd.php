<?php
/**
 * Part of Component Userxtd files.
 *
 * @copyright   Copyright (C) 2014 Asikart. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

// Access check.
if (!JFactory::getUser()->authorise('core.manage', 'com_userxtd'))
{
	throw new \Exception(JText::_('JERROR_ALERTNOAUTHOR'), 404);
}

include_once JPATH_COMPONENT_ADMINISTRATOR . '/src/init.php';

echo with(new UserxtdComponent)->execute();
