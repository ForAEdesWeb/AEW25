<?php
/**
 * @version $Id: helper.php 263 2014-03-14 08:13:44Z michal $
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

class modDjc2RelateditemsHelper {
	var $_data = null;
	var $_cparams = null;
	var $_mparams = null;
	var $_categoryparams = array();
	
	function __construct( $params=array() )
	{
		$app = JFactory::getApplication();
		
		$cparams = $app->getParams('com_djcatalog2');
		$ncparams = new JRegistry();
		$ncparams->merge($cparams);
		
		$this->_cparams = $ncparams;
		
		$this->_mparams = $params;
	}
	function getData() {
		$app = JFactory::getApplication();
		
		if (!$this->_data){
			$option = $app->input->get('option', '', 'string');
			$view = $app->input->get('view', '', 'string');
			$id = $app->input->get('id', '', 'int');
			if ($option != 'com_djcatalog2' || $view != 'item' || !$id) {
				return false;
			}
			$db					= JFactory::getDBO();
			$db->setQuery($this->_buildQuery());
			$this->_data = $db->loadObjectList();
			
			foreach ($this->_data as $key => $item) {
				if ($this->_mparams->get('show_price') == 2 || ( $this->_mparams->get('show_price') == 1 && $item->price > 0.0)) {
					$catParams = $this->getCategoryParams($item->cat_id);
					if ($item->price != $item->final_price) {
						$this->_data[$key]->price = DJCatalog2HtmlHelper::formatPrice($item->price, $catParams);
						$this->_data[$key]->special_price = DJCatalog2HtmlHelper::formatPrice($item->special_price, $catParams);
					} else {
						$this->_data[$key]->price = DJCatalog2HtmlHelper::formatPrice($item->price, $catParams);
						$this->_data[$key]->special_price = null;
					}
					//$this->_data[$key]->price = DJCatalog2HtmlHelper::formatPrice($item->price, $catParams);
				}
				else {
					$this->_data[$key]->price = null;
					$this->_data[$key]->special_price = null;
				}
				
				$this->_data[$key]->slug = (empty($this->_data[$key]->alias)) ? $this->_data[$key]->id : $this->_data[$key]->id.':'.$this->_data[$key]->alias;
				$this->_data[$key]->catslug = (empty($this->_data[$key]->category_alias)) ? $this->_data[$key]->category_id : $this->_data[$key]->category_id.':'.$this->_data[$key]->category_alias;
				$this->_data[$key]->prodslug = (empty($this->_data[$key]->producer_alias)) ? $this->_data[$key]->producer_id : $this->_data[$key]->producer_id.':'.$this->_data[$key]->producer_alias;
				
			}
		}
		return $this->_data;
	}
	
	function getCategoryParams($catid) {
		if (!isset($this->_categoryparams[$catid])) {
			$categories = Djc2Categories::getInstance(array('state'=>'1'));
			$category = $categories->get($catid);
			$this->_categoryparams[$catid] = $this->_cparams;
			if (!empty($category)) {
				$catpath = array_reverse($category->getPath());
				foreach($catpath as $k=>$v) {
					$parentCat = $categories->get((int)$v);
					if (!empty($parentCat) && !empty($category->params)) {
						$catparams = new JRegistry($parentCat->params); 
						$this->_categoryparams[$catid]->merge($catparams);
					}
				}
			}
		}		
		return $this->_categoryparams[$catid];
	}
	
	function _buildQuery()
	{
		$where		= $this->_buildContentWhere();
		$orderby	= $this->_buildContentOrderBy();

		$query = ' SELECT i.*, CASE WHEN (i.special_price > 0.0 AND i.special_price < i.price) THEN i.special_price ELSE i.price END as final_price, c.id AS category_id, p.id AS producer_id, c.name AS category, c.alias as category_alias, p.name AS producer, p.alias as producer_alias, p.published as publish_producer, img.fullname AS item_image, img.caption AS image_caption, img.path as image_path, img.fullpath as image_fullpath '
			//. ', CASE WHEN CHAR_LENGTH(i.alias) THEN CONCAT_WS(":", i.id, i.alias) ELSE i.id END as slug, '
			//. ' CASE WHEN CHAR_LENGTH(c.alias) THEN CONCAT_WS(":", c.id, c.alias) ELSE c.id END as catslug, '
			//. ' CASE WHEN CHAR_LENGTH(p.alias) THEN CONCAT_WS(":", p.id, p.alias) ELSE p.id END as prodslug '
			. ' FROM #__djc2_items AS i '
			. ' LEFT JOIN #__djc2_categories AS c ON c.id = i.cat_id '
			. ' LEFT JOIN #__djc2_producers AS p ON p.id = i.producer_id '
			. ' LEFT JOIN (select im1.fullname, im1.caption, im1.type, im1.item_id, im1.path, im1.fullpath from #__djc2_images as im1, (select item_id, type, min(ordering) as lowest_order from #__djc2_images group by item_id, type) as im2 where im1.item_id = im2.item_id and im1.type=im2.type and im1.ordering = im2.lowest_order group by im1.type, im1.item_id, im1.path, im1.fullpath) AS img ON img.item_id = i.id AND img.type=\'item\''
			. $where
			. $orderby
		;
		
		//echo str_replace('#_', 'jos', $query);die();
		return $query;
	}

	function _buildContentOrderBy()
	{
		$filter_order		= $this->_mparams->get('orderby','i.ordering');
		$filter_order_Dir	= $this->_mparams->get('orderdir','asc');
		$filter_featured	= $this->_mparams->get('featured_first', 0);
		$limit = ($this->_mparams->get('items_limit',0));
		if ($filter_order != 'i.ordering' && $filter_order != 'category' && $filter_order != 'producer' && $filter_order != 'i.price' && $filter_order != 'i.name' && $filter_order != 'rand()') {
			$filter_order = 'i.ordering';
		}
		if ($filter_order_Dir != 'asc' && $filter_order_Dir != 'desc') {
			$filter_order_Dir = 'asc';
		}
		
		if ($filter_featured) {
			$orderby 	= ' ORDER BY i.featured DESC, '.$filter_order.' '.$filter_order_Dir.' , i.ordering, c.ordering ';
		}
		else if ($filter_order == 'i.ordering'){
			$orderby 	= ' ORDER BY i.ordering '.$filter_order_Dir.', c.ordering '.$filter_order_Dir;
		} 
		else {
			$orderby 	= ' ORDER BY '.$filter_order.' '.$filter_order_Dir.' , i.ordering, c.ordering ';
		}
		if ($limit > 0) {
			$orderby .= ' LIMIT '.$limit;
		}
		return $orderby;
	}

	function _buildContentWhere()
	{
		$db					= JFactory::getDBO();
		$app = JFactory::getApplication();
		
		$filter_catid		= 0; // $this->_mparams->get('catid', 0);
		$filter_featured	= $this->_mparams->get('featured_only', 0);
		
		$option = $app->input->get('option', '', 'string');
		$view = $app->input->get('view', '', 'string');
		$id = $itemId = $app->input->get('id', '', 'int');
		
		if ($option != 'com_djcatalog2' || $view != 'item' || !$id) {
			return false;
		}
		
		if ($option != 'com_djcatalog2' || $view != 'item' || !$itemId) {
			return false;
		}

		$where = array();
		
		$where[] = '(i.id IN (SELECT related_item FROM #__djc2_items_related WHERE item_id='.(int)$id.') )';
		
		if ($filter_featured > 0) {
			$where[] = 'i.featured = 1';
		}

		if ($filter_catid > 0) {
			
			$categories = Djc2Categories::getInstance(array('state'=>'1'));
				if ($parent = $categories->get((int)$filter_catid) ) {
					$childrenList = array($parent->id);
					$parent->makeChildrenList($childrenList);
					if ($childrenList) {
						$cids = implode(',', $childrenList);
						$db->setQuery('SELECT item_id 
									   FROM #__djc2_items_categories AS ic
									   INNER JOIN #__djc2_categories AS c ON c.id=ic.category_id 
									   WHERE category_id IN ('.$cids.') AND c.published = 1');
						$items = $db->loadColumn();
						if (count ($items)) {
							$items = array_unique($items);
							$where[] = 'i.id IN ('.implode(',',$items).')';
						}
						//$where[] = 'i.cat_id IN ( '.$cids.' )';
					}
					else if ($filter_catid != 0){
						JError::raiseError( 404, JText::_("COM_DJCATALOG2_PAGE_NOT_FOUND") );
					}
				}
			
		}
		$where[] = 'i.published = 1';
		$where[] = 'c.published = 1';
		$where 		= ( count( $where ) ? ' WHERE '. implode( ' AND ', $where ) : '' );
		return $where;
	}
}

?>
