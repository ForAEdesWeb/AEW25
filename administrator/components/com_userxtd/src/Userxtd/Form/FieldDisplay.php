<?php
/**
 * Part of joomla330 project. 
 *
 * @copyright  Copyright (C) 2011 - 2014 SMS Taiwan, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Userxtd\Form;

// No direct access
defined('_JEXEC') or die;

/**
 * Class FieldDisplay
 *
 * @since 1.0
 */
class FieldDisplay
{
	/**
	 * showImage
	 *
	 * @param mixed $value
	 *
	 * @return  string
	 */
	public static function showImage($value)
	{
		if($value)
		{
			return \JHtml::image(static::getThumbPath($value), 'UserXTD image');
		}

		return '';
	}

	/**
	 * getThumbPath
	 *
	 * @param string $value
	 *
	 * @return  string
	 */
	public static function getThumbPath($value)
	{
		$thumb = new \Windwalker\Image\Thumb(null, 'com_userxtd');

		return $thumb->resize($value, 300, 300, \JImage::CROP_RESIZE);
	}
}
 