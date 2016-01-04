<?php
/**
 * @version $Id: quotes.php 272 2014-05-21 10:25:49Z michal $
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

// No direct access
defined('_JEXEC') or die;

class Djcatalog2TableQuotes extends JTable
{
	public function __construct(&$db)
	{
		parent::__construct('#__djc2_quotes', 'id', $db);
		$this->items = array();
	}
	public function bind($array, $ignore = '')
	{
		$db = JFactory::getDbo();
		
		$params = JComponentHelper::getParams('com_djcatalog2');

		if (!empty($array['quote_items']) && is_array($array['quote_items'])) {
			$items = array();
			$rows = $array['quote_items'];
			foreach($rows['id'] as $id => $value) {
				$row = new stdClass();

				$row->id = $value;
				$row->quote_id        = $array['id'];
				$row->item_id           = (empty($rows['item_id'][$id])) ? 0 : $rows['item_id'][$id];
				$row->item_name         = $rows['item_name'][$id];
				$row->quantity          = $rows['quantity'][$id];

				$items[] = $row;
			}

			$array['items'] = $items;
			unset($array['quote_items']);
		}

		return parent::bind($array, $ignore);
	}

	public function load($keys = null, $reset = true)
	{
		$return = parent::load($keys, $reset);

		if ($return !== false && (int)$this->id > 0 && empty($this->items)) {
			$db = JFactory::getDbo();
			$db->setQuery('select * from #__djc2_quote_items where quote_id='.(int)$this->id);
			$this->items = $db->loadObjectList('id');
		}

		return $return;
	}
	public function store($updateNulls = false)
	{
		$items = $this->items;
		unset($this->items);

		$success = parent::store($updateNulls);
		//$this->items = $items;

		if (!$success) {
			return false;
		}

		$db = JFactory::getDbo();

		$do_not_delete = array();
		if (count($items)) {
			foreach ($items as &$obj) {
				$obj->quote_id = $this->id;
				if ($obj->id > 0) {
					$ret = $db->updateObject( '#__djc2_quote_items', $obj, 'id', false);
				} else {
					$ret = $db->insertObject( '#__djc2_quote_items', $obj, 'id');
				}
				if ($ret) {
					$do_not_delete[] = $obj->id;
				} else {
					$this->setError($db->getErrorMsg());
				}
			}
			unset($obj);
		}

		if (count($do_not_delete) > 0) {
			$db->setQuery('delete from #__djc2_quote_items where quote_id='.(int)$this->id.' and id not in ('.implode(',', $do_not_delete).')');
		} else {
			$db->setQuery('delete from #__djc2_quote_items where quote_id='.(int)$this->id);
		}

		if (!$db->query()) {
			$this->setError($db->getErrorMsg());
			return false;
		}

		$this->items = $items;
		unset($items);

		return true;

	}
}
