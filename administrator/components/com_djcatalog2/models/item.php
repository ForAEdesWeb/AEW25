<?php
/**
 * @version $Id: item.php 312 2014-07-16 08:10:07Z michal $
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

// No direct access.
defined('_JEXEC') or die;

//jimport('joomla.application.component.modeladmin');
require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'lib'.DS.'modeladmin.php');
jimport('joomla.application.component.helper');

class Djcatalog2ModelItem extends DJCJModelAdmin
{
	protected $text_prefix = 'COM_DJCATALOG2';

	public function __construct($config = array()) {
		//$config['event_after_save'] = 'onItemAfterSave';
		//$config['event_after_delete'] = 'onItemAfterDelete';
		parent::__construct($config);
	}

	public function getTable($type = 'Items', $prefix = 'Djcatalog2Table', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}
	public function getForm($data = array(), $loadData = true)
	{
		// Initialise variables.
		$app	= JFactory::getApplication();

		// Get the form.
		$form = $this->loadForm('com_djcatalog2.item', 'item', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form)) {
			return false;
		}

		return $form;
	}

	public function getItem($pk = null) {
		if ($item = parent::getItem($pk)) {
			if ((!isset($item->categories) || !is_array($item->categories)) && isset($item->id)){
				$this->_db->setQuery('SELECT category_id FROM #__djc2_items_categories WHERE item_id=\''.$item->id.'\'');
				$item->categories = $this->_db->loadColumn();
			}
			return $item;
		} else {
			return false;
		}
	}

	protected function loadFormData()
	{
		$data = JFactory::getApplication()->getUserState('com_djcatalog2.edit.item.data', array());

		if (empty($data)) {
			$data = $this->getItem();
		}

		return $data;
	}

	protected function _prepareTable(&$table)
	{
		jimport('joomla.filter.output');
		$date = JFactory::getDate();
		$user = JFactory::getUser();

		$table->name		= htmlspecialchars_decode($table->name, ENT_QUOTES);
		$table->alias		= JApplication::stringURLSafe($table->alias);

		if (empty($table->alias)) {
			$table->alias = JApplication::stringURLSafe($table->name);
		}

		if (empty($table->id)) {
			if (empty($table->ordering)) {
				$db = JFactory::getDbo();
				$db->setQuery('SELECT MAX(ordering) FROM #__djc2_items WHERE cat_id = '.$table->cat_id);
				$max = $db->loadResult();

				$table->ordering = $max+1;
			}
		}
	}

	protected function getReorderConditions($table)
	{
		$condition = array();
		$condition[] = 'cat_id = '.(int) $table->cat_id;
		return $condition;
	}
	
	public function validateAttributes($data, &$table) {
		$db = JFactory::getDbo();
		$db->setQuery('select * from #__djc2_items_extra_fields where required=1 AND (group_id=0 OR group_id='.(int)$table->group_id.')');
		$required_fields = $db->loadObjectList();
		
		if (count($required_fields) == 0) {
			return true;
		}
		
		$all_valid = true;
		
		foreach($required_fields as $field) {
			$field_id = $field->id;
			$valid = false;
			if (isset($data[$field_id])) {
				if (is_array($data[$field_id])) {
					foreach($data[$field_id] as $option) {
						if (!empty($option)) {
							$valid = true;
							break;
						}
					}
				} else {
					if (!empty($data[$field_id])) {
						$valid = true;
					}
				}
			}
			if (!$valid) {
				$all_valid = false;
				$message = JText::_($field->name);
				$message = JText::sprintf('JLIB_FORM_VALIDATE_FIELD_INVALID', $message);
				$this->setError($message);
			}
		}
		
		return $all_valid;
		
	}
	
	public function getFields() {
		$item = $this->getItem();
		
		$itemId = $item->id;
		
		$db = JFactory::getDbo();
		
		$query = $db->getQuery(true);
		$query->select('f.*, g.name as group_name');
		$query->from('#__djc2_items_extra_fields AS f');
		$query->select('CASE '
				.'WHEN (f.type=\'text\' OR f.type=\'textarea\' OR f.type=\'html\') '
				.'THEN vt.value '
				.'WHEN (f.type=\'calendar\') '
				.'THEN vd.value '
				.'WHEN (f.type=\'checkbox\' OR f.type=\'select\' OR f.type=\'radio\') '
				.'THEN GROUP_CONCAT(vi.value SEPARATOR \'|\')'
				.'ELSE "" END AS field_value');
		$query->join('LEFT','#__djc2_items_extra_fields_values_text AS vt ON f.id=vt.field_id AND vt.item_id='.(int)$itemId);
		$query->join('LEFT','#__djc2_items_extra_fields_values_int AS vi ON f.id=vi.field_id AND vi.item_id='.(int)$itemId);
		$query->join('LEFT','#__djc2_items_extra_fields_values_date AS vd ON f.id=vd.field_id AND vd.item_id='.(int)$itemId);
		$query->join('LEFT', '#__djc2_items_extra_fields_groups as g ON g.id = f.group_id');
		
		//$query->where('f.group_id='.(int)$this->groupId.' OR f.group_id=0');
		$query->group('f.id');
		$query->order('f.group_id asc, f.ordering asc');
		//echo str_replace('#_', 'jos', (string)$query);die();
		$db->setQuery($query);
		
		$fields = ($db->loadObjectList('id'));

		$groupped_fields = array();
		
		if (count($fields)) {
			$fieldIds = array_keys($fields);
			$db->setQuery('SELECT * FROM #__djc2_items_extra_fields_options WHERE field_id IN ('.implode(',', $fieldIds).') ORDER BY field_id ASC, ordering ASC');
			$optionList = $db->loadObjectList();
		
			foreach($fields as $field_id => $field) {
				foreach ($optionList as $optionRow) {
					if ($optionRow->field_id == $field_id) {
						if (empty($field->optionlist)) {
							$fields[$field_id]->optionlist = array();
						}
						$fields[$field_id]->optionlist[] = $optionRow;
					}
				}
				
				if (array_key_exists($field->group_id, $groupped_fields) == false) {
					$groupped_fields[$field->group_id] 			= new stdClass();
					$groupped_fields[$field->group_id]->id 		= $field->group_id;
					$groupped_fields[$field->group_id]->name 	= ($field->group_id) > 0 ? $field->group_name : JText::_('COM_DJCATALOG2_FIELD_GROUP_COMMON');
					$groupped_fields[$field->group_id]->fields 	= array();
				}
				$groupped_fields[$field->group_id]->fields[$field_id] = $fields[$field_id];
			}
		}
		
		return $groupped_fields;
	}

	public function saveAttributes($data, &$table) {
		$db = JFactory::getDbo();
		if (!empty($data) ) {
			$query = $db->getQuery(true);
			$query->delete();
			$query->from('#__djc2_items_extra_fields_values_text');
			$query->where('item_id ='.$table->id.' and field_id not in (select id from #__djc2_items_extra_fields where group_id = '.$table->group_id.' or group_id = 0)');
			$db->setQuery($query);
			$db->query();
			
			$query = $db->getQuery(true);
			$query->delete();
			$query->from('#__djc2_items_extra_fields_values_int');
			$query->where('item_id ='.$table->id.' and field_id not in (select id from #__djc2_items_extra_fields where group_id = '.$table->group_id.' or group_id = 0)');
			$db->setQuery($query);
			$db->query();
			
			$query = $db->getQuery(true);
			$query->delete();
			$query->from('#__djc2_items_extra_fields_values_date');
			$query->where('item_id ='.$table->id.' and field_id not in (select id from #__djc2_items_extra_fields where group_id = '.$table->group_id.' or group_id = 0)');
			$db->setQuery($query);
			$db->query();
			
			$query = $db->getQuery(true);
			$query->select('ef.*');
			$query->from('#__djc2_items_extra_fields as ef');
			$query->where('ef.group_id='.$table->group_id.' OR ef.group_id=0');
			$db->setQuery($query);

			$attribs = $db->loadObjectList();
			$itemId = $table->id;
			$rows = array();

			$text_types = array('text','textarea','html');
			$int_types = array('select','checkbox','radio');
			$date_types = array('calendar');
			/*
			foreach ($attribs as $k=>$v) {
				$fieldId = $v->id;
				$className =  DJCatalog2CustomField.ucfirst($v->type);
				if (class_exists($className) == false ){
					continue;
				}
				
				$field = new $className($fieldId, $itemId, $v->name, $v->required);
				
				if (array_key_exists($fieldId, $data) && !empty($data[$fieldId])) {
					$field->setValue($data[$fieldId]);
					$field->save();	
				} else {
					$field->delete();
				}
			}
			
			return true;*/
			
			foreach ($attribs as $k=>$v) {
				$fv_table = null;
				$type_table_name = null;
				$table_type = null;
				if (in_array($v->type, $text_types)) {
					$fv_table = JTable::getInstance('FieldValuesText', 'Djcatalog2Table', array());
					$type_table_name = '#__djc2_items_extra_fields_values_text';
					$table_type = 'text';
				} else if (in_array($v->type, $int_types)) {
					$fv_table = JTable::getInstance('FieldValuesInt', 'Djcatalog2Table', array());
					$type_table_name = '#__djc2_items_extra_fields_values_int';
					$table_type = 'int';
				} else if (in_array($v->type, $date_types)) {
					$fv_table = JTable::getInstance('FieldValuesDate', 'Djcatalog2Table', array());
					$type_table_name = '#__djc2_items_extra_fields_values_date';
					$table_type = 'date';
				} else {
					continue;
				}
				$fieldId = $v->id;
				if (array_key_exists($fieldId, $data) && !empty($data[$fieldId])) {
					// add/alter data
					$value = null;
					$id = null;
						
					if (is_array($data[$fieldId])) {
						$db->setQuery('
									SELECT id 
									FROM '.$type_table_name.' 
									WHERE 
										item_id='.(int)$itemId.' 
										AND field_id='.$fieldId. ' order by id '
						);
						$values = $db->loadColumn();
						$count = (count($values) > count($data[$fieldId])) ? count($values) : count($data[$fieldId]);
						for ($i = 0; $i < $count; $i++) {
							if (isset($data[$fieldId][$i])) {
								$id = null;
								if (isset($values[$i])) {
									$id = $values[$i];
								}
								
								$rows[] = array(
											'id'=>$id, 
											'item_id'=>$itemId, 
											'field_id'=>$fieldId, 
											'value' => $data[$fieldId][$i],
											'type' => $table_type
								);
							} else {
								$db->setQuery('
								DELETE 
								FROM '.$type_table_name.' 
								WHERE id='.(int)$values[$i] 
								);
								$db->query();
							}
						}

					} else {
						if ($v->type == 'html') {
							$data[$fieldId] = JComponentHelper::filterText($data[$fieldId]);
							$data[$fieldId] = preg_replace('/&(?![A-Za-z0-9#]{1,7};)/','&amp;',$data[$fieldId]);
						}
						if ($fv_table->load(array('item_id'=>$itemId,'field_id'=>$fieldId))) {
							$id = $fv_table->id;
						}
						$rows[] = array(
										'id'=>$id, 
										'item_id'=>$itemId, 
										'field_id'=>$fieldId, 
										'value' => $data[$fieldId],
										'type' => $table_type
						);
					}

				} else {
					// remove data
					$db->setQuery('
								DELETE 
								FROM '.$type_table_name.' 
								WHERE 
									field_id='.(int)$fieldId.' 
									AND item_id='.(int)$itemId
					);
					$db->query();
				}
			}

			foreach ($rows as $key=>$row) {
				$fv_table = null;
				if (isset($row['type'])) {
					if ($row['type'] == 'text' || $row['type'] == 'int' || $row['type'] == 'date') {
						$fv_table = JTable::getInstance('FieldValues'.ucfirst($row['type']), 'Djcatalog2Table', array());
						unset($row['type']);
					} else{
						continue;
					}
				} else {
					continue;
				}
				
				$isNew = true;
				// Load the row if saving an existing record.
				if ($row['id'] > 0) {
					$fv_table->load($row['id'], true);
					$isNew = false;
				}

				// Bind the data.
				if (!$fv_table->bind($row)) {
					$this->setError($fv_table->getError());
					return false;
				}
				// Check the data.
				if (!$fv_table->check()) {
					$this->setError($fv_table->getError());
					return false;
				}

				// Store the data.
				if (!$fv_table->store()) {
					$this->setError($fv_table->getError());
					return false;
				}

			}
		}
		return true;
	}
	public function changeFeaturedState($pks, $value) {
		if (empty($pks)) {
			return false;
		}
		$ids = implode(',',$pks);
		$db = JFactory::getDbo();
		$db->setQuery('update #__djc2_items set featured='.(int)$value.' where id in ('.$ids.')');
		if (!$db->query()) {
			$this->setError($db->getErrorMsg());
			return false;
		}
		return true;
	}
	
	public function changeAvailableState($pks, $value) {
		if (empty($pks)) {
			return false;
		}
		$ids = implode(',',$pks);
		$db = JFactory::getDbo();
		$db->setQuery('update #__djc2_items set available='.(int)$value.' where id in ('.$ids.')');
		if (!$db->query()) {
			$this->setError($db->getErrorMsg());
			return false;
		}
		return true;
	}

}