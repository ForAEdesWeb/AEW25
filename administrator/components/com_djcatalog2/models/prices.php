<?php
/**
 * @version $Id: prices.php 278 2014-05-27 12:51:04Z michal $
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

class Djcatalog2ModelPrices extends JModelList
{
    public function __construct($config = array())
    {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = array(
                'id', 'a.id',
                'name', 'a.name',
                'alias', 'a.alias',
                'category_name',
                'producer_name',
                'ordering', 'a.ordering',
                'checked_out', 'a.checked_out',
                'checked_out_time', 'a.checked_out_time',
                'published', 'a.published',
                'a.featured',
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

        $search = $this->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');
        $this->setState('filter.search', $search);

        $published = $this->getUserStateFromRequest($this->context.'.filter.published', 'filter_published', '');
        $this->setState('filter.published', $published);

        $category = $this->getUserStateFromRequest($this->context.'.filter.category', 'filter_category', '');
        $this->setState('filter.category', $category);

        $producer = $this->getUserStateFromRequest($this->context.'.filter.producer', 'filter_producer', '');
        $this->setState('filter.producer', $producer);
        
        $customergroup = $this->getUserStateFromRequest($this->context.'.filter.customergroup', 'filter_customergroup', '');
        $this->setState('filter.customergroup', $customergroup);

        // Load the parameters.
        $params = JComponentHelper::getParams('com_djcatalog2');
        $this->setState('params', $params);
    }

    protected function getStoreId($id = '')
    {
        // Compile the store id.
        $id .= ':'.$this->getState('filter.search');
        $id .= ':'.$this->getState('filter.published');
        $id .= ':'.$this->getState('filter.category');
        $id .= ':'.$this->getState('filter.producer');
        $id .= ':'.$this->getState('filter.customergroup');
        $id .= ':'.$this->getState('filter.ids');

        return parent::getStoreId($id);
    }

    protected function getListQuery()
    {
        
        // Create a new query object.
        $db = $this->getDbo();
        $query = $db->getQuery(true);

        // Select the required fields from the table.
        
        $select_default = 'a.*, c.name AS category_name, c.id AS cat_id, p.name AS producer_name ';
        
        $query->select($this->getState('list.select', $select_default));
                $query->from('#__djc2_items AS a');

                // Join over the categories.
                //$query->select('c.name AS category_name, c.id AS cat_id');
                //$query->join('INNER', '#__djc2_items_categories AS ic ON a.id = ic.item_id AND ic.default=1');
                //$query->join('LEFT', '#__djc2_categories AS c ON c.id = ic.category_id');
                $query->join('LEFT', '#__djc2_categories AS c ON c.id = a.cat_id');
                
                // Join over the producers.
                //$query->select('p.name AS producer_name');
                $query->join('LEFT', '#__djc2_producers AS p ON p.id = a.producer_id');
                
                $query->select('t.value as tax_rate');
                $query->join('left', '#__djc2_tax_rates as t on a.tax_rate_id = t.id');
                
                // Join over the users for the checked out user.
                //$query->select('uc.name AS editor');
                //$query->join('LEFT', '#__users AS uc ON uc.id=a.checked_out');
                
                //$query->select('img.fullname AS item_image, img.caption AS image_caption');
                //$query->join('LEFT', '#__djc2_images AS img ON img.item_id=a.id AND img.type=\'item\' AND img.ordering=1');
                //$query->join('left', '(SELECT im1.* from #__djc2_images as im1 GROUP BY im1.item_id, im1.type ORDER BY im1.ordering asc) AS img ON img.item_id = a.id AND img.type=\'item\'');
                //$query->join('left', '(select im1.fullname, im1.caption, im1.type, im1.item_id, im1.path, im1.fullpath from #__djc2_images as im1, (select item_id, type, min(ordering) as lowest_order from #__djc2_images group by item_id, type) as im2 where im1.item_id = im2.item_id and im1.type=im2.type and im1.ordering = im2.lowest_order group by im1.type, im1.item_id, im1.path, im1.fullpath) AS img ON img.item_id = a.id AND img.type=\'item\'');
        
                
                // Filter by published state
                $published = $this->getState('filter.published');
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

                // Filter by category state
                $category = $this->getState('filter.category');
                if (is_numeric($category) && $category != 0) {
                    //$query->where('a.cat_id = ' . (int) $category);
                    $categories = Djc2Categories::getInstance();
                    if ($parent = $categories->get((int)$category) ) {
                        $childrenList = array($parent->id);
                        $parent->makeChildrenList($childrenList);
                        if ($childrenList) {
                            $cids = implode(',', $childrenList);
                            $db->setQuery('SELECT item_id 
                                           FROM #__djc2_items_categories AS ic
                                           INNER JOIN #__djc2_categories AS c ON c.id=ic.category_id 
                                           WHERE category_id IN ('.$cids.')');
                            $items = $db->loadColumn();
                            if (count ($items)) {
                                $items = array_unique($items);
                                $query->where('a.id IN ('.implode(',',$items).')');
                            } else {
                                $query->where('1=0');
                            }
                            //$where[] = 'i.cat_id IN ( '.$cids.' )';
                        }
                        else if ($category != 0){
                            $query->where('1=0');
                        }
                    }
                }

                // Filter by producer state
                $producer = $this->getState('filter.producer');
                if (is_numeric($producer) && $producer != 0) {
                    $query->where('a.producer_id = ' . (int) $producer);
                }
                
                // Filter by primary keys
                $item_ids = $this->getState('filter.ids');
                if ($item_ids != '') {
                    $query->where('a.id IN ('.$item_ids.')');
                }
                
                $customer_group = $this->getState('filter.customergroup');
                if (is_numeric($customer_group) && (int)$customer_group != 0) {
                	$query->select('cg.price as group_price');
                	$query->join('left', '#__djc2_prices as cg on cg.group_id ='.(int)$customer_group.' AND cg.item_id=a.id');
                }


                // Add the list ordering clause.
                $orderCol   = $this->state->get('list.ordering', 'a.name');
                $orderDirn  = $this->state->get('list.direction', 'asc');
                if ($orderCol == 'a.ordering' || $orderCol == 'category_name') {
                    $orderCol = 'c.name '.$orderDirn.', a.ordering';
                }

                $query->order($db->escape($orderCol.' '.$orderDirn));
                return $query;
    }
    
    protected function _getListCount($query)
    {
        // Use fast COUNT(*) on JDatabaseQuery objects if there no GROUP BY or HAVING clause:
        if ($query instanceof JDatabaseQuery
        && $query->type == 'select'
                && $query->group === null
                && $query->having === null)
        {
            
            $query = clone $query;
            $query->clear('select')->clear('order')->select('COUNT(*)');
            $this->_db->setQuery($query);
            return (int) $this->_db->loadResult();
        }
    
        // Otherwise fall back to inefficient way of counting all results.
        $this->_db->setQuery($query);
        $this->_db->execute();
    
        return (int) $this->_db->getNumRows();
    }

    public function getCategories(){
        if(empty($this->_categories)) {
            $query = "SELECT * FROM #__djc2_categories ORDER BY name";
            $this->_categories = $this->_getList($query,0,0);
        }
        return $this->_categories;
    }

    public function getProducers(){
        if(empty($this->_producers)) {
            $query = "SELECT * FROM #__djc2_producers ORDER BY name";
            $this->_producers = $this->_getList($query,0,0);
        }
        return $this->_producers;
    }

}