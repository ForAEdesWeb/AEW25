<?php
/**
 * @version $Id: view.raw.php 139 2013-08-01 11:50:28Z michal $
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

jimport('joomla.application.component.view');

class Djcatalog2ViewItemform extends JViewLegacy {
	
	protected $itemId;
	protected $groupId;
	
	public function display($tpl = null)
	{
		$app = JFactory::getApplication();
		$this->itemId = (int)$app->input->getInt('itemId',0);
		$this->groupId = (int)$app->input->getInt('groupId',0);

		$db = JFactory::getDbo();
		
		if ($this->groupId >= 0){
			$query = $db->getQuery(true);
			$query->select('f.*');
			$query->from('#__djc2_items_extra_fields AS f');
			$query->select('CASE '
						.'WHEN (f.type=\'text\' OR f.type=\'textarea\' OR f.type=\'html\') ' 
						.'THEN vt.value '
						.'WHEN (f.type=\'calendar\') ' 
						.'THEN vd.value '
						.'WHEN (f.type=\'checkbox\' OR f.type=\'select\' OR f.type=\'radio\') '
						.'THEN GROUP_CONCAT(vi.value SEPARATOR \'|\')'
						.'ELSE "" END AS field_value');
			$query->join('LEFT','#__djc2_items_extra_fields_values_text AS vt ON f.id=vt.field_id AND vt.item_id='.(int)$this->itemId);
			$query->join('LEFT','#__djc2_items_extra_fields_values_int AS vi ON f.id=vi.field_id AND vi.item_id='.(int)$this->itemId);
			$query->join('LEFT','#__djc2_items_extra_fields_values_date AS vd ON f.id=vd.field_id AND vd.item_id='.(int)$this->itemId);
			$query->where('f.group_id='.(int)$this->groupId.' OR f.group_id=0');
			$query->group('f.id');
			$query->order('f.group_id asc, f.ordering asc');
			//echo str_replace('#_', 'jos', (string)$query);die();
			$db->setQuery($query);
			$this->fields = ($db->loadObjectList('id'));

			if (count($this->fields)) {
				$fieldIds = array_keys($this->fields);
				$db->setQuery('SELECT * FROM #__djc2_items_extra_fields_options WHERE field_id IN ('.implode(',', $fieldIds).') ORDER BY field_id ASC, ordering ASC');
				$optionList = $db->loadObjectList();
				
				foreach($this->fields as $field_id => $field) {
					foreach ($optionList as $optionRow) {
						if ($optionRow->field_id == $field_id) {
							if (empty($field->optionlist)) {
								$this->fields[$field_id]->optionlist = array();
							}
							$this->fields[$field_id]->optionlist[] = $optionRow;
						}
					}
				}
			} else {
				echo JText::_('COM_DJCATALOG2_NO_FIELDS_IN_GROUP');
				return;
			}
		} else {
			//echo JText::_('COM_DJCATALOG2_CHOOSE_FIELDGROUP_FIRST');
			return;
		}
		
		parent::display($tpl);
	}
	
}
?>