<?php
/**
 * @version $Id: djfieldgroup.php 139 2013-08-01 11:50:28Z michal $
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

defined('_JEXEC') or die();
defined('JPATH_BASE') or die;

jimport('joomla.html.html');
jimport('joomla.form.formfield');

class JFormFieldDjfieldgroup extends JFormField {
	
	protected $type = 'Djfieldgroup';
	
	protected function getInput()
	{
		// Initialize some field attributes.
		$size		= $this->element['size'] ? ' size="'.(int) $this->element['size'].'"' : '';
		$maxLength	= $this->element['maxlength'] ? ' maxlength="'.(int) $this->element['maxlength'].'"' : '';
		$allowswitching = (isset($this->element['allowswitching']) && $this->element['allowswitching'] =='true') ? true: false; 
		$attr = '';
		
		$class = $this->element['class'] ? (string) $this->element['class'] : '';
		$class .= $this->element['required']=='true' ? ' required' : '';

		$attr .= 'class="'.$class.'"';
		
		$db = JFactory::getDbo();
		$db->setQuery('SELECT id AS value, name AS text FROM #__djc2_items_extra_fields_groups ORDER BY text ASC');
		$groups = $db->loadObjectList();
		$options = array();
		$default_label = $this->element['required']=='true' ? JText::_('COM_DJCATALOG2_CHOOSE_FIELDGROUP') : JText::_('COM_DJCATALOG2_CONFIG_NONE');
		$default_value = $this->element['required']=='true' ? '' : '0';
		$options[] = JHTML::_('select.option', $default_value, '- '.$default_label.' -');
		$selected = null;
		
		foreach ($groups as $group) {
			if ($group->value == $this->value) {
				$selected = $group->text;
			}
			$options[] = JHTML::_('select.option', $group->value, $group->text);
		}
		if ($this->value == null || $this->value=='' /*|| $this->value == 0*/ || $allowswitching) {
			$out = JHtml::_('select.genericlist', $options, $this->name, trim($attr), 'value', 'text', $this->value, $this->id);			
		} else {
			$out = '<input id="'.$this->id.'" type="hidden" name="'.$this->name.'" value="'.$this->value.'" />';
				
			$out .= '<input type="text" value="'.(($selected) ? $selected : '- '.JText::_('COM_DJCATALOG2_CONFIG_NONE').' -').'"' .
				$size.' readonly="readonly" class="readonly" '.$maxLength.'/>';
		}
		
		return ($out);
		
	}
}
?>