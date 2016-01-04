<?php
/**
 * Part of joomla330 project. 
 *
 * @copyright  Copyright (C) 2011 - 2014 SMS Taiwan, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Windwalker\CCK;

// No direct access
defined('_JEXEC') or die;

/**
 * Class CCKHelper
 *
 * @since 1.0
 */
class CCKHelper
{
	/**
	 * The value of false.
	 *
	 * @var  array
	 */
	static protected $falseValue = array(
		'disbaled',
		'false',
		'null',
		'0',
		'no',
		'none'
	);

	/**
	 * The value of true.
	 *
	 * @var  array
	 */
	static protected $trueValue = array(
		'true',
		'yes',
		'1'
	);

	/**
	 * isBool
	 *
	 * @param string $string
	 *
	 * @return  boolean
	 */
	public static function isBool($string)
	{
		if (in_array((string) $string, self::$falseValue) || !$string)
		{
			return false;
		}

		return true;
	}
}
 