<?php
/**
 * @version $Id: producers.php 278 2014-05-27 12:51:04Z michal $
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

class Djcatalog2ModelProducers extends JModelList
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
		// List state information.
		parent::populateState('a.name', 'asc');
		
		// Initialise variables.
		$app = JFactory::getApplication();
		$session = JFactory::getSession();
		
		$params = Djcatalog2Helper::getParams();
		$this->setState('params', $params);

		//$search = $this->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');
		//$this->setState('filter.search', $search);

		//$published = $this->getUserStateFromRequest($this->context.'.filter.published', 'filter_published', '');
		//$this->setState('filter.published', $published);
		
		$order		= $app->input->get( 'order', $params->get('producers_default_order','a.ordering'), 'cmd' );
		$this->setState('list.ordering', $order);
		
		$order_dir	= $app->input->get( 'dir',	$params->get('producers_default_order_dir','asc'), 'word' );
		$this->setState('list.direction', $order_dir);

		// Load the parameters.
		$params = JComponentHelper::getParams('com_djcatalog2');
		
		$this->setState('params', $params);
	}

	protected function getStoreId($id = '')
	{
		// Compile the store id.
		//$id	.= ':'.$this->getState('filter.search');
		//$id	.= ':'.$this->getState('filter.published');
		
		$id	.= ':'.$this->getState('list.ordering');
		$id	.= ':'.$this->getState('list.direction');

		return parent::getStoreId($id);
	}

	protected function getListQuery()
	{
		// Create a new query object.
		$db = $this->getDbo();
		$query = $db->getQuery(true);

		// Select the required fields from the table.
		$select_default='a.*, img.fullname AS item_image, img.caption AS image_caption, img.path as image_path, img.fullpath as image_fullpath, uc.name AS editor';
		
		$query->select($this->getState('list.select', $select_default));
		
		$query->from('#__djc2_producers AS a');
		
		// Join over the users for the checked out user.
		//$query->select('uc.name AS editor');
		$query->join('LEFT', '#__users AS uc ON uc.id=a.checked_out');
		
		//$query->select('img.fullname AS item_image, img.caption AS image_caption');
		//$query->join('LEFT', '#__djc2_images AS img ON img.item_id=a.id AND img.type=\'producer\' AND img.ordering=1');
		//$query->join('left', '(SELECT im1.* from #__djc2_images as im1 GROUP BY im1.item_id, im1.type ORDER BY im1.ordering asc) AS img ON img.item_id = a.id AND img.type=\'producer\'');
		//$query->join('left', '(select im1.fullname, im1.caption, im1.type, im1.item_id, im1.path, im1.fullpath from #__djc2_images as im1, (select item_id, type, min(ordering) as lowest_order from #__djc2_images group by item_id, type) as im2 where im1.item_id = im2.item_id and im1.type=im2.type and im1.ordering = im2.lowest_order group by im1.type, im1.item_id, im1.path, im1.fullpath) AS img ON img.item_id = a.id AND img.type=\'producer\'');
		$query->join('left', '#__djc2_images as img on img.id = (select id from #__djc2_images where item_id = a.id and type=\'producer\' order by ordering asc limit 1)');
		
		
		// Filter by published state
		$published = $this->getState('filter.published', 1);
		if (is_numeric($published)) {
			$query->where('a.published = ' . (int) $published);
		}
		else if ($published === '') {
			$query->where('(a.published = 0 OR a.published = 1)');
		}


		// Filter by search in title.
		$search = $this->getState('filter.search');
		if (!empty($search)) {
			if (stripos($search, 'id:') === 0) {
				$query->where('a.id = '.(int) substr($search, 3));
			}
			else {
				$search = $db->quote('%'.$db->escape($search, true).'%');
				$query->where('(a.name LIKE '.$search.' OR a.alias LIKE '.$search.')');
			}
		}

		// Add the list ordering clause.
		$orderCol	= $this->state->get('list.ordering', 'a.ordering');
		$orderDirn	= $this->state->get('list.direction', 'asc');
		
		$order_fields = array('a.name', 'a.id', 'a.ordering');
		if (!in_array($orderCol, $order_fields)){
			$orderCol = 'a.ordering';
		}
		
		if ($orderDirn != 'asc' && $orderDirn != 'desc') {
			$orderDirn = 'asc';
		}
	
		$query->order($db->escape($orderCol.' '.$orderDirn));

		return $query;
	}
	
}