<?php
/**
 * @version $Id: orders.php 272 2014-05-21 10:25:49Z michal $
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

class Djcatalog2TableOrders extends JTable
{
	public function __construct(&$db)
	{
		parent::__construct('#__djc2_orders', 'id', $db);
		$this->items = array();
	}
	public function bind($array, $ignore = '')
	{
		$db = JFactory::getDbo();
		
		$params = JComponentHelper::getParams('com_djcatalog2');

		$net_prices = (bool)((int)$params->get('price_including_tax', 1) == 0);

		if (!empty($array['order_items']) && is_array($array['order_items'])) {
			$items = array();
			$rows = $array['order_items'];
			foreach($rows['id'] as $id => $value) {
				$row = new stdClass();
				if (empty($rows['item_name'][$id])
				|| floatval($rows['quantity'][$id] == 0.0)
				|| floatval($rows['base_cost'][$id] == 0.0)
				|| floatval($rows['cost'] == 0.0)
				|| floatval($rows['total'] == 0.0)
				) {
					continue;
				}
				$row->id = $value;
				$row->order_id        = $array['id'];
				$row->item_id           = (empty($rows['item_id'][$id])) ? 0 : $rows['item_id'][$id];
				$row->item_name         = $rows['item_name'][$id];
				$row->quantity          = $rows['quantity'][$id];
				$row->cost              = $rows['cost'][$id];
				$row->base_cost         = $rows['base_cost'][$id];
				$row->tax               = $rows['tax'][$id];
				$row->tax_rate          = $rows['tax_rate'][$id];
				$row->total             = $rows['total'][$id];

				$items[] = $row;
			}

			$array['items'] = $items;
			unset($array['order_items']);
		}

		if (isset($array['items']) &&  count($array['items']) > 0) {
			$sub_totals = array();
			$total = 0.0;
			$tax = 0.0;
			$grand_total = 0.0;
			foreach($array['items'] as $item) {
				$tax_rate = (int)($item->tax_rate * 100);
				if (!array_key_exists($tax_rate, $sub_totals)) {
					$sub_totals[$tax_rate] = array('total'=>0.0, 'grand_total'=>0.0, 'tax'=>0.0);
				}
				$sub_totals[$tax_rate]['grand_total'] += round($item->total, 2);
				$sub_totals[$tax_rate]['total'] += round($item->cost, 2);
				$sub_totals[$tax_rate]['tax'] += round($item->tax, 2);
			}
			

			foreach($sub_totals as $tax_rate => $sub_total) {
				if ($net_prices) {
					// calculate tax from net price and then calculate grand total
					$sub_totals[$tax_rate]['tax'] = round(($tax_rate * $sub_total['total'])/100, 2);
					$sub_totals[$tax_rate]['grand_total'] =   $sub_totals[$tax_rate]['total'] + $sub_totals[$tax_rate]['tax'];
				} else {
					// calculating tax from grand total
					$sub_totals[$tax_rate]['tax'] = round(($tax_rate/(100+$tax_rate)) * $sub_total['grand_total'], 2);
					$sub_totals[$tax_rate]['total'] =   $sub_totals[$tax_rate]['grand_total'] -  $sub_totals[$tax_rate]['tax'];
				}

				$tax += $sub_totals[$tax_rate]['tax'];
				$total += $sub_totals[$tax_rate]['total'];
				$grand_total += $sub_totals[$tax_rate]['grand_total'];
			}
			
			$array['total'] = $total;
			$array['grand_total'] = $grand_total;
			$array['tax'] = $tax;
		}

		return parent::bind($array, $ignore);
	}

	public function load($keys = null, $reset = true)
	{
		$return = parent::load($keys, $reset);

		if ($return !== false && (int)$this->id > 0 && empty($this->items)) {
			$db = JFactory::getDbo();
			$db->setQuery('select * from #__djc2_order_items where order_id='.(int)$this->id);
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
				$obj->order_id = $this->id;
				if ($obj->id > 0) {
					$ret = $db->updateObject( '#__djc2_order_items', $obj, 'id', false);
				} else {
					$ret = $db->insertObject( '#__djc2_order_items', $obj, 'id');
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
			$db->setQuery('delete from #__djc2_order_items where order_id='.(int)$this->id.' and id not in ('.implode(',', $do_not_delete).')');
		} else {
			$db->setQuery('delete from #__djc2_order_items where order_id='.(int)$this->id);
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
