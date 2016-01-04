<?php
/**
 * @version $Id: html.php 272 2014-05-21 10:25:49Z michal $
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

class DJCatalog2HtmlHelper {
	public static function trimText($text, $length = 0) {
		if ($length > 0) {
			$nohtml = strip_tags($text);
			if(strlen($nohtml) > $length)
				return self::_substr($nohtml,$length);
		}
		return $text;
	}
	
	private static function _substr($str, $length, $minword = 3)
	{
	    $sub = '';
	    $len = 0;
	   
	    foreach (explode(' ', $str) as $word)
	    {
	        $part = (($sub != '') ? ' ' : '') . $word;
	        $sub .= $part;
	        $len += strlen($part);
	       
	        if (strlen($word) > $minword && strlen($sub) >= $length)
	        {
	            break;
	        }
	    }
	    return $sub . (($len < strlen($str)) ? '...' : '');
	}
	
	public static function formatPrice($price, &$params) {
		$price_decimal_separator = null;
		$price_thousands_separator = null;
		
		switch($params->get('thousand_separator',0)) {
			case 0: $price_thousands_separator=''; break;
			case 1: $price_thousands_separator=' '; break;
			case 2: $price_thousands_separator='\''; break;
			case 3: $price_thousands_separator=','; break;
			case 4: $price_thousands_separator='.'; break;
			default: $price_thousands_separator=''; break;
		}
		
		switch($params->get('decimal_separator',0)) {
			case 0: $price_decimal_separator=','; break;
			case 1: $price_decimal_separator='.'; break;
			default: $price_decimal_separator=','; break;
		}
		
		if ($params->get('unit_side') == '1') {
			return number_format($price, $params->get('decimals',2), $price_decimal_separator, $price_thousands_separator).' '.$params->get('price_unit');
		}
		else {
			return $params->get('price_unit').' '.number_format($price, $params->get('decimals',2), $price_decimal_separator, $price_thousands_separator);
		}
	}
	
	public static function orderDirImage ($order_current, $order='i.ordering', $dir='asc') {
		if ($dir == 'desc') $dir='asc';
		else $dir = 'desc';
		if ($order_current == $order) {
			return '<img class="djcat_order_dir" alt="'.$dir.'" src="'.DJCatalog2ThemeHelper::getThemeImage($dir.'.png').'" />';			
		}
		else {
			return '';
		}
	}
	
	public static function getEmailTemplate($data, $templatename) {
		$params = JComponentHelper::getParams('com_djcatalog2');
		$theme = $params->get('theme','default');
	
		$theme_location = $default_location = JPATH_ROOT.DS.'components'.DS.'com_djcatalog2'.DS.'themes'.DS.'default'.DS.'email'.DS.$templatename.'.php';
	
		if ($theme && $theme != 'default') {
			$theme_location = JPATH_ROOT.DS.'components'.DS.'com_djcatalog2'.DS.'themes'.DS.$theme.DS.'email'.DS.$templatename.'.php';
		}
	
		$template = JFile::exists($theme_location) ? $theme_location : $default_location;
	
	
		if (JFile::exists($template)) {
			ob_start();
			include($template);
			$out = ob_get_contents();
			ob_end_clean();
			return $out;
		}
		else {
			return false;
		}
	}
	
}

?>