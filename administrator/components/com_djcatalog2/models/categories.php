<?php
/**
 * @version $Id: categories.php 272 2014-05-21 10:25:49Z michal $
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

// no direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');
jimport('joomla.application.component.helper');

class Djcatalog2ModelCategories extends JModelList
{
	public function __construct($config = array())
	{
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
				'id', 'a.id',
				'name', 'a.name',
				'alias', 'a.alias',
				'ordering', 'a.ordering',
				'checked_out', 'a.checked_out',
				'checked_out_time', 'a.checked_out_time',
				'published', 'a.published',
				'access', 'a.access', 'access_level',
				'created', 'a.created',
				'created_by', 'a.created_by',
				'language', 'a.language'
				);
		}

		parent::__construct($config);
	}
	protected function populateState($ordering = null, $direction = null)
	{
		parent::populateState('a.ordering', 'asc');
		
		// Initialise variables.
		$app = JFactory::getApplication();
		$session = JFactory::getSession();
		
		$search = $this->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');
		$this->setState('filter.search', $search);
		
		// Load the parameters.
		$params = JComponentHelper::getParams('com_djcatalog2');
		$this->setState('params', $params);
		//$this->setState('list.limit', 0);

	}
	
	protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id	.= ':'.$this->getState('filter.search');
	
		return parent::getStoreId($id);
	}

	protected function getListQuery()
	{
		// Create a new query object.
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		
		$categories = Djc2Categories::getInstance();

		// Select the required fields from the table.
		$select_default = 'a.*, uc.name AS editor, img.fullname AS item_image, img.caption AS image_caption, img.path as image_path, img.fullpath as image_fullpath';
		
		$query->select($this->getState('list.select', $select_default));
		$query->from('#__djc2_categories AS a');
		
		// Join over the users for the checked out user.
		//$query->select('uc.name AS editor');
		$query->join('LEFT', '#__users AS uc ON uc.id=a.checked_out');
		
		//$query->select('img.fullname AS item_image, img.caption AS image_caption');
		//$query->join('LEFT', '#__djc2_images AS img ON img.item_id=a.id AND img.type=\'category\' AND img.ordering=1');
		//$query->join('left', '(SELECT im1.* from #__djc2_images as im1 GROUP BY im1.item_id, im1.type ORDER BY im1.ordering asc) AS img ON img.item_id = a.id AND img.type=\'category\'');
		//$query->join('left', '(select im1.fullname, im1.caption, im1.type, im1.item_id, im1.path, im1.fullpath from #__djc2_images as im1, (select item_id, type, min(ordering) as lowest_order from #__djc2_images group by item_id, type) as im2 where im1.item_id = im2.item_id and im1.type=im2.type and im1.ordering = im2.lowest_order group by im1.type, im1.item_id, im1.path, im1.fullpath) AS img ON img.item_id = a.id AND img.type=\'category\'');
		$query->join('left', '#__djc2_images as img on img.id = (select id from #__djc2_images where type=\'category\' and item_id = a.id order by ordering asc limit 1)');
		
		// Filter by search in title.
		$search = $this->getState('filter.search');
		
		if (!empty($search)) {
			if (stripos($search, 'id:') === 0) {
				$category = $categories->get((int) substr($search, 3));
				$return_false = true;
				if ($category) {
					$path = $category->getPath();
					JArrayHelper::toInteger($path);
					if (count($path) > 0) {
						$query->where('a.id IN ('.implode(",", $path).')');
						$return_false = false;
					}
				}
				
				if ($return_false) {
					$query->where('1=0');
				}
			}
			else {
				$search = $db->quote('%'.$db->escape($search, true).'%');
				$id_query = $db->getQuery(true);
				$id_query->select('distinct id');
				$id_query->from('#__djc2_categories');
				$id_query->where('(name LIKE '.$search.' OR alias LIKE '.$search.')');
				$id_query->order('parent_id asc');

				$db->setQuery($id_query);
				$rows = $db->loadColumn();

				$return_false = true;
				$ids = array();
				if (count($rows) > 0){
					$rows = array_unique($rows);
					foreach($rows as $id) {
						$category = $categories->get($id);
						$path = $category->getPath();
						JArrayHelper::toInteger($path);
						$ids = array_merge($ids, $path);
					}
					$ids = array_unique($ids);
					
					if (count($ids) > 0) {
						$query->where('a.id IN ('.implode(",", $ids).')');
						$return_false = false;
					}
				}
				
				if ($return_false) {
					$query->where('1=0');
				}
			}
		}
		
		
		//$parent_id = JRequest::getInt('parent_id', 0);
		
		//$query->where('a.parent_id=\''.$parent_id.'\'');
		
		// Add the list ordering clause.
		$orderCol	= $this->state->get('list.ordering', 'a.ordering');
		$orderDirn	= $this->state->get('list.direction', 'asc');
		if ($orderCol == 'a.ordering') {
			$orderCol = 'a.parent_id '.$orderDirn.', a.ordering '.$orderDirn;
			$query->order($db->escape($orderCol));
		} else {
			$query->order($db->escape($orderCol.' '.$orderDirn));
		}
		return $query;
	}
	public function getItems() {
		
		$limitstart = $this->getState('list.start', 0);
		$limit = $this->getState('list.limit', 0);
		
		$this->setState('list.start',0);
		$this->setState('list.limit',0);
		
		$items = parent::getItems();
		//return $items;
		$children = array();
		foreach ($items as $item )
		{
			$pt = $item->parent_id;
			$list = array();
			if (array_key_exists($pt, $children)) {
				$list = $children[$pt];
			}
			array_push( $list, $item );
			$children[$pt] = $list;
		}
		$items = $this->makeCategoryTree(0, array(), $children,0);
		
		if ($limit > 0) {
			$items = array_slice($items, $limitstart, $limit);
		} else {
			$items = array_slice($items, 0);
		}
		
		$this->setState('list.start', $limitstart);
		$this->setState('list.limit', $limit);
		
		return $items;
	}
	function recreateThumbnails($cid = array())
	{
		if (count( $cid ))
		{
			JArrayHelper::toInteger($cid);
			$cids = implode( ',', $cid );

			$query = 'SELECT fullname FROM #__djc2_images'
					. ' WHERE item_id IN ( '.$cids.' )'
					. ' AND type=\'category\''
			;
			$this->_db->setQuery( $query );
			if (!$this->_db->query()) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
			$items = $this->_db->loadObjectList();
			$params = JComponentHelper::getParams( 'com_djcatalog2' );

			foreach($items as $item) {
				DJCatalog2ImageHelper::processImage(DJCATIMGFOLDER, $item->fullname, 'category', $params);
			}
		}
		return true;
	}
	protected function makeCategoryTree( $id, $list, &$children, $level=0) {
		if (array_key_exists($id, $children)) {
			foreach ($children[$id] as $child)
			{
				$id = $child->id;

				$pt = $child->parent_id;
				$list[$id] = $child;
				if (array_key_exists($id, $children)) {
					$list[$id]->children = count( $children[$id] );
				}
				else {
					$list[$id]->children = 0;
				}
				$list = $this->makeCategoryTree( $id, $list, $children, $level+1);
			}
				
		}
		return $list;
	}


}