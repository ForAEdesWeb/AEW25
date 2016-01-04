<?php
/**
 * @version $Id: helper.php 211 2013-11-25 09:02:55Z michal $
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

class DJC2FiltersModuleHelper {
	public static function getData($params) {
		$attributes = self::getAttributes($params);
		$counters = self::getCounters($attributes);
		
		$app = JFactory::getApplication();
		
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
		$globalSearch = urldecode($app->input->get( 'search','','string' ));
		$globalSearch = trim(JString::strtolower( $globalSearch ));
		if (substr($globalSearch,0,1) == '"' && substr($globalSearch, -1) == '"') {
			$globalSearch = substr($globalSearch,1,-1);
		}
		if (strlen($globalSearch) > 0 && (strlen($globalSearch)) < 3 || strlen($globalSearch) > 20) {
			$globalSearch = null;
		}
		
		$grouppedattributes = array();

		foreach ($attributes as $key=>$attribute) {
			$attributes[$key]->alias = str_replace('-', '_', $attribute->alias);
			$attributes[$key]->optionsArray = ($attribute->options) ? $attribute->options : array();
			$attributes[$key]->optionValuesArray = ($attribute->optionValues) ? $attribute->optionValues : array();
			$attributes[$key]->optionCounterArray = array();
			$attributes[$key]->selected = false;
			$attributes[$key]->selectedOptions = array();
			$attributes[$key]->selectedOptionValues = array();
			$attributes[$key]->availableOptions = 0;
			
			foreach ($attributes[$key]->optionsArray as $kk => $vv) {
                if (is_array($counters) && !empty($counters)) {
                    if (array_key_exists($vv, $counters)) {
                        $attributes[$key]->optionCounterArray[] = $counters[$vv]->item_count; 
                        $attributes[$key]->availableOptions++;
                    } else {
                        $attributes[$key]->optionCounterArray[] = 0;
                    }
                } else {
                    $attributes[$key]->optionCounterArray[] = 0;
                }
            }
			if (!empty($filters[$attribute->alias])) {
				$filter = $filters[$attribute->alias];
				if (is_scalar($filter) && strpos($filter, ',') !== false) {
					$filter = explode(',', $filter);
				}
				if (is_array($filter)) {
				    foreach($filter as $k=>$v) {
				        $filter[$k] = (int)$v;
				    }
					$attributes[$key]->selected = true;
					foreach ($attribute->optionsArray as $o) {
						if (in_array($o, $filter)){
							$index = array_search($o, $attributes[$key]->optionsArray);
							if (array_key_exists($index, $attributes[$key]->optionValuesArray)) {
								$attributes[$key]->selectedOptionValues[] = $attributes[$key]->optionValuesArray[$index];
								$attributes[$key]->selectedOptions[] = $attributes[$key]->optionsArray[$index];
							}
						}
					}
				} else {
					$attributes[$key]->selected = true;
					foreach ($attribute->optionsArray as $o) {
						if ($o == (int)$filter) {
							$index = array_search($o, $attributes[$key]->optionsArray);
							if (array_key_exists($index, $attributes[$key]->optionValuesArray)) {
								$attributes[$key]->selectedOptionValues[] = $attributes[$key]->optionValuesArray[$index];
								$attributes[$key]->selectedOptions[] = $attributes[$key]->optionsArray[$index];
							}
						}
					}
				}
			}
			if (empty($grouppedattributes[$attribute->group_id])) {
				$grouppedattributes[$attribute->group_id] = new stdClass();
				$grouppedattributes[$attribute->group_id]->group_name = $attribute->group_name;
				$grouppedattributes[$attribute->group_id]->attributes = array();
			}
			$grouppedattributes[$attribute->group_id]->attributes[] = $attribute;
		}
		return $grouppedattributes;
	}
	private static function getAttributes(&$params) {
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('f.*, g.name as group_name');
		$query->from('#__djc2_items_extra_fields as f');
		$query->join('left', '#__djc2_items_extra_fields_groups as g ON g.id=f.group_id');
	
		$fieldgroups = $params->get('fieldgroups');
		$group_ids = '';
		if (!empty($fieldgroups)) {
			if (in_array(0, $fieldgroups)) {
				$group_ids = ' and (g.id in ('.implode(',',$fieldgroups).') OR g.id IS NULL)';
			} else {
				$group_ids = ' and (g.id in ('.implode(',',$fieldgroups).'))';
			}
		}
		
		$query->where('f.published = 1 and f.filterable = 1 and (f.type = \'checkbox\' or f.type = \'radio\' or f.type = \'select\') '.$group_ids);
		$query->group('f.id');
		$query->order('f.group_id asc, f.ordering asc');
		$db->setQuery($query);
		
		$attributes = $db->loadObjectList('id');
		
		if (count($attributes) > 0) {
			$query = $db->getQuery(true);
			$query->select('*');
			$query->from('#__djc2_items_extra_fields_options');
			$query->where('field_id in ('.implode(',',array_keys($attributes)).')');
			$query->order('field_id asc, ordering asc');
			
			$db->setQuery($query);
			$optionslist = $db->loadObjectList();
			
			foreach ($attributes as $field_id => $field) {
				$field_options = array();
				$field_optionValues = array();
				foreach($optionslist as $k => $option) {
					if ($option->field_id == $field_id) {
						$field_options[] = $option->id;
						$field_optionValues[] = $option->value;
					}
				}
				$attributes[$field_id]->options = $field_options;//implode('|', $field_options);
				$attributes[$field_id]->optionValues = $field_optionValues;//implode('|', $field_optionValues);
			}
		}
			
		return $attributes;
		
	}
	
	private static function getCounters($attributes) {
		$db = JFactory::getDbo();
		$app = JFactory::getApplication();
		$cparams = Djcatalog2Helper::getParams();
		$params = new JRegistry();
		$params->merge($cparams);
		
		JModelLegacy::addIncludePath(JPATH_BASE.DS.'components'.DS.'com_djcatalog2'.DS.'models');
		$model = JModelLegacy::getInstance('Items', 'Djcatalog2Model');
		$state = $model->getState();
		
		$params->set('product_catalogue', 0);
		$params->set('limit_items_show', 0);
		$model->setState('params', $params);
		$model->setState('filter.catalogue', false);
		$model->setState('list.start', 0);
		$model->setState('list.limit', 0);
		
		$model->setState('list.select', 'i.id');
		$model->setState('list.ordering', 'i.id');
		
		/*
		$items = $model->getItems();
		if (empty($items)){
			return false;
		}
		$itemIds = array_keys($items);
		*/
		
		$items_query = $model->buildQuery();
		
		//echo str_replace('#_','jos',$items_query).'<br/>';die();
		
		$query = $db->getQuery(true);
        $query->select('concat(cast(ef.id as char), "_", cast(opt.id as char)) as row_index, ef.id as field_id, ef.name as field_name, opt.id as option_id, opt.value as option_value, count(fv.item_id) as item_count');
        $query->from('#__djc2_items_extra_fields_values_int as fv');
        $query->join('inner', '#__djc2_items_extra_fields as ef on ef.id=fv.field_id');
        $query->join('inner', '#__djc2_items_extra_fields_options as opt on opt.field_id=ef.id and opt.id=fv.value');
        //$query->where('fv.item_id in ('.implode(',', $itemIds).')');
        $query->join('inner', '('.$items_query.') as item_ids on fv.item_id = item_ids.id');
        $query->group('ef.id, opt.value');
        $query->order('ef.name asc');
        
        $db->setQuery($query);
        //echo str_replace('#_','jos',$query).'<br/>';die();
        return $db->loadObjectList('option_id');
        
	}
}