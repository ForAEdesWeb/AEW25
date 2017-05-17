<?php
/**
 * @version $Id: field.php 272 2014-05-21 10:25:49Z michal $
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

class Djcatalog2ModelField extends DJCJModelAdmin
{
	protected $text_prefix = 'COM_DJCATALOG2';

	public function __construct($config = array()) {
		//$config['event_after_save'] = 'onFieldAfterSave';
		//$config['event_after_delete'] = 'onFieldAfterDelete';
		parent::__construct($config);
	}

	public function getTable($type = 'Fields', $prefix = 'Djcatalog2Table', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}
	public function getForm($data = array(), $loadData = true)
	{
		// Initialise variables.
		$app	= JFactory::getApplication();

		// Get the form.
		$form = $this->loadForm('com_djcatalog2.field', 'field', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form)) {
			return false;
		}

		return $form;
	}

	protected function loadFormData()
	{
		$data = JFactory::getApplication()->getUserState('com_djcatalog2.edit.field.data', array());

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
		
		if (empty($table->alias)) {
			$table->alias = JFilterOutput::stringURLSafe($table->name);
			$table->alias = trim(str_replace('-','_',$table->alias));
			if(trim(str_replace('_','',$table->alias)) == '') {
				$table->alias = JFactory::getDate()->format('Y_m_d_H_i_s');
			}
		}

		if (empty($table->id)) {
			if (empty($table->ordering)) {
				$db = JFactory::getDbo();
				$db->setQuery('SELECT MAX(ordering) FROM #__djc2_items_extra_fields');
				$max = $db->loadResult();

				$table->ordering = $max+1;
			}
		}
	}

	protected function getReorderConditions($table)
	{
		$condition = array();
		$condition[] = 'group_id = '.(int) $table->group_id;
		return $condition;
	}

	public function delete(&$cid) {
		if (count( $cid ))
		{
			$cids = implode(',', $cid);
			try {
				$db = JFactory::getDbo();
				$db->setQuery('DELETE FROM #__djc2_items_extra_fields_values_text WHERE field_id IN ('.$cids.') ');
				$db->query();
			} 
			catch (Exception $e) {
				$this->setError($e->getMessage());
				return false;
			}
			try {
				$db = JFactory::getDbo();
				$db->setQuery('DELETE FROM #__djc2_items_extra_fields_values_int WHERE field_id IN ('.$cids.') ');
				$db->query();
			} 
			catch (Exception $e) {
				$this->setError($e->getMessage());
				return false;
			}
			try {
				$db = JFactory::getDbo();
				$db->setQuery('DELETE FROM #__djc2_items_extra_fields_values_date WHERE field_id IN ('.$cids.') ');
				$db->query();
			}
			catch (Exception $e) {
				$this->setError($e->getMessage());
				return false;
			}
		}
		return parent::delete($cid);
	}
	
	public function saveOptions($values, &$table, $newField) {
		$db = JFactory::getDbo();
		if (!empty($values) && array_key_exists('id', $values) && array_key_exists('option', $values) && array_key_exists('position', $values)) {
			if ($table->type == 'select' || $table->type == 'checkbox' || $table->type == 'radio') {
				
				//$db->setQuery('SELECT MAX(ordering) FROM #__djc2_items_extra_fields_options WHERE field_id='.(int)$table->id);
				//$max = $db->loadResult();
				
				$pks = array();
                
                $max = 1;
				
				foreach ($values['id'] as $key=>$id) {
					if ($values['option'][$key]) {
						$fo_table = JTable::getInstance('FieldOptions', 'Djcatalog2Table', array());
						$isNew = true;
						// Load the row if saving an existing record.
						if ($id > 0 && $newField === false) {
							$fo_table->load($id);
							$isNew = false;
						}
						
						$data = array();
						$data['id'] = $isNew ? null:$id;
						//$data['value'] = htmlspecialchars($values['option'][$key]);
						$data['value'] = ($values['option'][$key]);
						$data['ordering'] = ($values['position'][$key] > 0) ? $values['position'][$key] : 0;
						$data['field_id'] = $table->id;
						// Bind the data.
						if (!$fo_table->bind($data)) {
							$this->setError($fo_table->getError());
							return false;
						}
						if (empty($fo_table->ordering) || !$fo_table->ordering) {
							$fo_table->ordering = $max;
						}
                        $max = $fo_table->ordering + 1;
						// Check the data.
						if (!$fo_table->check()) {
							$this->setError($fo_table->getError());
							return false;
						}
			
						// Store the data.
						if (!$fo_table->store()) {
							$this->setError($fo_table->getError());
							return false;
						}
						
						$pks[] = $fo_table->id;
					}
				}
				if (!empty($pks)) {
					$db->setQuery('DELETE FROM #__djc2_items_extra_fields_options WHERE field_id='.(int)$table->id.' AND id NOT IN ('.implode(',',$pks).')');
					$db->query();
				}
			}
		}
		return true;
	}
	public function deleteOptions(&$table) {
		$db = JFactory::getDbo();
		$db->setQuery('DELETE FROM #__djc2_items_extra_fields_options WHERE field_id='.(int)$table->id);
		if (!$db->query()){
			$this->setError($db->getError());
		}
		return true;
		
		
	}
}