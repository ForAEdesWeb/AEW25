<?php
/**
 * Part of joomla330 project. 
 *
 * @copyright  Copyright (C) 2011 - 2014 SMS Taiwan, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Userxtd\Avatar;

// No direct access
defined('_JEXEC') or die;

/**
 * Class Avatar
 *
 * @since 1.0
 */
class Avatar
{
	/**
	 * getDefaultImage
	 *
	 * @return  string
	 */
	public static function getDefaultAvatar()
	{
		return \JURI::root() . 'components/com_userxtd/images/default_avatar.png';
	}
}
 