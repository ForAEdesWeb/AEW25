<?php
/**
 * @version $Id: price.php 272 2014-05-21 10:25:49Z michal $
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


class Djcatalog2HelperPrice {
	public static function calculate($price, $target, $tax_rate_id, $tax_rate = false, $user_id = null) {
		if ($tax_rate === false) {
			$tax_rate = self::getTaxRate($tax_rate_id, $user_id);
		}
		
		switch ($target) {
			case 'N' : {
				$price = self::getWithoutTax($price, $tax_rate);
				break;
			}
			case 'G' : {
				$price = self::getWithTax($price, $tax_rate);
				break;
			}
			case 'T' : {
				$price = self::getTax($price, $tax_rate);
				break;
			}
			default: break;
		}
		
		return $price;
	}
	
	public static function getTaxRate($tax_rate_id, $user_id = null) {
		$user = Djcatalog2Helper::getUserProfile($user_id);
		
		$tax_rate = 0;
		
		if (isset($user->tax_rules)) {
			if (isset($user->tax_rules[$tax_rate_id])) {
				$tax_rate=$user->tax_rules[$tax_rate_id];
			}
		}
		
		return $tax_rate;
	}
	
	public static function getWithoutTax($price, $tax_rate) {
		$tax = self::getTax($price, $tax_rate, true);
		$net = $price - $tax;
		return round($net, 2);
	}
	public static function getWithTax($price, $tax_rate) {
		$gross = $price + self::getTax($price, $tax_rate, false);
		return round($gross, 2);
	}
	public static function getTax($price, $tax_rate, $has_tax = null) {
		
		$params = JComponentHelper::getParams('com_djcatalog2');
		$default_has_tax = (bool)((int)$params->get('price_including_tax', 1) == 1);
		
		$tax = 0;
		if ($has_tax || ($has_tax === null) && $default_has_tax ) {
			$tax = $price * ($tax_rate / (100 + $tax_rate));
		} else if ($has_tax === false || ($has_tax === null && !$default_has_tax) ) {
			$tax = ($tax_rate * $price) / 100;
		}
		
		return round($tax, 2);
	}
	public static function getPrices($price, $old_price, $tax_rate_id, $tax_rate, $params){
		
		$prices = array('net'=>0, 'tax'=>0, 'gross'=>0, 'old'=>0, 'display'=>0, 'display2nd'=>false);
		
		$tax_already_incl = (bool)($params->get('price_including_tax', 1) == 1);
		$price_display = $params->get('price_display', 0);
		
		if ($tax_already_incl) {
			$prices['gross']	= $price;
			$prices['net'] 		= self::calculate($price, 'N', $tax_rate_id, $tax_rate);
			$prices['tax'] 		= self::calculate($price, 'T', $tax_rate_id, $tax_rate);
		
			$prices['old_gross']	= $old_price;
			$prices['old_net'] 		= self::calculate($old_price, 'N', $tax_rate_id, $tax_rate);
		} else {
			$prices['net'] 		= $price;
			$prices['gross'] 	= self::calculate($price, 'G', $tax_rate_id, $tax_rate);
			$prices['tax'] 		= self::calculate($price, 'T', $tax_rate_id, $tax_rate);
		
			$prices['old_net'] 		= $old_price;
			$prices['old_gross'] 	= self::calculate($old_price, 'G', $tax_rate_id, $tax_rate);
		}
		
		if ($price_display == 0) {
			$prices['display'] = $prices['gross'];
			$prices['old_display'] = $prices['old_gross'];
		} else if ($price_display == 2) {
			$prices['display'] = $prices['gross'];
			$prices['display2nd'] = $prices['net'];
			$prices['old_display'] = $prices['old_gross'];
		} else {
			$prices['display'] 	= $prices['net'];
			$prices['old_display'] = $prices['old_net'];
		}
		
		return $prices;
	}
	
	public static function getCartPrices($price, $old_price, $tax_rate_id, $tax_rate, $quantity, $params){
		
		$prices_base = self::getPrices($price, $old_price, $tax_rate_id, $tax_rate, $params);
		$prices_total = self::getPrices(($price * $quantity), ($old_price * $quantity), $tax_rate_id, $tax_rate, $params);
		
		$prices = array('base' => $prices_base, 'total' => $prices_total);
		return $prices;
	}
}