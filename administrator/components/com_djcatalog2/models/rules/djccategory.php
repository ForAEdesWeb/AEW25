<?php
/**
 * @version $Id: djccategory.php 132 2013-05-20 07:12:44Z michal $
 * @package DJ-Catalog2
 * @copyright Copyright (C) 2012 DJ-Extensions.com LTD, All rights reserved.
 * @license http://www.gnu.org/licenses GNU/GPL
 * @author url: http://dj-extensions.com
 * @author email contact@dj-extensions.com
 * @developer Michal Olczyk - michal.olczyk@design-joomla.eu
 *
 * DJ-Catalog2 is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * DJ-Catalog2 is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with DJ-Catalog2. If not, see <http://www.gnu.org/licenses/>.
 *
 */

defined('_JEXEC') or die();
defined('JPATH_PLATFORM') or die;

class JFormRuleDjccategory extends JFormRule
{
	public function test(&$element, $value, $group = null, &$input = null, &$form = null)
	{
		$allowed_categories = array();
		if (!empty($element['allowed_categories'])) {
			if (!is_array($element['allowed_categories'])) {
				$allowed_categories = explode(',', $element['allowed_categories']);
			}
		}
		if (empty($allowed_categories)) {
			return true;
		}
		
		if (is_scalar($value)) {
			// Check each value and return true if we get a match
			foreach ($allowed_categories as $option)
			{
				if ((int)$value == (int)$option)
				{
					return true;
				}
			}
		} else if (is_array($value) || empty($value)) {
			$required = (empty($element['required'])) ? false : $element['required'];
			if ((empty($value) || count($value) == 0 )&& $required != 'true' && $required != 'required') {
				return true;
			}
			$value = array_unique($value);
			// If at least one category is invalid, return false
			$is_valid = false;
			foreach ($value as $selected) {
				if (!in_array($selected, $allowed_categories) && (int)$selected > 0) {
					return false;
				} else if ((int)$selected >= 0) {
					$is_valid = true;
				}
			}
			
			return $is_valid;
		}
		
		return false;
		
	}
}
