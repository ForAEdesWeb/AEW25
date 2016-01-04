<?php
/**
 * @version $Id: view.feed.php 294 2014-06-28 19:59:07Z michal $
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
	
	function display($tpl = null) {
		$app = JFactory::getApplication();
		$document= JFactory::getDocument();
		$model = $this->getModel();
		
		$config = JFactory::getConfig();
		
		$siteEmail	= 		$config->get('mailfrom');
		$fromName         = $config->get('fromname');
		$feedEmail        = $config->get('feed_email', 'author');
		$document->editor = $fromName;
		
		if ($feedEmail != 'none')
		{
			$document->editorEmail = $siteEmail;
		}
		
		$menus		= $app->getMenu('site');
		$menu  = $menus->getActive();
		
		$mOption = (empty($menu->query['option'])) ? null : $menu->query['option'];
    	$mCatid = (empty($menu->query['cid'])) ? null : (int)$menu->query['cid'];
    	$mProdid   = (empty($menu->query['pid'])) ? null : (int)$menu->query['pid'];
		
		$filter_catid		= $app->input->get( 'cid',null,'int' );
		if ($filter_catid === null && $mOption == 'com_djcatalog2' && $mCatid) {
			$filter_catid = $mCatid;
			$app->input->set('cid', $filter_catid);
		}
		
		$filter_producerid	= $app->input->get( 'pid',null,'string' );
		if ($filter_producerid === null && $mOption == 'com_djcatalog2' && $mProdid) {
			$filter_producerid = $mProdid;
			$app->input->set('pid', $filter_producerid);
		}
		
		$params = Djcatalog2Helper::getParams();
		
		$params->set('product_catalogue', false);
		
		$filter_order		= $params->get('rss_items_default_order','i.date');
		$filter_order_Dir	= $params->get('rss_items_default_order_dir','desc');
		
		$limitstart	= $app->input->get('limitstart', 0, 'int');
		$limit_items_show = $params->get('rss_limit_items_show', $config->get('feed_limit', 10));
		$app->input->set('limit', $limit_items_show);
		
		$dispatcher	= JDispatcher::getInstance();
		$categories = Djc2Categories::getInstance(array('state'=>'1'));
		
		// current category
		$category = $categories->get((int) $app->input->get('cid',0,'default'));
		$subcategories = null;
		if (!empty($category)) {
			$subcategories = $category->getChildren();
		}
		/* If Cateogory not published set 404 */
		if (($category && $category->id > 0 && $category->published == 0) || empty($category)) {
			throw new Exception(JText::_('COM_DJCATALOG2_PRODUCT_NOT_FOUND'), 404);
		}
		
		$title = $params->get('page_title', '');
		
		if ($menu && ($menu->query['option'] != 'com_djcatalog2' || $menu->query['view'] != 'items' || $id != $category->id)) {
				
			if (!empty($category->metatitle)) {
				$title = $category->metatitle;
			}
			else if ($category->name && $category->id > 0) {
				$title = $category->name;
			}
		}
		if (empty($title)) {
			$title = $config->get('sitename');
		}
		
		$document->setTitle($title);
		
		$rows = $model->getItems();
		
		$document->link = JRoute::_(DJCatalogHelperRoute::getCategoryRoute($category->catslug));
		
		
		foreach ($rows as $row)
		{
			// Strip html from feed item title
			$title = $this->escape($row->name);
			$title = html_entity_decode($title, ENT_COMPAT, 'UTF-8');
		
			// Compute the article slug
			$row->slug = $row->alias ? ($row->id . ':' . $row->alias) : $row->id;
		
			// Url link to article
			$link = JRoute::_(DJCatalogHelperRoute::getItemRoute($row->slug, $category->id == 0 ? $row->catslug : $category->catslug));
		
			// Get description, author and date
			$date = $row->created;
		
			// Load individual item creator class
			$item           = new JFeedItem;
			$item->title    = $title;
			$item->link     = $link;
			$item->date     = $date;
			$item->author 	= $row->author;
			$item->category = ($category->id == 0) ? $row->category : $category->name;
			//$item->authorEmail = $siteEmail;
			
			if ($feedEmail == 'site')
			{
				$item->authorEmail = $siteEmail;
			}
			else if($feedEmail === 'author')
			{
				$item->authorEmail = $row->author_email;
			}
			
			$description = '';
			
			if ($row->item_image && (int)$params->get('rss_image_link_item', '1')) {
				//$item->image = new JFeedImage();
				//$item->image->url = DJCatalog2ImageHelper::getImageUrl($row->item_image,'small');
				//$item->image->link = $item->link;
				//$item->image->title = $row->image_caption;
				
				$description .= '<img src="'.DJCatalog2ImageHelper::getImageUrl($row->image_fullpath,'small').'" alt="'.$row->image_caption.'"/>';
			}
			
			if ($params->get('rss_description_type', '1') != '0')
			{
				$description .= ($params->get('rss_description_type', '1') == '1' && $row->intro_desc) ? $row->intro_desc : $row->description;
				if ($params->get('rss_showreadmore_item', '1') ) {
					$description .= '<p class="feed-readmore"><a target="_blank" href ="' . $item->link . '">' . JText::_('COM_DJCATALOG2_READMORE') . '</a></p>';
				}
			}
		
			// Load item description and add div
			$item->description	= '<div class="feed-description">'.$description.'</div>';
		
			// Loads item info into rss array
			$document->addItem($item);
		}
	}

}




