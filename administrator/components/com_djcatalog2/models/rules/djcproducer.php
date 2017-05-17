<?php
/**
 * @version $Id: djcproducer.php 143 2013-10-02 14:36:44Z michal $
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

class JFormRuleDjcproducer extends JFormRule
{
	public function test(&$element, $value, $group = null, &$input = null, &$form = null)
	{
		$db = JFactory::getDbo();
		$user = JFactory::getUser();

		$user_id = ($form->getValue('created_by')) ? (int)$form->getValue('created_by') : $user->id;

		$db->setQuery('select id from #__djc2_producers where created_by='.(int)$user_id);
		$user_producers = $db->loadColumn();
		
		if (in_array((int)$value, $user_producers)) {
			return true;
		}
		
		if (count($user_producers) == 0 && (int)$value == 0) {
			return true;
		}
		
		return false;
		
	}
}
