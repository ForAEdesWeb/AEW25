<?php
/**
 * @version $Id: items.php 317 2014-07-23 10:36:21Z michal $
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

jimport('joomla.application.component.modellist');

class DJCatalog2ModelItems extends JModelList {
	var $_list = null;
	var $_pagination = null;
	var $_total = null;
	var $_producers = null;
	var $_params = null;
	var $_attributes = array();
		
	function __construct($config = array())
	{
		parent::__construct($config);
	}
	
	protected function populateState($ordering = null, $direction = null)
	{
		$app = JFactory::getApplication();
		$params = Djcatalog2Helper::getParams();
		$this->setState('params', $params);

		$filter_featured	= $params->get('featured_only', 0);
		$this->setState('filter.featured', $filter_featured);
		
		$filter_catid		= (int) $app->input->get( 'cid', '0', 'int');
		$this->setState('filter.category', $filter_catid);
		
		$filter_catalogue = $params->get('product_catalogue', false) == true ? true : false;
		$this->setState('filter.catalogue', $filter_catalogue);
		
		$filter_producerid 	= (int) $app->input->get( 'pid',0, 'string' );
		$this->setState('filter.producer', $filter_producerid);
		
		$filter_index       =  $app->input->get( 'ind', false, 'string' );
		$this->setState('filter.index', ($filter_index === false) ? false : urldecode($filter_index) );
		
		$filter_owner 	= (int) $app->input->get( 'aid',0, 'string' );
		$this->setState('filter.owner', $filter_owner);
		
		$filter_price_from 	= $app->input->get( 'price_from',0, 'string' );
		$this->setState('filter.price_from', $filter_price_from);
		
		$filter_price_to 	= $app->input->get( 'price_to',0, 'string' );
		$this->setState('filter.price_to', $filter_price_to);
		
		$filters 			= $app->input->get('djcf',array(),'array');
		
		$request = $app->input->getArray($_REQUEST);
		foreach($request as $param=>$value) {
			if (!array_key_exists('djcf', $request)) {
				$request['djcf'] = array();
			}
			if (strstr($param, 'f_')) {
				$qkey = substr($param, 2);
				$qval = (strstr($value,',') !== false) ? explode(',',$value) : $value;
				unset($request[$param]);
				$request['djcf'][$qkey] = $qval;
			}
		}
		$filters = $request['djcf'];
		
		$this->setState('filter.customattribute', $filters);
		
		$searches 			= $app->input->get('djcs',array(), 'array');
		$this->setState('filter.customsearch', $searches);
		
		$globalSearch 		= urldecode($app->input->get( 'search','', 'string' ));
		$this->setState('filter.search', $globalSearch);
		
		$this->setState('filter.publish_date', true);
		 
		$this->setState('filter.state', '1');
		
		$order		= $app->input->get( 'order', $params->get('items_default_order','i.ordering'), 'cmd' );
		$this->setState('list.ordering', $order);
		
		$order_dir	= $app->input->get( 'dir',	$params->get('items_default_order_dir','asc'), 'word' );
		$this->setState('list.direction', $order_dir);
		
		$order_featured	= $params->get('featured_first', 0);
		$this->setState('list.ordering_featured', $order_featured);
		
		$limit		= $app->input->get( 'limit', $params->get('limit_items_show',10), 'int' );
		$this->setState('list.limit', $limit);
		
		$limitstart	= $app->input->get( 'limitstart', 0, 'int' );
		$this->setState('list.start', $limitstart);
		
	}
	
	protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id	.= ':'.$this->getState('list.select');
		$id	.= ':'.$this->getState('filter.featured');
		
		$filter_category = $this->getState('filter.category', 0);
		if (is_array($filter_category)) {
			JArrayHelper::toInteger($filter_category);
			$filter_category = implode(',',$filter_category);
		}
		$id	.= ':'.$filter_category;
		
		$filter_pks = $this->getState('filter.item_ids');
		if (is_array($filter_pks)) {
			JArrayHelper::toInteger($filter_pks);
			$filter_pks = implode(',',$filter_pks);
		}
		$id	.= ':'.$filter_pks;
		
		$id	.= ':'.$this->getState('filter.catalogue');
		$id	.= ':'.$this->getState('filter.producer');
		$id	.= ':'.$this->getState('filter.index');
		$id	.= ':'.$this->getState('filter.price_from');
		$id	.= ':'.$this->getState('filter.price_to');
		$id	.= ':'.serialize($this->getState('filter.customattribute'));
		$id	.= ':'.serialize($this->getState('filter.customsearch'));
		$id	.= ':'.$this->getState('filter.search', '1');
		$id	.= ':'.$this->getState('filter.state');
		$id	.= ':'.$this->getState('filter.owner');
		$id	.= ':'.$this->getState('list.ordering');
		$id	.= ':'.$this->getState('list.direction');
		$id	.= ':'.$this->getState('list.ordering_featured');
		$id	.= ':'.$this->getState('list.limit');
		$id	.= ':'.$this->getState('list.start');

		return md5($this->context . ':' . $id);
	}
	protected function _getList($query, $limitstart = 0, $limit = 0)
	{
		$this->_db->setQuery($query, $limitstart, $limit);
		$result = $this->_db->loadObjectList('id');

		return $result;
	}
	
	public function getItems()
	{
		// Get a storage key.
		$store = $this->getStoreId();

		// Try to load the data from internal storage.
		if (isset($this->cache[$store]))
		{
			return $this->cache[$store];
		}
		// Load the list items.
		$query = $this->_getListQuery();
		//echo str_replace('#_', 'jos',(string)$query);die();
		$items = $this->_getList($query, $this->getStart(), $this->getState('list.limit'));

		// Check for a database error.
		if ($this->_db->getErrorNum())
		{
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		// Add the items to the internal cache.
		$this->cache[$store] = $items;
		
		$this->bindAttributes($store);
		
		return $this->cache[$store];
	}
	protected function getListQuery()
	{
		return $this->buildQuery();
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
			$query->clear('select')->clear('order')->select('COUNT(distinct (i.id))');
			$this->_db->setQuery($query);
			//echo str_replace('#__', 'jos_', $query);die();
			return (int) $this->_db->loadResult();
		}
	
		// Otherwise fall back to inefficient way of counting all results.
		$this->_db->setQuery($query);
		$this->_db->execute();
	
		return (int) $this->_db->getNumRows();
	}
	
	public function buildQuery($ignoreFilters = array()) {
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		
		$where		= $this->_buildContentWhere($ignoreFilters, $query);
		$orderby	= $this->_buildContentOrderBy($query);
		$attributes = $this->getAttributes(true);
		$textSearch = array();
		
		$filters = $this->getState('filter.customattribute');
		$searches = $this->getState('filter.customsearch');
		$globalSearch = $this->getState('filter.search');
		
		//$query->select('distinct i.*');
		$list_select = $this->getState('list.select','distinct i.*, CASE WHEN (i.special_price > 0.0 AND i.special_price < i.price) THEN i.special_price ELSE i.price END as final_price');
		$ids_only = ($list_select == 'i.id') ? true : false;
		
		if ($ids_only) {
			$list_select = 'distinct i.id';
		}
		
		$query->select($list_select);
		
		$query->from('#__djc2_items as i');
		
		if (!$ids_only) {
			$query->select('c.id as _category_id, c.name as category, c.published as publish_category, c.alias as category_alias');
		}
		
		$query->join('left','#__djc2_categories AS c ON c.id = i.cat_id');
		
		if (!$ids_only) {
			$query->select('p.id as _producer_id, p.name as producer, p.published as publish_producer, p.alias as producer_alias');
		}
		
		$query->join('left','#__djc2_producers AS p ON p.id = i.producer_id');
		
		if (!$ids_only) {
			$query->select('ua.name AS author, ua.email AS author_email');	
		}
		
		$query->join('left', '#__users AS ua ON ua.id = i.created_by');
		
		/*if (!$ids_only) {
			$query->select('group_concat(distinct ic.category_id order by ic.category_id asc separator \'|\') AS categorylist');
			$query->join('left', '#__djc2_items_categories AS ic ON ic.item_id=i.id');
		}*/
		
		$globalSearch = trim(JString::strtolower( $globalSearch ));
		if (JString::substr($globalSearch,0,1) == '"' && JString::substr($globalSearch, -1) == '"') { 
			$globalSearch = JString::substr($globalSearch,1,-1);
		}
		if (JString::strlen($globalSearch) > 0 && (JString::strlen($globalSearch)) < 2 || JString::strlen($globalSearch) > 40) {
			$globalSearch = null;
		}
		
		$doTextSearch = !in_array('search', $ignoreFilters);
		if ($doTextSearch && $globalSearch) {
			
			$words = explode(' ', $globalSearch);
			$wheres = array();
			$phrase = 'all';
			foreach ($words as $word) {
				$word		= $db->quote('%'.$db->escape($word, true).'%', false);
				$wheres2	= array();
				$wheres2[]	= 'i.description LIKE '.$word;
				$wheres2[]	= 'i.intro_desc LIKE '.$word;
				$wheres2[]	= 'i.name LIKE '.$word;
				$wheres2[]	= 'p.name LIKE '.$word;
				$wheres2[]	= 'c.name LIKE '.$word;
				$wheres[]	= implode(' OR ', $wheres2);
			}
			
			$textSearch[]	= '(' . implode(($phrase == 'all' ? ') AND (' : ') OR ('), $wheres) . ')';
			
			/*$textSearch[] = 'LOWER(i.name) LIKE '.$db->quote( '%'.$db->escape( $globalSearch, true ).'%', false );
			$textSearch[] = 'LOWER(i.description) LIKE '.$db->quote( '%'.$db->escape( $globalSearch, true ).'%', false );
			$textSearch[] = 'LOWER(i.intro_desc) LIKE '.$db->quote( '%'.$db->escape( $globalSearch, true ).'%', false );
			$textSearch[] = 'LOWER(c.name) LIKE '.$db->quote( '%'.$db->escape( $globalSearch, true ).'%', false );
			$textSearch[] = 'LOWER(p.name) LIKE '.$db->quote( '%'.$db->escape( $globalSearch, true ).'%', false );
			*/
			
			$optionsSearch = 
			     ' select i.id '
				.' from #__djc2_items as i '
				.' inner join #__djc2_items_extra_fields_values_int as efv on efv.item_id = i.id'
				.' inner join #__djc2_items_extra_fields as ef on ef.id = efv.field_id and ef.searchable = 1 '
				.' inner join #__djc2_items_extra_fields_options as efo on efo.id = efv.value and lower(efo.value) like '.$db->quote( '%'.$db->escape( $globalSearch, true ).'%', false )
				.' union '
				. 'select i.id '
				.' from #__djc2_items as i '
				.' inner join #__djc2_items_extra_fields_values_text as efv on efv.item_id = i.id'
				.' inner join #__djc2_items_extra_fields as ef on ef.id = efv.field_id and ef.searchable = 1 and lower(efv.value) like '.$db->quote( '%'.$db->escape( $globalSearch, true ).'%', false )
				;
				
			$query->join('LEFT', '('.$optionsSearch.') AS customattribute_search ON customattribute_search.id = i.id');
			$textSearch[] = 'i.id = customattribute_search.id';
		}
		
		
		$doCustomSearch = !in_array('custom_fields', $ignoreFilters);
		
		if ($doCustomSearch) {
			$filter_unions = array();
			foreach ($attributes as $key=>$attribute) {
				$attributes[$key]->alias = str_replace('-', '_', $attribute->alias);
				
				if (!empty($filters[$attribute->alias])) {
					$filter = $filters[$attribute->alias];
					if ($attribute->filterable == 1) {
						
						if (is_scalar($filter) && strpos($filter, ',') !== false) {
							$filter = explode(',', $filter);
						}
						
						if (is_array($filter)) {
							if ($attribute->type == 'checkbox') {
								foreach($filter as $key=>$opt) {
									if (is_scalar($opt)) {
										$filter_unions[] = '(select * from #__djc2_items_extra_fields_values_int where field_id='.$attribute->id.' and value='.(int)$opt.')';
									}
								}	
							} else {
								$terms = array();
								foreach($filter as $key=>$opt) {
									if (is_scalar($opt)) {
										$terms[] = 'value = '.(int)$opt;
									}
								}
								if (count($terms) > 0) {
									$condition = implode(' OR ', $terms);
									$filter_unions[] = '(select * from #__djc2_items_extra_fields_values_int where field_id='.$attribute->id.' and ('.$condition.'))';
								}
							}
						} else {
							$filter_unions[] = '(select * from #__djc2_items_extra_fields_values_int where field_id='.$attribute->id.' and value='.(int)$filter.')';
						}
					}
				}
				
			}
			
			if (count($filter_unions) > 0) {
				$unionQuery = 'select * from (select count(*) as c, item_id from ('.implode(' union ', $filter_unions).') as f group by f.item_id) as filter_counter where filter_counter.c='.count($filter_unions);
				$query->join('inner', '('.$unionQuery.') as filters on filters.item_id = i.id');
			}
		}
		
		if ($doTextSearch && count($textSearch)) {
			$where[] = ' ( '.implode( ' OR ', $textSearch ).' ) ';
		}
		
		if (count($where) > 0) {
			$query->where($where);
		}
		//$query->group('i.id');
		$query->order($orderby);
		//echo str_replace('#_','jos',$query).'<br/>';die();
		return $query;
	}


	function _buildContentOrderBy($query)
	{
		$db = JFactory::getDbo();
		
		$filter_order		= $this->getState('list.ordering');
		$filter_order_Dir	= $this->getState('list.direction');
		$filter_featured	= $this->getState('list.ordering_featured');
		
		$params = $this->getState('params',  Djcatalog2Helper::getParams());
		
		$sortables = array('i.ordering', 'i.name', 'i.created', 'i.price', 'category', 'c.name', 'producer', 'p.name', 'i.id', 'rand()');
		
		if (!in_array($filter_order, $sortables)) {
			$filter_alias = false;
			if (strpos($filter_order, 'f_') !== false) {
				$alias = preg_replace('#^(f_)([a-zA-Z0-9_\-]+)#', '$2', $filter_order);
				if ($alias) {
					$db->setQuery('select * from #__djc2_items_extra_fields where alias='.$db->quote($db->escape($alias)).' and published = 1 and sortable = 1 and (type = \'text\' || type = \'radio\' || type = \'select\' || type = \'calendar\')');	
					$field = $db->loadObject();
					if (!empty($field)) {
						
						$filter_order = 'customattribute_order.value';
						$filter_alias = true;
						
						if ($field->type == 'calendar') {
							$query->join('LEFT', '#__djc2_items_extra_fields_values_date AS customattribute_order ON customattribute_order.item_id = i.id AND customattribute_order.field_id='.(int)$field->id);
						} else if ($field->type == 'text') {
							$query->join('LEFT', '#__djc2_items_extra_fields_values_text AS customattribute_order ON customattribute_order.item_id = i.id AND customattribute_order.field_id='.(int)$field->id);
						} else {
							$filter_order = 'customattribute_order.ordering';
							$query->join('LEFT', '#__djc2_items_extra_fields_values_int AS customattribute_order_ref ON customattribute_order_ref.item_id = i.id AND customattribute_order_ref.field_id='.(int)$field->id);
							$query->join('LEFT', '#__djc2_items_extra_fields_options AS customattribute_order ON customattribute_order_ref.value = customattribute_order.id');
						}
					}
				}
			}
			
			if (!$filter_alias) {
				$filter_order = 'i.ordering';
			}
		}
		
		if ($filter_order_Dir != 'asc' && $filter_order_Dir != 'desc') {
			$filter_order_Dir = 'asc';
		}
		
		if ($filter_order == 'i.ordering'){
			if ($filter_featured) {
				if ($params->get('items_category_ordering', '1') != '1') {
					$orderby = ' i.featured DESC, i.ordering '.$filter_order_Dir.', c.ordering '.$filter_order_Dir;
				} else {
					$orderby = 'i.featured DESC, c.parent_id asc, c.ordering asc, i.ordering '.$filter_order_Dir;
				}
				
			} else {
				if ($params->get('items_category_ordering', '1') != '1') {
					$orderby = ' i.ordering '.$filter_order_Dir.', c.ordering '.$filter_order_Dir;
				} else {
					$orderby = 'c.parent_id asc, c.ordering asc, i.ordering '.$filter_order_Dir;
				}
			}
		} else {
			// older version compatibility
			switch ($filter_order) {
				case 'producer': {
					$filter_order = 'p.name';
					break;
				}
				case 'category': {
					$filter_order = 'c.name';
					break;
				}
				case 'i.price' : {
					$filter_order = 'final_price';
					break;
				}
			}
			if ($filter_featured) {
				$orderby 	= ' i.featured DESC, '.$filter_order.' '.$filter_order_Dir.' , i.ordering, c.ordering ';
			}
			else {
				$orderby 	= ' '.$filter_order.' '.$filter_order_Dir.' , i.ordering, c.ordering ';
			}
		}
		return $orderby;
	}

	function _buildContentWhere($ignoreFilters = array(), &$query)
	{
		$view = JFactory::getApplication()->input->get('view');
		$db					= JFactory::getDBO();
		
		$params = $this->getState('params');
		
		$filter_featured	= $this->getState('filter.featured');
		
		$filter_catid		= $this->getState('filter.category');
		$filter_catalogue		= $this->getState('filter.catalogue');
		$filter_producerid  = $this->getState('filter.producer');
		$filter_pks 		= $this->getState('filter.item_ids');
		
		$filter_price_from  = $this->getState('filter.price_from');
		$filter_price_to  = $this->getState('filter.price_to');
		
		$filter_index       =  $this->getState('filter.index', false);
		
		$filter_state 		= $this->getState('filter.state', '1');
		
		$filter_owner = 	$this->getState('filter.owner');

		$where = array();
		
		///// new
		$category_subquery = 'SELECT ic.item_id '
							.'FROM #__djc2_items_categories AS ic '
						   	.'INNER JOIN #__djc2_categories AS c ON c.id=ic.category_id '
						   	.'WHERE c.published = 1';
		

		if (is_array($filter_catid) && !empty($filter_catid)) {
			JArrayHelper::toInteger($filter_catid);
			$category_subquery .= ' AND category_id IN ('.implode(',',$filter_catid).')';
		} else if ((int)$filter_catid >= 0) {
			if ($filter_catalogue && is_scalar($filter_catid)) {
				$category_subquery .= ' AND ic.category_id ='.(int)$filter_catid;
			} else if ((int)$filter_catid > 0) {
				$categories = Djc2Categories::getInstance(array('state'=>'1'));
				if ($parent = $categories->get((int)$filter_catid) ) {
					$childrenList = array($parent->id);
					$parent->makeChildrenList($childrenList);
					if ($childrenList) {
						$cids = implode(',', $childrenList);
						$category_subquery .= ' AND ic.category_id IN ('.$cids.')';
					} else if ($filter_catid != 0){
						JError::raiseError( 404, JText::_("COM_DJCATALOG2_PAGE_NOT_FOUND") );
					}
				}
			}
		}
		
		$query->join('inner', '('.$category_subquery.') as category_filter ON i.id = category_filter.item_id');
		
		/// ------
		
		if (!in_array('producer', $ignoreFilters) && $filter_producerid > 0) {
            $where[] = 'i.producer_id = '.(int) $filter_producerid;
        }
        
        if (!in_array('price', $ignoreFilters)) {
        	if ($filter_price_from > 0) {
        		$where[] = 'i.price >= '. floatval(str_replace(',', '.', $filter_price_from));
        	}
        	if ($filter_price_to > 0) {
        		$where[] = 'i.price <= '. floatval(str_replace(',', '.', $filter_price_to));
        	}
        }
		
		if (!in_array('featured', $ignoreFilters) && $filter_featured > 0) {
			$where[] = 'i.featured = 1';
		}
		
		$nullDate = $db->quote($db->getNullDate());
		$date = JFactory::getDate();
		$nowDate = $db->quote($date->toSql());
		
		if ($this->getState('filter.publish_date', true)){
			$query->where('(i.publish_up = ' . $nullDate . ' OR i.publish_up <= ' . $nowDate . ')');
			$query->where('(i.publish_down = ' . $nullDate . ' OR i.publish_down >= ' . $nowDate . ')');
		}
		
		if (!in_array('atoz', $ignoreFilters) && $filter_index !== false) {
            //$where[] = ' LOWER(i.name) LIKE '.$db->quote( $db->escape( $filter_index, true ).'%', false );
            if ($filter_index === 'num') {
            	$where[] = '( i.name REGEXP '.$db->quote( '^[0-9]', false ) .')';
            } else {
            	$where[] = '( LOWER(i.name) LIKE '.$db->quote( $db->escape( $filter_index, true ).'%', false ) . ' COLLATE utf8_bin ' .
            			' OR UPPER(i.name) LIKE '.$db->quote( $db->escape( $filter_index, true ).'%', false ) .' COLLATE utf8_bin )';
            }
        }
        
        if (!in_array('item_ids', $ignoreFilters) && !empty($filter_pks) && is_array($filter_pks)) {
        	JArrayHelper::toInteger($filter_pks);
        	$query->join('inner', '(select id from #__djc2_items where id in ('.implode(',',$filter_pks).')) AS item_pks on item_pks.id = i.id');
        }
        
        if (!in_array('owner', $ignoreFilters) && $filter_owner > 0) {
        	$where[] = 'i.created_by = '.$filter_owner;
        }
        
        if ($filter_state == '1') {
        	$where[] = 'i.published = 1';
        } else if ($filter_state == '-1') {
        	$where[] = 'i.published = 0';
        }
        
		return $where;
	}
	
	function getAttributes($all = false) {
		$idx = $all ? 0 : 1;
		if (!isset($this->_attributes[$idx])) {
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query->select('f.*, group_concat(fo.id order by fo.ordering asc separator \'|\') as options');
			$query->from('#__djc2_items_extra_fields as f');
			$query->join('LEFT', '#__djc2_items_extra_fields_options as fo ON fo.field_id=f.id');
				
			if ($all) {
				$query->where('f.published = 1');
			} else {
				$query->where('(f.visibility = 2 or f.visibility = 3) and f.published = 1');
			}
			$query->group('f.id');
			//$query->order('f.group_id asc, f.ordering asc, fo.ordering asc');
			$query->order('f.group_id asc, f.ordering asc');
			$db->setQuery($query);
			$this->_attributes[$idx] = $db->loadObjectList('id');
		}
		return $this->_attributes[$idx];
	}

	function bindAttributes($store) {
		if (!empty($this->cache[$store])) {
			$ids = array_keys($this->cache[$store]);
			if (empty($ids)) {
				return;
			}
			$db = JFactory::getDbo();
			
			$query_int = $db->getQuery(true);
			$query_text = $db->getQuery(true);
			$query_date = $db->getQuery(true);
			
			$query_int->select('fields.alias, fields.type, fields.ordering, fieldvalues.item_id, fieldvalues.field_id, fieldvalues.id as value_id, fieldoptions.id as option_id, fieldoptions.value');
			$query_int->from('#__djc2_items_extra_fields_values_int as fieldvalues');
			$query_int->join('inner', '#__djc2_items as items on items.id=fieldvalues.item_id' );
			$query_int->join('inner','#__djc2_items_extra_fields as fields ON fields.id = fieldvalues.field_id');
			$query_int->join('left','#__djc2_items_extra_fields_options as fieldoptions ON fieldoptions.id = fieldvalues.value AND fieldoptions.field_id = fields.id');
			$query_int->where('fieldvalues.item_id IN ('.implode(',',$ids).') AND (fields.visibility = 2 OR fields.visibility = 3) AND fields.published = 1');
			$query_int->order('fieldvalues.field_id asc, fieldvalues.field_id asc');
			
			$query_text->select('fields.alias, fields.type, fields.ordering, fieldvalues.item_id, fieldvalues.field_id, fieldvalues.id as value_id, 0 as option_id, fieldvalues.value');
			$query_text->from('#__djc2_items_extra_fields_values_text as fieldvalues');
			$query_text->join('inner', '#__djc2_items as items on items.id=fieldvalues.item_id' );
			$query_text->join('inner','#__djc2_items_extra_fields as fields ON fields.id = fieldvalues.field_id');
			$query_text->where('fieldvalues.item_id IN ('.implode(',',$ids).') AND (fields.visibility = 2 OR fields.visibility = 3) AND fields.published = 1');
			$query_text->order('fieldvalues.field_id asc, fieldvalues.field_id asc');
			
			$query_date->select('fields.alias, fields.type, fields.ordering, fieldvalues.item_id, fieldvalues.field_id, fieldvalues.id as value_id, 0 as option_id, fieldvalues.value');
			$query_date->from('#__djc2_items_extra_fields_values_date as fieldvalues');
			$query_date->join('inner', '#__djc2_items as items on items.id=fieldvalues.item_id' );
			$query_date->join('inner','#__djc2_items_extra_fields as fields ON fields.id = fieldvalues.field_id');
			$query_date->where('fieldvalues.item_id IN ('.implode(',',$ids).') AND (fields.visibility = 2 OR fields.visibility = 3) AND fields.published = 1');
			$query_date->order('fieldvalues.field_id asc, fieldvalues.field_id asc');
			
			//$query = 'SELECT * FROM (('.(string)$query_int.') UNION DISTINCT ('.(string)$query_text.')) as list ORDER BY list.field_id asc, list.item_id asc';
			//echo str_replace('#_','jos',$query);die();
			
			// I decided not to use UNION because of FaLang translation issues
			
			$db->setQuery($query_int);
			$int_attributes = $db->loadObjectList();
			
			$db->setQuery($query_text);
			$text_attributes = $db->loadObjectList();
			
			$db->setQuery($query_date);
			$date_attributes = $db->loadObjectList();
			
			
			foreach ($text_attributes as $attribute) {
				$field = '_ef_'.$attribute->alias;
				$this->cache[$store][$attribute->item_id]->$field = $attribute->value;
				//$this->cache[$store][$attribute->item_id]->$field = $attribute->optionvalues ? $attribute->optionvalues : $attribute->value;
			}
			foreach ($date_attributes as $attribute) {
				$field = '_ef_'.$attribute->alias;
				$this->cache[$store][$attribute->item_id]->$field = $attribute->value;
			}
			foreach ($int_attributes as $attribute) {
				$field = '_ef_'.$attribute->alias;
				if (!isset($this->cache[$store][$attribute->item_id]->$field) || !is_array($this->cache[$store][$attribute->item_id]->$field)) {
					$this->cache[$store][$attribute->item_id]->$field = array();
				}
				$tmp_arr = $this->cache[$store][$attribute->item_id]->$field;
				$tmp_arr[] = $attribute->value;
				$this->cache[$store][$attribute->item_id]->$field = $tmp_arr;
			}
			
			$query = $db->getQuery(true);
			$query->select('i.id, img.fullname as item_image, img.caption AS image_caption, img.path AS image_path, img.fullpath AS image_fullpath');
			$query->from('#__djc2_items as i');
			
			//$query->join('inner', '(select im1.fullname, im1.caption, im1.type, im1.item_id, im1.path, im1.fullpath from #__djc2_images as im1, (select item_id, type, min(ordering) as lowest_order from #__djc2_images group by item_id, type) as im2 where im1.item_id = im2.item_id and im1.type=im2.type and im1.ordering = im2.lowest_order group by im1.type, im1.item_id, im1.path, im1.fullpath) AS img ON img.item_id = i.id AND img.type=\'item\'');
			$query->join('inner', '#__djc2_images as img on img.id=(select id from #__djc2_images where type=\'item\' and item_id=i.id order by ordering asc limit 1)');
			$query->where('i.id IN ('.implode(',', $ids).')');
			$db->setQuery($query);
			$image_list = $db->loadObjectList('id');
			
			foreach($this->cache[$store] as &$row) {
				$row->slug = empty($row->alias) ? $row->id : $row->id.':'.$row->alias;
				$row->catslug = empty($row->category_alias) ? $row->cat_id : $row->cat_id.':'.$row->category_alias;
				$row->prodslug = empty($row->producer_alias) ? $row->producer_id : $row->producer_id.':'.$row->producer_alias;
				
				$row->item_image = isset($image_list[$row->id]) ? $image_list[$row->id]->item_image : null;
				$row->image_caption = isset($image_list[$row->id]) ? $image_list[$row->id]->image_caption : null;
				$row->image_path = isset($image_list[$row->id]) ? $image_list[$row->id]->image_path : null;
				$row->image_fullpath = isset($image_list[$row->id]) ? $image_list[$row->id]->image_fullpath : null;
			}
		}
	}
	
	function getProducers(){
		if(!$this->_producers) {
			$db = JFactory::getDbo();
			$filter_catid		= $this->getState('filter.category');
			$filter_producerid    = $this->getState('filter.producer');
			
			$query = null;
			if ($filter_catid > 0) {
				$categories = Djc2Categories::getInstance(array('state'=>'1'));
				if ($parent = $categories->get((int)$filter_catid) ) {
					$childrenList = array($parent->id);
					$parent->makeChildrenList($childrenList);
					$query = 'SELECT DISTINCT p.id, p.name as text '
							//. ' CASE WHEN CHAR_LENGTH(p.alias) THEN CONCAT_WS(":", p.id, p.alias) ELSE p.id END as value '
							.' FROM #__djc2_producers as p '
							.' INNER JOIN #__djc2_items AS i ON p.id = i.producer_id '
							.' INNER JOIN #__djc2_items_categories AS c ON c.item_id = i.id '
							.' WHERE c.category_id IN ('.implode(',', $childrenList).') AND p.published=1 '
							.' GROUP BY p.id, p.name'
							.' ORDER BY p.name ASC ';
				}
			} else {
				$query = 'SELECT p.id, p.name as text '
					//. ' CASE WHEN CHAR_LENGTH(p.alias) THEN CONCAT_WS(":", p.id, p.alias) ELSE p.id END as value '
					.' FROM #__djc2_producers as p WHERE p.published=1 ORDER BY text';
			}
			$db->setQuery($query);
			$items = $db->loadObjectList();
			foreach ($items as $k=>$v) {
				$items[$k]->value = (!empty($v->alias)) ? $v->id .':'. $v->alias : $v->id;
			}
			$this->_producers = $items;
		}
		return $this->_producers;
	}	
	
	function getParams() {
		return Djcatalog2Helper::getParams();
	}
    
    function getSubCategories($category) {
        /*
        $db = JFactory::getDbo();
        $parent_id = $category->id;
        $db->setQuery('
                select c.id as category_id, count(i.id) as item_count
                from #__djc2_categories as c
                left join #__djc2_items_categories as ic on c.id = ic.category_id 
        		left join #__djc2_items as i on i.id = ic.item_id
        		where i.published = 1 OR i.id IS NULL 
                group by c.id
                order by c.parent_id, c.ordering asc, c.name asc
            ');   
        
        $categoryList = $db->loadObjectList('category_id');

        $children = $category->getChildren();
        
        foreach ($children as $k=>$v) {
            $this->countChildren($v, $categoryList);
        }
        
        $subcategories = array();
        foreach ($children as $subcategory) {
            if (array_key_exists($subcategory->id, $categoryList)) {
                $subcategories[] = $subcategory;
            }
        }
        
        return $subcategories;*/
    	
    	$db = JFactory::getDbo();
    	$parent_id = $category->id;
    	
    	$children = $category->getChildren();
    	
    	$categories = Djc2Categories::getInstance(array('state'=>'1'));
    	$subcategories = array();
    	
    	$category_subquery = 'SELECT ic.item_id '
    			.'FROM #__djc2_items_categories AS ic '
    			.'INNER JOIN #__djc2_categories AS c ON c.id=ic.category_id '
    			.'WHERE c.published = 1';
    	
    	foreach ($children as $k=> $child) {
    		if ($parent = $categories->get((int)$child->id) ) {
    			$childrenList = array($parent->id);
    			$parent->makeChildrenList($childrenList);
    			if ($childrenList) {
    				$cids = implode(',', $childrenList);
    				$child_subquery =  $category_subquery . ' AND ic.category_id IN ('.$cids.')';
    				
    				$db->setQuery('
    						SELECT COUNT(DISTINCT i.id)
    						FROM #__djc2_items AS i
    						INNER JOIN ('.$child_subquery.') AS category_filter ON i.id = category_filter.item_id
    						WHERE i.published = 1
    						');
    				$children[$k]->item_count = $db->loadResult();
    				$subcategories[$child->id] = $children[$k];
    			}
    		}
    		
    	}
    	
    	return $subcategories;
    }
    
    protected function countChildren(&$node, &$countList) {
        $children = $node->getChildren();
        $node->item_count = (isset($countList[$node->id])) ? $countList[$node->id]->item_count : 0;
        if (count($children)) {
            foreach ($children as $child) {
                $node->item_count += $this->countChildren($child, $countList);
            }
        }
        
        return $node->item_count;
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
                $list[$id]->level = $level;
                $list = $this->makeCategoryTree( $id, $list, $children, $level+1);
            }
                
        }
        return $list;
    }
    
    public function getIndexCount() {
    	$cparams = Djcatalog2Helper::getParams();
    	$params = new JRegistry();
    	$params->merge($cparams);
    	
    	$letters_str = trim($cparams->get('atoz_letters', ''));
    	$letters = array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z');
    	
    	if (!empty($letters_str)) {
    		$letters = explode(',', $letters_str);
    	}
    	
    	$obj = array();
    	
    	$query = '';
    	
    	if ((int)$params->get('atoz_check_available', 0) == 1) {
    		$db = JFactory::getDBO();
    		$model = JModelLegacy::getInstance('Items', 'Djcatalog2Model');
    		$state = $model->getState();
    		//$params->set('product_catalogue', 0);
    		//$params->set('limit_items_show', 0);
    		$model->setState('params', $params);
    		$model->setState('list.start', 0);
    		$model->setState('list.limit', 0);
    		$model->setState('list.select', 'i.id');
    		$model->setState('filter.catalogue', false);
    		
    		$model->setState('filter.index', false);
    		
    		$model->setState('list.ordering', 'i.id');
    		$model->setState('list.direction', 'asc');
    		
    		$items_query = $model->buildQuery();
    		 
    		$select = $join = array();
    		foreach ($letters as $letter) {
    			if ($letter && $letter != ',') {
    				$tbljoin = 'table_'.$letter;
    				if ($letter == 'num') {
    					$select[] = ' count('.$tbljoin.'.id) as count_'.$letter;
    					$join[] = 'left join #__djc2_items as '.$tbljoin.' on '.$tbljoin.'.id = items.id and '.$tbljoin.'.name REGEXP \'^[0-9]\'';
    				} else {
    					$select[] = ' count('.$tbljoin.'.id) as count_'.$letter;
    					$join[] = 'left join #__djc2_items as '.$tbljoin.' on '.$tbljoin.'.id = items.id and lower('.$tbljoin.'.name) like \''.$letter.'%\'';
    				}
    			}
    		}
    		 
    		$query .= 'SELECT '.implode(', ',$select).PHP_EOL.' FROM #__djc2_items as items '.PHP_EOL.implode(PHP_EOL,$join).' where items.id in ('.$items_query.')';
    		$db->setQuery($query);
    		$obj = $db->loadAssoc();

    		foreach ($obj as $k=>$v) {
    			$justletter = str_replace('count_', '', $k);
    			$obj[$justletter] = $k;
    			unset($obj[$k]);
    		}
    	}
    	
    	if (empty($obj)) {
    		$obj = array();
    		foreach ($letters as $letter) {
    			if ($letter && $letter != ',') {
    				$obj[$letter] = 1;
    			}
    		}
    	}
    	
    	return $obj;
    }
    
    public function getFieldGroups() {
    	$params = Djcatalog2Helper::getParams();
    	$db = JFactory::getDBO();
    	$model = JModelLegacy::getInstance('Items', 'Djcatalog2Model');
    	$state = $model->getState();
    	
    	$model->setState('params', $params);
    	$model->setState('list.start', 0);
    	$model->setState('list.limit', 0);
    	$model->setState('list.select', 'i.id');
    	$model->setState('filter.catalogue', true);

    	$model->setState('list.ordering', 'i.id');
    	$model->setState('list.direction', 'asc');
    	
    	$items_query = $model->buildQuery();
    	
    	$query = $db->getQuery(true);
    	$query->select('f.*');
    	$query->from('#__djc2_items_extra_fields as f');
    	$query->join('inner', '#__djc2_items as i on i.group_id = f.group_id');
    	$query->join('inner', '('.$items_query.') as iq on iq.id = i.id');
    	$query->where('(f.visibility = 2 or f.visibility = 3) and f.published = 1 and f.separate_column = 1');
    	$query->group('f.name, f.alias, f.id, f.group_id');
    	$query->order('f.group_id asc, f.ordering asc');
    	$db->setQuery($query);
    	
    	$groups = $db->loadObjectList();
    	
    	return $groups;
    }
    
    public function getSortables() {
    	$params = Djcatalog2Helper::getParams();
    	$db = JFactory::getDBO();
    	$model = JModelLegacy::getInstance('Items', 'Djcatalog2Model');
    	$state = $model->getState();
    	 
    	$model->setState('params', $params);
    	$model->setState('list.start', 0);
    	$model->setState('list.limit', 0);
    	$model->setState('list.select', 'i.id');
    	$model->setState('filter.catalogue', true);
    	
    	$model->setState('list.ordering', 'i.id');
    	$model->setState('list.direction', 'asc');
    
    	$items_query = $model->buildQuery();
    	 
    	$query = $db->getQuery(true);
    	$query->select('f.*');
    	$query->from('#__djc2_items_extra_fields as f');
    	$query->join('inner', '#__djc2_items as i on i.group_id = f.group_id');
    	$query->join('inner', '('.$items_query.') as iq on iq.id = i.id');
    	$query->where('(f.visibility = 2 or f.visibility = 3) and f.published = 1 and f.sortable = 1 and (f.type = \'text\' || f.type = \'radio\' || f.type = \'select\' || f.type = \'calendar\')');
    	$query->group('f.name, f.alias, f.id, f.group_id');
    	$query->order('f.group_id asc, f.ordering asc');
    	$db->setQuery($query);
    	 
    	$sortables = $db->loadObjectList();
    	 
    	return $sortables;
    }
	
}

