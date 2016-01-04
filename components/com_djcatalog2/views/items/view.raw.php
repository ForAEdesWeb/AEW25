<?php
/**
 * @version $Id: view.raw.php 125 2013-03-28 09:17:52Z michal $
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

jimport('joomla.application.component.view');
jimport('joomla.html.pagination');

class DJCatalog2ViewItems extends JViewLegacy {
	public function __construct($config = array())
	{
		parent::__construct($config);
		
		$this->_addPath('template', JPATH_COMPONENT.  '/themes/default/views/items');
		$theme = DJCatalog2ThemeHelper::getThemeName();
		if ($theme && $theme != 'default') {
			$this->_addPath('template', JPATH_COMPONENT.  '/themes/'.$theme.'/views/items');
		}
	}
	
	function display($tpl = null) {
		die('not supported yet');
		$app = JFactory::getApplication();
		
		
		$moduleclass_sfx = JRequest::getVar( 'moduleclass_sfx', null, 'default', 'string');
		$mid = (int)JRequest::getVar( 'moduleId', null, 'default', 'int');
		$stitle = JRequest::getVar( 'stitle', '1', 'default', 'string');
		$ltitle = JRequest::getVar( 'ltitle', '1', 'default', 'string');
		$scattitle = JRequest::getVar( 'scattitle', null, 'default', 'string');
		$spag = JRequest::getVar( 'spag', null, 'default', 'int');
		
		$trunc = (int)JRequest::getVar( 'trunc', '0', 'default', 'int');
		$trunclimit = (int)JRequest::getVar( 'trunclimit', '0', 'default', 'int');
		
		$showreadmore = (int)JRequest::getVar( 'showreadmore', '1', 'default', 'int');
		$readmoretext = JRequest::getVar( 'readmoretext', '', 'post');
		$readmoretext = ($readmoretext != '') ? urldecode($readmoretext) : JText::_('COM_DJCATALOG2_READMORE');
		
		$largewidth = JRequest::getVar('largewidth','400','default','int');
		$largeheight = JRequest::getVar('largeheight','240','default','int');
		$largecrop = JRequest::getVar('largecrop',1,'default','int') ? true:false;
		$smallwidth = JRequest::getVar('smallwidth','90','default','int');
		$smallheight = JRequest::getVar('smallheight','70','default','int');
		$smallcrop = JRequest::getVar('smallcrop',1,'default','int') ? true:false;
		
		JModelLegacy::addIncludePath(JPATH_BASE.DS.'components'.DS.'com_djcatalog2'.DS.'models');
		$model = JModelLegacy::getInstance('Items', 'Djcatalog2Model', array('ignore_request'=>true));
		$state = $model->getState();
		
		$params = Djcatalog2Helper::getParams();
		$model->setState('params', $params);
		
		$paginationStart = (int)JRequest::getVar( 'pagstart', null, 'default', 'int');
		$model->setState('list.start', $paginationStart);
		
		$cols = (int)JRequest::getVar( 'cols', null, 'default', 'int');
		$rows = (int)JRequest::getVar( 'rows', null, 'default', 'int');
		$itemsPerPage = $rows * $cols;
		$model->setState('list.limit', $itemsPerPage);
		
		$categories = JRequest::getVar( 'categories', null, 'default', 'string', 0);
		$catsw = JRequest::getVar( 'catsw', 0, 'default', 'int', 0);
		$categories = explode('|', $categories);
		if ($catsw && count($categories)) {
			$model->setState('filter.category',$categories);
			$model->setState('filter.catalogue',true);
		}
		
		$featured_only = JRequest::getVar( 'featured_only', 0, 'default', 'int', 0);
		if ($featured_only) {
			$model->setState('filter.featured', 1);
		}
		
		$featured_first = JRequest::getVar( 'featured_first', 0, 'default', 'int', 0);
		if ($featured_first) {
			$model->setState('list.ordering_featured', 1);
		}
		
		$filter_order		= JRequest::getVar( 'order',null,'default','cmd' );
		if ($filter_order) {
			$orderby = null;
			switch ($filter_order) {
				case '0':
					$orderby = 'i.ordering';
					break;
				case '1':
					$orderby = 'i.name';
					break;
				case '2':
					$orderby = 'c.ordering';
					break;
				case '3':
					$orderby = 'p.ordering';
					break;
				case '4':
					$orderby = 'i.price';
					break;
				case '5':
					$orderby = 'i.id';
					break;
				case '6':
					$orderby = 'i.created';
					break;
				default:
					$orderby = 'i.ordering';
					break;
			}
			$model->setState('list.ordering',$orderby);
		}
		
		$filter_order_Dir	= JRequest::getVar( 'dir',	0,'asc','default','word' );
		if ($filter_order_Dir) {
			$model->setState('list.direction',$filter_order_Dir);
		}
		
		$list = $model->getItems();
		
		$result = new stdClass();
		
		
		$result->current = $paginationStart;
		$result->total = $model->getTotal();
		$result->module_id = $mid;

		$result->items = array();
		foreach ($list as $item) {
			$element = new stdClass();
			$element->id 			= $item->id;
			$element->name 			= $item->name;
			$element->slug 		= $item->slug;
			$element->cat_id 		= $item->cat_id;
			$element->catslug 		= $item->catslug;
			$element->category 		= $item->category;
			$element->producer_id 	= $item->producer_id;
			$element->image 		= $item->item_image;
			$element->image_caption = $item->image_caption;
			$element->description 	= $item->intro_desc;
			$result->items[] = $element;
		}
		
		print_r($result);
		die();
		
        //parent::display($tpl);
	}
}




