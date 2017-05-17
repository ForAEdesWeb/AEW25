<?php
/**
 * @version $Id: customfield.php 270 2014-04-10 06:40:48Z michal $
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

defined('_JEXEC') or die;

abstract class DJCatalog2CustomField extends JObject {

	public $type = null;
	public $base_type = null;

	public $params = null;

	public $id = 0;
	public $field_id = 0;
	public $name = '';
	public $value = null;
	public $item_id = 0;
	public $required = false;
	
	protected $table_name = null;

	public function __construct($field_id, $item_id, $name, $required = false, $value = null) {
		$this->field_id = $field_id;
		$this->item_id = $item_id;
		$this->name = $name;
		$this->value = $value;
		
		if (empty($this->type) || empty($this->base_type)) {
			throw new Exception('COM_DJCATALOG2_ERROR_INVALID_FIELD_TYPE');
			return false;
		}
		
		$this->table = JTable::getInstance('FieldValues'.ucfirst($this->base_type), 'Djcatalog2Table', array());
		$this->table_name = $this->table->getTableName();
	}

	public function save($value = false) {
		if(empty($value) && empty($this->value)) {
			return false;
		} else if (empty($value)) {
			$value = $this->value;
		}
		

		$db = JFactory::getDbo();
		$rows = array();

		if (is_array($value)) {
			$query =' SELECT id '
					.' FROM ' . $this->table_name
					.' WHERE item_id='.(int)$this->item_id.' AND field_id=' . $this->field_id
					;
			$db->setQuery($query);
			$current_values = $db->loadColumn();
			$count = (count($current_values) > count($value)) ? count($current_values) : count($value);

			for ($i = 0; $i < $count; $i++) {
				if (isset($value[$i])) {
					$id = null;
					if (isset($current_values[$i])) {
						$id = $current_values[$i];
					}
						
					$rows[] = array(
							'id'=>$id,
							'item_id'=>$this->item_id,
							'field_id'=>$this->field_id,
							'value' => $value[$i]
					);
				} else {
					$delete_query =  ' DELETE '
							.' FROM ' . $this->table_name
							.' WHERE id='.(int)$current_values[$i];
						
					$db->setQuery($delete_query);
					$db->query();
				}
			}
		} else {
			// html field
			//$data[$fieldId] = JComponentHelper::filterT($data[$fieldId]);
			//$data[$fieldId] = preg_replace('/&(?![A-Za-z0-9#]{1,7};)/','&amp;',$data[$fieldId]);
				
			$id = null;
			if ($this->table->load(array('item_id'=>$this->item_id,'field_id'=>$this->field_id), true)) {
				$id = $this->table->id;
			}
			$rows[] = array(
					'id'=>$id,
					'item_id'=>$this->item_id,
					'field_id'=>$this->field_id,
					'value' => $value
			);
		}

		foreach($rows as $row) {
			$this->table->reset();
			
			// Load the row if saving an existing record.
			$isNew = true;
			if ($row['id'] > 0) {
				$this->table->load($row['id'], true);
				$isNew = false;
			}
				
			// Bind the data.
			if (!$this->table->bind($row)) {
				$this->setError($this->table->getError());
				return false;
			}
			// Check the data.
			if (!$this->table->check()) {
				$this->setError($this->table->getError());
				return false;
			}

			// Store the data.
			if (!$this->table->store()) {
				$this->setError($this->table->getError());
				return false;
			}
		}
		
		return true;
	}

	public function delete() {
		$db = JFactory::getDbo();
		if ($this->item_id > 0 && $this->table_name){
			$db->setQuery('delete from '.$this->table_name.' where item_id='.(int)$this->item_id.' and field_id='.$this->field_id);
			if (!$db->query()) {
				$this->setError($db->getErrorMsg());
				return false;
			}
		} else {
			$this->setError(JText::_('COM_DJCATALOG2_ERROR_CUSTOM_FIELD_MISSING_ARGUMENTS'));
			return false;
		}
		return true;
	}

	public function getLabel() {
		return $this->name;
	}

	public function getValue() {
		if (is_array($this->value)) {
			return implode(',', $this->value);
		}
		else {
			return $this->value;
		}
	}
	public function setValue($value) {
		$this->value = $value;
	}

	public function getFormLabel($attribs = '') {
		return '<label for="attribute_'.$this->field_id.'" '.$attribs.'>'.$this->name.'</label>';
	}

	abstract public function getFormInput($attribs = ''); 
	/*{
		return '<input type="text" name="attribute['.$this->field_id.']" value="'.$this->getValue().' '.$attribs.'"/>';
	}*/

	public function getParamsInput() {
	}

}