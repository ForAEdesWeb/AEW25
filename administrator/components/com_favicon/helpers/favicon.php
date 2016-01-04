<?php
/**
 * @copyright	Copyright (C) 2010 Michael Richey. All rights reserved.
 * @license		GNU General Public License version 3; see LICENSE.txt
 */

jimport('joomla.filesystem.file');
class FaviconHelper
{
	/**
	 * Configure the Linkbar.
	 *
	 * @param	string	The name of the active view.
	 */
	public static function addSubmenu($vName)
	{
		JSubMenuHelper::addEntry(
			JText::_('COM_FAVICON_SUBMENU_ICONS'),
			'index.php?option=com_favicon',
			$vName == 'favicons'
		);
	}
	/**
	 * Gets a list of the actions that can be performed.
	 *
	 * @param	int		The category ID.
	 *
	 * @return	JObject
	 */
	public static function getActions()
	{
		$user	= JFactory::getUser();
		$result	= new JObject;
		$result->set('core.admin',	$user->authorise('core.admin', 'com_favicon'));
		return $result;
	}

     function format_bytes($size) {
        $units = array(' B', ' KiB', ' MiB', ' GiB', ' TiB');
        for ($i = 0; $size >= 1024 && $i < 4; $i++) $size /= 1024;
        return round($size, 2).$units[$i];
    }

    }