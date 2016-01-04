<?php
/**
 * @version $Id: modfrontpage.php 272 2014-05-21 10:25:49Z michal $
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

jimport('joomla.application.component.model');

class DJCatalog2ModelModfrontpage extends JModelLegacy {
	var $_list = null;
	var $_pagination = null;
	var $_total = null;
	var $_params = null;
	
	var $_cparams = null;
	
	var $_categoryparams = array();
	
	function getXml() {
		$app = JFactory::getApplication();
		$jinput = $app->input;
		
		$this->_cparams = $app->getParams('com_djcatalog2');

		$moduleclass_sfx = $jinput->get( 'moduleclass_sfx', null, 'string');
		$mid = (int)$jinput->get( 'moduleId', null, 'int');
		$stitle = $jinput->get( 'stitle', '1', 'string');
		$ltitle = $jinput->get( 'ltitle', '1', 'string');
		
		$modal = $jinput->get( 'modal', 1, 'int');
		
		$scattitle = $jinput->get( 'scattitle', null, 'string');
		
		$sproducer = $jinput->get( 'sproducer', '0', 'string');
		$sprice = $jinput->get( 'sprice', '0', 'string');
		
		$spag = $jinput->get( 'spag', null,  'int');
		//$orderby = $jinput->get( 'orderby', null, 'get', 'int', 0);
		//$orderdir = $jinput->get( 'orderdir', 0, 'get', 'int', 0);
		//$featured_only = $jinput->get( 'featured_only', 0, 'get', 'int', 0);
		//$featured_first = $jinput->get( 'featured_first', 0, 'get', 'int', 0);
		$cols = (int)$jinput->get( 'cols', null, 'int');
		$rows = (int)$jinput->get( 'rows', null, 'int');
		//$mainimage = $jinput->get( 'mainimg', 'large', 'get', 'string', 0);
		$trunc = (int)$jinput->get( 'trunc', '0',  'int');
		$trunclimit = (int)$jinput->get( 'trunclimit', '0', 'int');
		
		$showreadmore = (int)$jinput->get( 'showreadmore', '1', 'int');
		$readmoretext = $jinput->get( 'readmoretext', '', 'string');
		$readmoretext = ($readmoretext != '') ? urldecode($readmoretext) : JText::_('COM_DJCATALOG2_READMORE');
		
		$paginationStart = (int)$jinput->get( 'pagstart', null, 'int');
		$categories = $jinput->get( 'categories', null,  'string');
		$catsw = $jinput->get( 'catsw', 0, 'int');
		$categories = explode('|', $categories);
		
		$largewidth = $jinput->get('largewidth','400','int');
		$largeheight = $jinput->get('largeheight','240','int');
		$largecrop = $jinput->get('largecrop',1,'int') ? true:false;
		$smallwidth = $jinput->get('smallwidth','90','int');
		$smallheight = $jinput->get('smallheight','70','int');
		$smallcrop = $jinput->get('smallcrop',1,'int') ? true:false;
		
		$Itemid = $jinput->get('Itemid',0, 'int');
		$path = JURI::base();
		
		$itemsCount = $this->getTotal();
		$itemsPerPage = $rows * $cols;
		$paginationBar = null;
		//if ($spag == 1) {
			$paginationBar ='<pagination><![CDATA[';
			if ($itemsCount > $itemsPerPage && $itemsPerPage > 0) {
				if ((int)$spag == 1) {
					for ($i = 0; $i < $itemsCount; $i = $i + $itemsPerPage) {
						$counter = (int)(($i+$itemsPerPage)/$itemsPerPage);
						if ($paginationStart == $i) $active=' active';
						else $active='';
						$paginationBar .= '<span class="btn'.$active.' button" style="cursor: pointer;" onclick="DJFrontpage_'.$mid.'.loadPage('.$i.'); return false;">'.$counter.'</span>&nbsp;';
					}
				} else {
					$prevPage = $paginationStart - $itemsPerPage;
					$nextPage = $paginationStart + $itemsPerPage;
					$firstPage = 0;
					$lastPage = $itemsPerPage * (ceil($itemsCount / $itemsPerPage) - 1);
					if ($paginationStart == 0) {
						$prevPage = $lastPage;
					}
					if ($paginationStart == $lastPage) {
						$nextPage = $firstPage;
					}
					
					$paginationBar .= '<span class="djcf_prev_button" style="cursor: pointer;" onclick="DJFrontpage_'.$mid.'.loadPage('.$prevPage.'); return false;"></span>&nbsp;';
					$paginationBar .= '<span class="djcf_next_button" style="cursor: pointer;" onclick="DJFrontpage_'.$mid.'.loadPage('.$nextPage.'); return false;"></span>';
				}				
			}
			$paginationBar .= ']]></pagination>';
		//}
		
		$items = $this->getList($paginationStart, $itemsPerPage);
		$output = '<?xml version="1.0" encoding="utf-8" ?><contents>';
		$gallery ='';
		for ($i = 0; $i  < count($items); $i++) {
			$title = '';
			$readmore = JRoute::_(DJCatalogHelperRoute::getItemRoute($items[$i]->slug, $items[$i]->catslug));
			if($stitle == '1') {
				if ($ltitle) {
					$title='<h3><a href="'.$readmore.'">'.$items[$i]->name.'</a></h3>';
				} else {
					$title='<h3>'.$items[$i]->name.'</h3>';
				}
			}
	
			$cattitle = '';
			if($scattitle == 1) {
				$cattitle='<h2>'.$items[$i]->category.'</h2>';
			}
			
			$producer = '';
			if($sproducer == 1 && $items[$i]->producer) {
				$producer = '<div class="djc_producer">'.JText::_('COM_DJCATALOG2_PRODUCER').': '.$items[$i]->producer.'</div>';
			}
			
			$price = '';
			if ($sprice == 1 && $items[$i]->price > 0) {
				if ($items[$i]->special_price) {
					$price = JText::_('COM_DJCATALOG2_PRICE').': <span class="djc_price_old">'.$items[$i]->price.'</span>&nbsp;<span class="djc_price_new">'.$items[$i]->special_price.'</span>';
				} else {
					$price = JText::_('COM_DJCATALOG2_PRICE').': <span class="djc_price">'.$items[$i]->price.'</span>';
				}
				$price = '<div class="djc_price">'.$price.'</div>';
			}
		

			
			if ($trunc > 0 && $trunclimit >0) {
				$items[$i]->intro_desc = DJCatalog2HtmlHelper::trimText($items[$i]->intro_desc, $trunclimit);
			} else if ($trunc > 0 && $trunclimit == -1) {
				$items[$i]->intro_desc = '';
			}
			
			$items[$i]->intro_desc = $producer.$price.$items[$i]->intro_desc;
			
			$output .= '<content>';
			if($scattitle == 1)
				$output .= '<category><![CDATA['.$cattitle.']]></category>';
			$output .= '<text><![CDATA['.$title.'<div class="djf_desc">'.$items[$i]->intro_desc.'</div>';
			
			if ($showreadmore == '1') {
				$output	.='<a class="btn btn-primary btn-large" href="'.$readmore.'">'.$readmoretext.'</a>';
			}
			$output	.= ']]></text>';
			$output .= '<image><![CDATA['.DJCatalog2ImageHelper::getProcessedImage($items[$i]->item_image, $largewidth, $largeheight, !$largecrop, $items[$i]->image_path).']]></image>';
			if ($modal == 1) {
				$output .= '<src><![CDATA['.DJCatalog2ImageHelper::getImageUrl($items[$i]->image_fullpath,'fullscreen').']]></src>';
			} else {
				$output .= '<src><![CDATA['.$readmore.']]></src>';
			}
			$output .= '</content>';
			if ($items[$i]->item_image) {
				$gallery .=	'<thumb><![CDATA[<div class="djf_cell img-polaroid"><a href="'.$readmore.'" onclick="DJFrontpage_'.$mid.'.loadItem('.$i.'); return false;"><img src="'.DJCatalog2ImageHelper::getProcessedImage($items[$i]->item_image, $smallwidth, $smallheight, !$smallcrop, $items[$i]->image_path).'" alt="'.$items[$i]->image_caption.'" /></a></div>]]></thumb>';				
			} else {
				$gallery .=	'<thumb><![CDATA[]]></thumb>';
			}
		}
		
		$all = $output.$gallery.$paginationBar;
		$all .= '</contents>';
		return $all;
	}
	function getList($start, $limit)
	{
		if (empty($this->_list))
		{
			$query = $this->_buildQuery();
			$this->_list = $this->_getList($query, $start, $limit);
		}
		
		foreach ($this->_list as $key => $item) {
			$catParams = $this->getCategoryParams($item->cat_id);
			
			if ($item->price != $item->final_price) {
				$this->_list[$key]->price = DJCatalog2HtmlHelper::formatPrice($item->price, $catParams);
				$this->_list[$key]->special_price = DJCatalog2HtmlHelper::formatPrice($item->special_price, $catParams);
			} else {
				$this->_list[$key]->price = DJCatalog2HtmlHelper::formatPrice($item->price, $catParams);
				$this->_list[$key]->special_price = null;
			}
		
			$this->_list[$key]->slug = (empty($this->_data[$key]->alias)) ? $this->_list[$key]->id : $this->_list[$key]->id.':'.$this->_list[$key]->alias;
			$this->_list[$key]->catslug = (empty($this->_data[$key]->category_alias)) ? $this->_list[$key]->category_id : $this->_list[$key]->category_id.':'.$this->_list[$key]->category_alias;
			$this->_list[$key]->prodslug = (empty($this->_data[$key]->producer_alias)) ? $this->_list[$key]->producer_id : $this->_list[$key]->producer_id.':'.$this->_list[$key]->producer_alias;
		}

		return $this->_list;
	}

	function getTotal()
	{
		if (empty($this->_total))
		{
			$query = $this->_buildQuery();
			$this->_total = $this->_getListCount($query);
		}

		return $this->_total;
	}

	function getPagination($start, $limit)
	{
		if (empty($this->_pagination))
		{
			jimport('joomla.html.pagination');
			$this->_pagination = new JPagination( $this->getTotal(), $start, $limit );
		}

		return $this->_pagination;
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
			//. ' LEFT JOIN #__djc2_images AS img ON img.item_id = i.id AND img.type=\'item\' AND img.ordering = 1 '
			. ' INNER JOIN (select im1.fullname, im1.caption, im1.type, im1.item_id, im1.path, im1.fullpath from #__djc2_images as im1, (select item_id, type, min(ordering) as lowest_order from #__djc2_images group by item_id, type) as im2 where im1.item_id = im2.item_id and im1.type=im2.type and im1.ordering = im2.lowest_order group by im1.type, im1.item_id, im1.path, im1.fullpath) AS img ON img.item_id = i.id AND img.type=\'item\''
			. $where
			. $orderby
		;
		//echo str_replace('#_', 'jos', $query);
		return $query;
	}

	function _buildContentOrderBy()
	{
		$jinput = JFactory::getApplication()->input;
		
		$featured_first = $jinput->get( 'featured_first', 0, 'int');
		$featured_order = '';
		if ($featured_first) {
			$featured_order = ' i.featured DESC, ';
		}
		
		$orderby = $jinput->get( 'orderby', null, 'int');
		$orderdir = $jinput->get( 'orderdir', 0, 'int');
		
		$orderbydir = ($orderdir == 0) ? ' ASC':' DESC'; 
		$featured_order = '';
		if ($featured_first) {
			$featured_order = ' i.featured DESC, ';
		}
		
		switch ($orderby) {
			case '0':
				$orderbyQuery = ' ORDER BY '.$featured_order.' i.ordering'.$orderbydir.', i.name';
				break;
			case '1':
				$orderbyQuery = ' ORDER BY '.$featured_order.' i.name'.$orderbydir.', i.ordering';
				break;
			case '2':
				$orderbyQuery = ' ORDER BY '.$featured_order.' c.ordering'.$orderbydir.', i.ordering';
				break;
			case '3':
				$orderbyQuery = ' ORDER BY '.$featured_order.' p.ordering'.$orderbydir.', i.ordering';
				break;
			case '4':
				$orderbyQuery = ' ORDER BY '.$featured_order.' i.price'.$orderbydir.', i.ordering';
				break;
			case '5':
				$orderbyQuery = ' ORDER BY '.$featured_order.' i.id'.$orderbydir.', i.ordering';
				break;
			case '6':
				$orderbyQuery = ' ORDER BY '.$featured_order.' i.created'.$orderbydir.', i.ordering';
				break;
			case '7':
				$orderbyQuery = ' ORDER BY '.$featured_order.' i.hits'.$orderbydir.', i.ordering';
				break;
			default:
				$orderbyQuery = ' ORDER BY '.$featured_order.' i.ordering'.$orderbydir.', i.name';
				break;
		}
		
		return $orderbyQuery;
	}

	function _buildContentWhere()
	{
		$jinput = JFactory::getApplication()->input;
		$view = $jinput->get('view');
		$db					= JFactory::getDBO();
		
		$featured_only = $jinput->get( 'featured_only', 0, 'int');
		$categories = $jinput->get( 'categories', null, 'string');
		$catsw = $jinput->get( 'catsw', 0,'int');
		$categories = explode('|', $categories);
		
		$nullDate = $db->quote($db->getNullDate());
		$date = JFactory::getDate();
		$nowDate = $db->quote($date->toSql());
		
		$where = array();
		if ($catsw && count($categories) > 0) {
			$categories = array_unique($categories);
			$catlist = implode(',',$categories);
			$db->setQuery('SELECT item_id 
						   FROM #__djc2_items_categories AS ic
						   INNER JOIN #__djc2_categories AS c ON c.id=ic.category_id 
						   WHERE category_id IN ('.$catlist.') AND c.published = 1');
			$items = $db->loadColumn();
			if (count ($items) > 0) {
				$items = array_unique($items);
				$where[] = 'i.id IN ('.implode(',',$items).')';
			} else $where[] = '1=0';
		}
		
		if ($featured_only > 0) {
			$where[] = 'i.featured = 1';
		}
		$where[] = 'i.published = 1';
		
		$where[] = '(i.publish_up = ' . $nullDate . ' OR i.publish_up >= ' . $nowDate . ')';
		$where[] = '(i.publish_down = ' . $nullDate . ' OR i.publish_down <= ' . $nowDate . ')';
		
		$where 		= ( count( $where ) ? ' WHERE '. implode( ' AND ', $where ) : '' );
		return $where;
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

