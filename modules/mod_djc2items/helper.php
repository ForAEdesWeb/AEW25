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

class modDjc2ItemsHelper {
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
		if (!$this->_data){
			
			JModelLegacy::addIncludePath(JPATH_BASE.DS.'components'.DS.'com_djcatalog2'.DS.'models', 'DJCatalog2Model');
			$model = JModelLegacy::getInstance('Items', 'Djcatalog2Model', array('ignore_request'=>true));
			
			$order		= $this->_mparams->get('orderby','i.ordering');
			$order_Dir	= $this->_mparams->get('orderdir','asc');
			$order_featured	= $this->_mparams->get('featured_first', 0);
			$filter_catid		= $this->_mparams->get('catid', array());
			$filter_itemids		= $this->_mparams->get('item_ids', null);
			
			$filter_featured	= $this->_mparams->get('featured_only', 0);
			$limit = $this->_mparams->get('items_limit',0);
			
			$state = $model->getState();
			
			//$this->_cparams->set('product_catalogue', 0);
			$model->setState('params', $this->_cparams);
			
			$model->setState('list.start', 0);
			$model->setState('list.limit', $limit);
			
			$model->setState('filter.category',$filter_catid);
			$model->setState('filter.catalogue',false);
			$model->setState('filter.featured',$filter_featured);
			$model->setState('list.ordering_featured',$order_featured);
			$model->setState('list.ordering',$order);
			$model->setState('list.direction',$order_Dir);
			
			if ($filter_itemids) {
				$filter_itemids = explode(',', $filter_itemids);
				$ids = array();
				foreach($filter_itemids as $k=>$v) {
					$v = trim($v);
					if ((int)$v > 0) {
						$ids[] = (int)$v;
					}
				}
				if (!empty($ids)) {
					$ids = array_unique($ids);
					$model->setState('filter.item_ids', $ids);
				}
			}
			
			$this->_data = $model->getItems();
			
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
}

?>
