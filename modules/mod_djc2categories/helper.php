<?php
/**
 * @version $Id: helper.php 294 2014-06-28 19:59:07Z michal $
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

defined ('_JEXEC') or die('Restricted access');

require_once(JPATH_BASE.DS.'components'.DS.'com_djcatalog2'.DS.'helpers'.DS.'route.php');
require_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_djcatalog2'.DS.'lib'.DS.'categories.php');

class DJC2CategoriesModuleHelper {
	public static function getHtml($cid, $expand, $params, $root_id = 0) {
		$categories = Djc2Categories::getInstance(array('state'=>'1'));
		$root = $categories->get(0);
		$class_sfx	= htmlspecialchars($params->get('moduleclass_sfx', ''));
		
		$current = $categories->get($cid);

		$path = array();
		if (!empty($current)) {
			foreach ($current->getPath() as $item) {
				$path[] = (int)$item;
			}
		}
		
		if ((int)$root_id > 0) {
			if ($new_root = $categories->get($root_id)) {
				$root = $new_root;
			}
		}
		
		$show_count = (bool)$params->get('display_counter', false);

		$html = '<ul class="menu'.$class_sfx.' nav">';
		self::makeList($html, $root, $path, $expand, $cid, 0, $show_count);
		$html .= '</ul>';
		return $html;
	}
	private static function makeList(&$html, &$root, $path, $expand, $cid, $level = 0, $show_count = false) {
		$children = $root->getChildren();
		foreach($children as $child) {
			$current = (($child->id == $cid)) ? true:false;
			$parent = (count($child->getChildren())) ? true:false;
			$active = (($current || in_array($child->id, $path))) ? true:false;
			$deeper = ($parent && $expand) ? true:false;

			$class = 'djc_catid-'.$child->id.' level'.$level;
			$class .= ( $current ) ? ' current':'';
			$class .= ( $active ) ? ' active':'';
			$class .= ( $parent ) ? ' parent':'';
			$class .= ( $deeper ) ? ' deeper':'';
			
			$display_name = $child->name;
			if ($show_count) {
				if (($count = $child->getProductCount()) !== false) {
					$display_name = $child->name.' <small class="djc_category_counter">['.$count.']</small>';
				}
			}
			
			$html.= '<li class="'.$class.'"><a href="'.JRoute::_(DJCatalogHelperRoute::getCategoryRoute($child->id.':'.$child->alias), true).'">'.$display_name.'</a>';
			if (($active || $expand) && count($child->getChildren())) {
				$html .= '<ul>';
				$level++;
				self::makeList($html, $child, $path, $expand, $cid, $level, $show_count);
				$level--;
				$html .= '</ul>';
			}
			$html .= '</li>';
		}
	}
}
?>
