<?php
/**
 * @version $Id: item.php 311 2014-07-16 08:00:52Z michal $
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

jimport('joomla.application.component.modelform');

class DJCatalog2ModelItem extends JModelForm {

	protected $view_item = 'item';
	protected $_item = null;
	protected $_context = 'com_djcatalog2.item';
	protected $_related = array();
	protected $_attributes = null;

	protected function populateState()
	{
		$app = JFactory::getApplication('site');

		// Load state from the request.
		$pk = $app->input->getInt('id');
		$this->setState('item.id', $pk);

		// Load the parameters.
		$params = $app->getParams();
		$this->setState('params', $params);

	}

	public function getForm($data = array(), $loadData = true)
	{
		// Get the form.
		$form = $this->loadForm('com_djcatalog2.contact', 'contact', array('control' => 'jform', 'load_data' => true));
		if (empty($form)) {
			return false;
		}
		
		$params = JComponentHelper::getParams('com_djcatalog2');
		
		$user = JFactory::getUser();
		if ($user->id > 0) {
			if ($form->getValue('contact_email') == '') {
				$form->setFieldAttribute('contact_email', 'default', $user->email);
			}
			if ($form->getValue('contact_name') == '') {
				$form->setFieldAttribute('contact_name', 'default', $user->name);
			}
		}
		
		$subject = @$this->getItem()->name;
		if ($subject && $form->getValue('contact_subject') == '') {
			$form->setFieldAttribute('contact_subject', 'default', $subject);
		}
		
		return $form;
	}
	
	protected function preprocessForm(JForm $form, $data, $group = 'content') {
		$app = JFactory::getApplication();
		$params = JComponentHelper::getParams('com_djcatalog2');
		
		$user = JFactory::getUser();
		
		$switchable_fields = array('contact_phone', 'contact_street', 'contact_city', 'contact_zip', 'contact_country', 'contact_company_name', 'contact_email_copy');
		foreach($switchable_fields as $field_name) {
			if ($params->get($field_name.'_field', '0') == '0'){
				$form->removeField($field_name);
			} else if ($params->get($field_name.'_field', '0') == '2') {
				$form->setFieldAttribute($field_name, 'required', 'required');
				$form->setFieldAttribute($field_name, 'class', $form->getFieldAttribute($field_name, 'class').' required');
			}
		}
		
		$plugin = JFactory::getApplication()->getParams()->get('contact_captcha', JFactory::getConfig()->get('captcha'));
		
		if ($user->id > 0 || ($plugin === 0 || $plugin === '0' || $plugin === '' || $plugin === null)) {
			$form->removeField('captcha');
		} else {
			$form->setFieldAttribute('captcha', 'plugin', $plugin, 'captcha');
		}
	}

	protected function loadFormData()
	{
		$data = (array)JFactory::getApplication()->getUserState('com_djcatalog2.contact.data', array());
		return $data;
	}

	public function &getItem($pk = null)
	{
		// Initialise variables.
		$pk = (!empty($pk)) ? $pk : (int) $this->getState('item.id');

		if ($this->_item === null) {
			$this->_item = array();
		}

		if (!isset($this->_item[$pk])) {
			try
			{
				$db = JFactory::getDbo();
				$query = $db -> getQuery(true);

				$attributes = $this -> getAttributes();

				$query -> select('i.*, CASE WHEN (i.special_price > 0.0 AND i.special_price < i.price) THEN i.special_price ELSE i.price END as final_price');
				$query -> from('#__djc2_items as i');

				$query -> select('c.id as _category_id, c.name as category, c.published as publish_category, c.alias as category_alias');
				$query -> join('left', '#__djc2_categories AS c ON c.id = i.cat_id');

				$query -> select('p.id as _producer_id, p.name as producer, p.published as publish_producer, p.alias as producer_alias');
				$query -> join('left', '#__djc2_producers AS p ON p.id = i.producer_id');
				
				$query -> select('ua.name AS author, ua.email AS author_email');
				$query -> join('left', '#__users AS ua ON ua.id = i.created_by');
				
				$nullDate = $db->quote($db->getNullDate());
				$date = JFactory::getDate();
				$nowDate = $db->quote($date->toSql());

				$query->where('i.id ='.(int)$pk);
				$query->where('(i.publish_up = ' . $nullDate . ' OR i.publish_up <= ' . $nowDate . ')');
				$query->where('(i.publish_down = ' . $nullDate . ' OR i.publish_down >= ' . $nowDate . ')');
				
				
				$query -> group('i.id');
				//echo str_replace('#_','jos',$query).'<br/>';die();
				$db -> setQuery($query);
				$item = $db -> loadObject();

				if (!empty($item)) {
					$item->slug = (empty($item->alias)) ? $item->id : $item->id.':'.$item->alias;
					$item->catslug = (empty($item->category_alias)) ? $item->cat_id : $item->cat_id.':'.$item->category_alias;
					$item->prodslug = (empty($item->producer_alias)) ? $item->producer_id : $item->producer_id.':'.$item->producer_alias;
				}
				$this->_item[$pk] = $item;
					
			}
			catch (JException $e)
			{
				$this->setError($e);
				$this->_item[$pk] = false;
			}

		}
		if ($this->_item[$pk])
		{
			$this->bindAttributes($pk);
		}
		return $this->_item[$pk];

	}

	function getRelatedItems($pk = null) {
		$pk = (!empty($pk)) ? $pk : (int) $this->getState('item.id');
		
		if (empty($this->_related[$pk])) {
			$query = ' SELECT i.*, CASE WHEN (i.special_price > 0.0 AND i.special_price < i.price) THEN i.special_price ELSE i.price END as final_price, c.id AS ccategory_id, c.alias as category_alias, p.id AS pproducer_id, p.alias as producer_alias, c.name AS category, p.name AS producer, p.published as publish_producer '
			. ' FROM #__djc2_items AS i '
			. ' LEFT JOIN #__djc2_categories AS c ON c.id = i.cat_id '
			. ' LEFT JOIN #__djc2_producers AS p ON p.id = i.producer_id '
			. ' WHERE i.published = 1 AND i.id IN (SELECT related_item FROM #__djc2_items_related WHERE item_id='.(int)$pk.')'
			. ' ORDER BY i.ordering ASC ';
			$this->_db->setQuery($query);
			$related_items = $this->_db->loadObjectList('id');
			
			$this->_related[$pk] = array();
			
			if (!empty($related_items)) {
				$ids = array_keys($related_items);
				
				$query = $this->_db->getQuery(true);
				$query->select('i.id, img.fullname as item_image, img.caption AS image_caption, img.path as image_path, img.fullpath as image_fullpath');
				$query->from('#__djc2_items as i');
				$query->join('inner', '(select im1.fullname, im1.caption, im1.type, im1.item_id, im1.path, im1.fullpath from #__djc2_images as im1, (select item_id, type, min(ordering) as lowest_order from #__djc2_images group by item_id, type) as im2 where im1.item_id = im2.item_id and im1.type=im2.type and im1.ordering = im2.lowest_order group by im1.type, im1.item_id, im1.path, im1.fullpath) AS img ON img.item_id = i.id AND img.type=\'item\'');
				$query->where('i.id IN ('.implode(',', $ids).')');
				$this->_db->setQuery($query);
				$image_list = $this->_db->loadObjectList('id');
				
				foreach($related_items as $key=>$row) {
					$related_items[$key]->slug = (empty($row->alias)) ? $row->id : $row->id.':'.$row->alias;
					$related_items[$key]->catslug = (empty($row->category_alias)) ? $row->cat_id : $row->cat_id.':'.$row->category_alias;
					$related_items[$key]->prodslug = (empty($row->producer_alias)) ? $row->producer_id : $row->producer_id.':'.$row->producer_alias;
					
					$related_items[$key]->item_image = isset($image_list[$row->id]) ? $image_list[$row->id]->item_image : null;
					$related_items[$key]->image_caption = isset($image_list[$row->id]) ? $image_list[$row->id]->image_caption : null;
					
					$related_items[$key]->image_path = isset($image_list[$row->id]) ? $image_list[$row->id]->image_path : null;
					$related_items[$key]->image_fullpath = isset($image_list[$row->id]) ? $image_list[$row->id]->image_fullpath : null;
					
					$this->_related[$pk][] = $related_items[$key];
				}
			}
		}
		return $this->_related[$pk];
	}
	function getAttributes() {
		if (empty($this->_attributes)) {
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query->select('f.*, group_concat(fo.id separator \'|\') as options');
			$query->from('#__djc2_items_extra_fields as f');
			$query->join('LEFT', '#__djc2_items_extra_fields_options as fo ON fo.field_id=f.id');
				
			$query->where('(f.visibility = 1 or f.visibility = 3) and f.published = 1');
			$query->group('f.id');
			$query->order('f.group_id asc, f.ordering asc');
			$db->setQuery($query);
			$this->_attributes = $db->loadObjectList();
		}

		return $this->_attributes;
	}
	function bindAttributes($id) {
		if (!empty($this->_item[$id])) {
			$db = JFactory::getDbo();
			
			$query_int = $db->getQuery(true);
			$query_text = $db->getQuery(true);
			$query_date = $db->getQuery(true);
			
			$query_int->select('fields.alias, fields.type, fields.ordering, fieldvalues.item_id, fieldvalues.field_id, fieldvalues.id as value_id, fieldoptions.id as option_id, fieldoptions.value');
			$query_int->from('#__djc2_items_extra_fields_values_int as fieldvalues');
			$query_int->join('inner', '#__djc2_items as items on items.id=fieldvalues.item_id' );
			$query_int->join('inner','#__djc2_items_extra_fields as fields ON fields.id = fieldvalues.field_id');
			$query_int->join('left','#__djc2_items_extra_fields_options as fieldoptions ON fieldoptions.id = fieldvalues.value AND fieldoptions.field_id = fields.id');
			$query_int->where('fieldvalues.item_id='.$id.' AND (fields.visibility = 1 OR fields.visibility = 3) AND fields.published = 1');
			$query_int->order('fields.ordering asc, fieldoptions.ordering asc');
			
			$query_text->select('fields.alias, fields.type, fields.ordering, fieldvalues.item_id, fieldvalues.field_id, fieldvalues.id as value_id, 0 as option_id, fieldvalues.value');
			$query_text->from('#__djc2_items_extra_fields_values_text as fieldvalues');
			$query_text->join('inner', '#__djc2_items as items on items.id=fieldvalues.item_id' );
			$query_text->join('inner','#__djc2_items_extra_fields as fields ON fields.id = fieldvalues.field_id');
			$query_text->where('fieldvalues.item_id='.$id.' AND (fields.visibility = 1 OR fields.visibility = 3) AND fields.published = 1');
			
			$query_date->select('fields.alias, fields.type, fields.ordering, fieldvalues.item_id, fieldvalues.field_id, fieldvalues.id as value_id, 0 as option_id, fieldvalues.value');
			$query_date->from('#__djc2_items_extra_fields_values_date as fieldvalues');
			$query_date->join('inner', '#__djc2_items as items on items.id=fieldvalues.item_id' );
			$query_date->join('inner','#__djc2_items_extra_fields as fields ON fields.id = fieldvalues.field_id');
			$query_date->where('fieldvalues.item_id='.$id.' AND (fields.visibility = 1 OR fields.visibility = 3) AND fields.published = 1');
			$query_date->order('fields.ordering asc');
			
			$db->setQuery($query_int);
			$int_attributes = $db->loadObjectList();
			$db->setQuery($query_text);
			$text_attributes = $db->loadObjectList();
			$db->setQuery($query_date);
			$date_attributes = $db->loadObjectList();
			
			
			foreach ($text_attributes as $attribute) {
				if ($attribute->item_id == $id) {
					$field = '_ef_'.$attribute->alias;
					$this->_item[$id]->$field = $attribute->value;
				}
			}
			foreach ($date_attributes as $attribute) {
				if ($attribute->item_id == $id) {
					$field = '_ef_'.$attribute->alias;
					$this->_item[$id]->$field = $attribute->value;
				}
			}
			foreach ($int_attributes as $attribute) {
				if ($attribute->item_id == $id) {
					$field = '_ef_'.$attribute->alias;
					if (!isset($this->_item[$id]->$field) || !is_array($this->_item[$id]->$field)) {
						$this->_item[$id]->$field = array();
					}
					if (!in_array($attribute->value, $this->_item[$id]->$field)) {
						$tmp_arr = $this->_item[$id]->$field;
						$tmp_arr[] = $attribute->value;
						$this->_item[$id]->$field = $tmp_arr;
					}
				}
			}
		}
	}
    public function getNavigation($id, $catid = null, $params = null) {
        $db = JFactory::getDbo();
        $category_limit = ($catid) ? ' AND i.cat_id='.$catid : '';
        
        $orderby = 'c.ordering ASC, i.ordering ASC';
        
        if (!empty($params)) {
            $filter_order       = $params->get('items_default_order','i.ordering');
            $filter_order_Dir   = $params->get('items_default_order_dir','asc');
            $filter_featured    = $params->get('featured_first', 0);
            
            $sortables = array('i.ordering', 'i.name', 'i.created', 'i.price', 'category', 'c.name', 'producer', 'p.name', 'i.id', 'rand()');
            
            if (!in_array($filter_order, $sortables)) {
                $filter_order = 'i.ordering';
            }
            
            if ($filter_order_Dir != 'asc' && $filter_order_Dir != 'desc') {
                $filter_order_Dir = 'asc';
            }
            
            if ($filter_order == 'i.ordering'){
                if ($filter_featured) {
                    //$orderby  = ' i.featured DESC, i.ordering '.$filter_order_Dir.', c.ordering '.$filter_order_Dir;
                    $orderby = 'i.featured DESC, c.parent_id asc, c.ordering asc, i.ordering '.$filter_order_Dir;
                } else {
                    //$orderby  = ' i.ordering '.$filter_order_Dir.', c.ordering '.$filter_order_Dir;
                    $orderby = 'c.parent_id asc, c.ordering asc, i.ordering '.$filter_order_Dir;
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
                    $orderby    = ' i.featured DESC, '.$filter_order.' '.$filter_order_Dir.' , i.ordering, c.ordering ';
                }
                else {
                    $orderby    = ' '.$filter_order.' '.$filter_order_Dir.' , i.ordering, c.ordering ';
                }
            }
        }
        
        $nullDate = $db->quote($db->getNullDate());
        $date = JFactory::getDate();
        $nowDate = $db->quote($date->toSql());
        
        $query = 'SELECT DISTINCT i.id, i.name, i.alias, i.cat_id, c.alias as category_alias, @num := @num + 1 AS position, '
                .' CASE WHEN (i.special_price > 0.0 AND i.special_price < i.price) THEN i.special_price ELSE i.price END as final_price '
                .' FROM (SELECT @num := 0) AS n, #__djc2_items AS i '
                .' LEFT JOIN #__djc2_categories as c ON c.id = i.cat_id '
                .' LEFT JOIN #__djc2_producers as p ON p.id = i.producer_id '
                .' WHERE i.published = 1 AND (i.publish_up = ' . $nullDate . ' OR i.publish_up <= ' . $nowDate . ') AND (i.publish_down = ' . $nullDate . ' OR i.publish_down >= ' . $nowDate . ') AND c.published = 1 '.$category_limit
                .' ORDER BY '. $orderby;

        $navigation = array('prev'=>null, 'next'=>null);
        
        $db->setQuery('SELECT subq.position FROM ('.$query.') as subq WHERE subq.id = '.$id.' ORDER BY subq.position DESC LIMIT 1');
        $position = $db->loadResult();
        
        //$pos_query = 'SELECT subq.* FROM ('.$query.') as subq WHERE subq.position='.($position - 1).' OR subq.position='.($position + 1).' ORDER BY subq.position ASC';               
        //$db->setQuery($pos_query);
        
        $prev_query = 'SELECT subq.* FROM ('.$query.') as subq WHERE subq.position < '.$position.' ORDER BY subq.position DESC LIMIT 1';
        $next_query = 'SELECT subq.* FROM ('.$query.') as subq WHERE subq.position > '.$position.' ORDER BY subq.position ASC LIMIT 1';
        
        $db->setQuery($prev_query);
        $prev = $db->loadObject();
        if (!empty($prev) && $prev->id > 0 && $prev->cat_id > 0) {
            $navigation['prev'] = $prev;
            $navigation['prev']->slug = $prev->id.':'.$prev->alias;
            $navigation['prev']->catslug = $prev->cat_id.':'.$prev->category_alias;
        }
        
        $db->setQuery($next_query);
        $next = $db->loadObject();
        if (!empty($next) && $next->id > 0 && $next->cat_id > 0) {
            $navigation['next'] = $next;
            $navigation['next']->slug = $next->id.':'.$next->alias;
            $navigation['next']->catslug = $next->cat_id.':'.$next->category_alias;
        }
        
        //$nav_rows = $db->loadObjectList();
        //echo str_replace('#__', 'j25_', $pos_query);
        
        /*if (count($nav_rows) > 0) {
            foreach($nav_rows as $row) {
                if ($row->position > $position) {
                    $navigation['next'] = $row;
                    $navigation['next']->slug = $row->id.':'.$row->alias;
                    $navigation['next']->catslug = $row->cat_id.':'.$row->category_alias;
                } else if ($row->position < $position) {
                    $navigation['prev'] = $row;
                    $navigation['prev']->slug = $row->id.':'.$row->alias;
                    $navigation['prev']->catslug = $row->cat_id.':'.$row->category_alias;
                }
            } 
        }*/
        
        return $navigation;
    }
	
	public function hit($pk = 0)
	{
		$hitcount = JFactory::getApplication()->input->getInt('hitcount', 1);
	
		if ($hitcount)
		{
			// Initialise variables.
			$pk = (!empty($pk)) ? $pk : (int) $this->getState('item.id');
			$db = $this->getDbo();
	
			$db->setQuery(
					'UPDATE #__djc2_items' .
					' SET hits = hits + 1' .
					' WHERE id = '.(int) $pk
			);
	
			if (!$db->query()) {
				$this->setError($db->getErrorMsg());
				return false;
			}
		}
	
		return true;
	}
}