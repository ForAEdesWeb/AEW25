<?php
/**
 * @version $Id: cart.php 272 2014-05-21 10:25:49Z michal $
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

defined('_JEXEC') or die('Restricted access');

require_once(JPATH_ROOT.DS.'components'.DS.'com_djcatalog2'.DS.'helpers'.DS.'price.php');

class Djcatalog2HelperCart {
	static $baskets = array();
	
	public $items = array();
	public $quantities = array();
	public $total = array();
	public $sub_totals = array();
	
	/**
	 * 
	 * Retrieves or creates DJCatalog2HelperCart object
	 * @param bool $from_storage
	 * @param array $cart_items
	 * @return DJCatalog2HelperCart
	 */
	
	public static function getInstance($from_storage = true, $cart_items = array()) {
		
		$app = JFactory::getApplication();
		
		if ($from_storage) {
			$stored_items = $app->getUserState('com_djcatalog2.cart.items', array());
			if (empty($cart_items) && !empty($stored_items)) {
				$cart_items = $stored_items;
			}
		}
		
		$hash = md5(serialize($cart_items));
		
		if (isset(self::$baskets[$hash])) {
			return self::$baskets[$hash];
		}
		
		$basket = new Djcatalog2HelperCart();
		
		if (!empty($cart_items)) {
			JModelLegacy::addIncludePath(JPATH_BASE.DS.'components'.DS.'com_djcatalog2'.DS.'models', 'DJCatalog2Model');
			$model = JModelLegacy::getInstance('Items', 'Djcatalog2Model', array('ignore_request'=>true));
			
			$state		= $model->getState();
			$model->setState('list.start', 0);
			$model->setState('list.limit', 0);
			
			$user = Djcatalog2Helper::getUserProfile();
			if (isset($user->user_group_id)) {
				$model->setState('filter.customergroup', $user->user_group_id);
			}
			
			$model->setState('filter.catalogue',false);
			$model->setState('list.ordering', 'i.name');
			$model->setState('list.direction', 'asc');
			
			$item_ids = array_keys($cart_items);
			
			$model->setState('filter.item_ids', $item_ids);
			
			$basket->items = $model->getItems();
			$basket->quantities = $cart_items;
		}
		
		$basket->recalculate();
		
		self::$baskets[$hash] = $basket;
		
		return self::$baskets[$hash];
	}
	
	public function getTotal(){
		return $this->total;
	}
	public function getSubTotals(){
		return $this->total;
	}
	public function getItems(){
		return $this->items;
	}
	public function removeItem($item_id, $lazy = false) {
		
		foreach ($this->items as $k=>$v) {
			if ($v->id == (int)$item_id) {
				unset($this->items[$k]);
			}
		}
		
		if (isset($this->quantities[$item_id])) {
			unset($this->quantities[$item_id]);
		}
		
		if (!$lazy) {
			$this->recalculate();
		}
		
		return true;
	}
	public function addItem($item, $quantity = 1, $lazy = false) {
		
		if (is_scalar($item) && (int)$item > 0) {
			
			JModelLegacy::addIncludePath(JPATH_BASE.DS.'components'.DS.'com_djcatalog2'.DS.'models', 'DJCatalog2Model');
			$model = JModelLegacy::getInstance('Items', 'Djcatalog2Model', array('ignore_request'=>true));
			
			$state		= $model->getState();
			$model->setState('list.start', 0);
			$model->setState('list.limit', 0);
			
			$user = Djcatalog2Helper::getUserProfile();
			if (isset($user->user_group_id)) {
				$model->setState('filter.customergroup', $user->user_group_id);
			}
			
			$model->setState('filter.catalogue',false);
			$model->setState('list.ordering', 'i.name');
			$model->setState('list.direction', 'asc');
			
			$item_ids = array($item);
			
			$model->setState('filter.item_ids', $item_ids);
				
			$items = $model->getItems($item);
			if (count($items) > 0) {
				$item = current($items);
			}
		}
		
		if (!is_object($item) || $item->available != 1) {
			$this->recalculate();
			return false;
		}
		
		$item_id = $item->id;
		
		foreach ($this->items as $k=>$v) {
			if ($v->id == $item->id) {
				unset($this->items[$k]);
			}
		}
		$this->items[$item_id] = $item;
		
		if (isset($this->quantities[$item_id])) {
			$quantity += $this->quantities[$item_id];
		}
		
		$this->quantities[$item->id] = $quantity;
			
		
		if (!$lazy) {
			$this->recalculate();
		}
		
		return true;
	}
	
	public function getItem($item_id) {
		if (!$item_id || !isset($this->items[(int)$item_id])) {
			return false;
		}
		
		return $this->items[(int)$item_id];
	}
	
	public function updateQuantity($item_id, $quantity) {
		if (!isset($this->quantities[$item_id])) {
			if (!$this->addItem($item_id, $quantity)) {
				return false;
			}
		} else {
			$this->quantities[$item_id] = $quantity;
		}
		
		$this->recalculate();
		
		return true;
	}
	public function recalculate() {
		$params= Djcatalog2Helper::getParams();
		
		$sub_totals = array();
		$total =array('net'=>0, 'tax'=>0, 'gross'=>0.0);

		foreach($this->items as $k=>&$item) {
			if (empty($item->id)) {
				unset($this->items[$k]);
				continue;
			}
			$item->_quantity = (isset($this->quantities[$item->id])) ? $this->quantities[$item->id] : 1;
			$item->_prices = Djcatalog2HelperPrice::getCartPrices($item->final_price, $item->price, $item->tax_rate_id, false,  $item->_quantity, $params);
		
			if (!$item->tax_rate_id) {
				$item->tax_rate_id = 0;
			}
		
			if (!isset($sub_totals[$item->tax_rate_id])) {
				$sub_totals[$item->tax_rate_id] = array('net'=>0, 'tax'=>0, 'gross'=>0.0);
			}
		
			$sub_totals[$item->tax_rate_id]['net'] += ($item->_prices['total']['net'] );
			$sub_totals[$item->tax_rate_id]['gross'] += ($item->_prices['total']['gross']);
			$sub_totals[$item->tax_rate_id]['tax'] += ($item->_prices['total']['tax']);
		}
		
		unset($item);
		
		$tax_already_incl = (bool)($params->get('price_including_tax', 1) == 1);
		
		foreach ($sub_totals as $tax_rate_id => $sub_total) {
			if ($tax_already_incl) {
				$sub_totals[$tax_rate_id]['tax'] = Djcatalog2HelperPrice::calculate($sub_total['gross'], 'T', $tax_rate_id);
				$sub_totals[$tax_rate_id]['net'] = $sub_totals[$tax_rate_id]['gross'] - $sub_totals[$tax_rate_id]['tax'];
			} else {
				$sub_totals[$tax_rate_id]['tax'] = Djcatalog2HelperPrice::calculate($sub_total['net'], 'T', $tax_rate_id);
				$sub_totals[$tax_rate_id]['gross'] = $sub_totals[$tax_rate_id]['net'] + $sub_totals[$tax_rate_id]['tax'];
			}
				
			$total ['net'] += $sub_totals[$tax_rate_id]['net'];
			$total ['tax'] += $sub_totals[$tax_rate_id]['tax'];
			$total ['gross'] += $sub_totals[$tax_rate_id]['gross'];
		}
		
		$this->sub_totals = $sub_totals;
		$this->total = $total;
		
		return true;
	}
	
	public function saveToStorage() {
		$app = JFactory::getApplication();
		$app->setUserState('com_djcatalog2.cart.items', $this->quantities);
		
		return true;
	}
	
	public function clear() {
		$app = JFactory::getApplication();
		
		$this->items = array();
		$this->quantities = array();
		$this->total = array();
		$this->sub_totals = array();
		
		$app->setUserState('com_djcatalog2.cart.items', null);
	}
}