<?php
/**
 * @version $Id: field.raw.php 105 2013-01-23 14:05:57Z michal $
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

jimport('joomla.application.component.controllerform');

class Djcatalog2ControllerField extends JControllerForm {
	function __construct($config = array())
	{
		parent::__construct($config);

	}
	/*function display($cachable = false, $urlparams = false) {
		$fieldtype = JRequest::getVar('fieldtype');
		$fieldId = JRequest::getVar('fieldId', 0);
		$suffix = JRequest::getVar('suffix', null);
		$db = JFactory::getDbo();

		$out = '';

		switch ($fieldtype) {
			case 'select':
			case 'radio':
			case 'checkbox': {
				$out .= '
				<div class="control-group">
					<div class="control-label">
					<label>'
					.JText::_('COM_DJCATALOG2_FIELD_TYPE_'.strtoupper($fieldtype))
					.' '
					.JText::_('COM_DJCATALOG2_FIELD_TYPE_OPTIONS').'</label>
					</div>
					<div class="controls">	
						<span class="btn" onclick="Djfieldtype_'.$suffix.'.appendOption();">
						'.JText::_('COM_DJCATALOG2_FIELD_TYPE_ADD_OPTION').'
						</span>
					</div>
				</div>'
				;
				
				$out .= '<div class="clearfix"></div>
					 	<table class="table-condensed">
					 	<thead>
					 		<tr>
					 			<th>'.JText::_('COM_DJCATALOG2_FIELD_OPTION_NAME').'</th>
					 			<th>'.JText::_('COM_DJCATALOG2_FIELD_OPTION_POSITION').'</th>
					 		</tr>
					 	</thead>
					 	<tbody id="DjfieldOptions">'
					 ;
				if ($fieldId > 0) {
					$db->setQuery('SELECT * FROM #__djc2_items_extra_fields_options WHERE field_id='.(int)$fieldId.' ORDER BY ordering ASC');
					$options = $db->loadObjectList();
					if (count($options)) {
						foreach ($options as $option) {
							$out .= '<tr>
								 <td>
									 <input type="hidden" name="fieldtype[id][]" value="'.$option->id.'"/>
									 <input type="text" size="30" name="fieldtype[option][]" value="'.$option->value.'" class="input-medium" />
								 </td>
								 <td>
									 <input type="text" size="4" name="fieldtype[position][]" value="'.$option->ordering.'" class="input-mini" /><span class="btn button-x">&nbsp;&nbsp;&minus;&nbsp;&nbsp;</span><span class="btn button-down">&nbsp;&nbsp;&darr;&nbsp;&nbsp;</span><span class="btn button-up">&nbsp;&nbsp;&nbsp;&uarr;&nbsp;&nbsp;&nbsp;</span>
								 </td>
								 </tr>'
								 ;
						}
					}
				}
				$out .'</tbody>
					</table>';
				break;
			}
			default: {
				break;
			}
		}

		echo $out;
	}
	function getForm() {
		$itemId = JRequest::getVar('itemId',0);
		$groupId = JRequest::getVar('groupId',0);
		$out = null;
		$db = JFactory::getDbo();
		if ($groupId > 0){
			$query = $db->getQuery(true);
			$query->select('f.*');
			$query->from('#__djc2_items_extra_fields AS f');
			
			$query->select('GROUP_CONCAT(v.value SEPARATOR \'|\') AS field_value');
			$query->join('LEFT','#__djc2_items_extra_fields_values AS v ON f.id=v.field_id AND v.item_id='.(int)$itemId);
			$query->where('f.group_id='.(int)$groupId);
			$query->group('f.id');
			$query->order('f.ordering');
			
			$db->setQuery($query);
			$fields = ($db->loadObjectList());

			if (count($fields)) {
				$out .= '<ul class="adminformlist">';
				foreach ($fields as $k=>$v) {
					$input = null;
					switch ($v->type) {
						case 'text': {
							$input = '
								<div class="control-group">
									<div class="control-label">
										<label for="attribute_'.$v->id.'">
										'.$v->name.'
										</label>
									</div>
									<div class="controls">
										<input size="40" id="attribute_'.$v->id.'" type="text" name="attribute['.$v->id.']" value="'.$v->field_value.'" />
									</div>
								</div>
								';
							break;
						}
						case 'textarea': {
							$input = '
								<div class="control-group">
									<div class="control-label">
										<label for="attribute_'.$v->id.'">
										'.$v->name.'
										</label>
									</div>
									<div class="controls">
										<textarea rows="3" cols="30" id="attribute_'.$v->id.'" name="attribute['.$v->id.']">'.$v->field_value.'</textarea>
									</div>
								</div>
								';
							break;
						}
						case 'html': {
							$editor = JFactory::getEditor();
							$input = '
								<div class="control-group">
									<div class="control-label">
										<label for="attribute_'.$v->id.'">
										'.$v->name.'
										</label>
									</div>
									<div class="controls">
										'.$editor->display( 'attribute['.$v->id.']', $v->field_value, '', '', '', '',false).'
									</div>
								</div>
									';
							break;
						}
						case 'select': {
							$db->setQuery('SELECT * FROM #__djc2_items_extra_fields_options WHERE field_id='.(int)$v->id.' ORDER BY ordering ASC');
							$options = $db->loadObjectList();
							$optionList = '<option value="">---</option>';
							foreach ($options as $option) {
								$selected = ($option->id == $v->field_value) ? 'selected="selected"' : '';
								$optionList .= '<option '.$selected.' value="'.$option->id.'">'.$option->value.'</option>';
							}
							$input = '
								<div class="control-group">
									<div class="control-label">
										<label for="attribute_'.$v->id.'">
										'.$v->name.'
										</label>
									</div>
									<div class="controls">
										<select id="attribute_'.$v->id.'" name="attribute['.$v->id.']">'.$optionList.'</select>
									</div>
								</div>
								';
							break;
						}
						case 'checkbox': {
							$db->setQuery('SELECT * FROM #__djc2_items_extra_fields_options WHERE field_id='.(int)$v->id.' ORDER BY ordering ASC');
							$options = $db->loadObjectList();
							$optionList = null;
							$values = explode('|', $v->field_value);
							$i = 1;
							foreach ($options as $option) {
								$selected = (in_array($option->id, $values)) ? 'checked="checked"' : '';
								$optionList .= '
									<label for="attribute_'.$v->id.'_'.$i.'">'.$option->value.'</label>
									<input id="attribute_'.$v->id.'_'.$i++.'" type="checkbox" '.$selected.' name="attribute['.$v->id.'][]" value="'.$option->id.'">';
							}
							$input = '
								<div class="control-group">
									<div class="control-label">
										<label>
										'.$v->name.'
										</label>
									</div>
									<div class="controls">
										<fieldset id="attribute_'.$v->id.'">
											'.$optionList.'
										</fieldset>
									</div>
								</div>
							';
							break;
						}
					case 'radio': {
							$db->setQuery('SELECT * FROM #__djc2_items_extra_fields_options WHERE field_id='.(int)$v->id.' ORDER BY ordering ASC');
							$options = $db->loadObjectList();
							$optionList = null;
							$i = 1;
							foreach ($options as $option) {
								$selected = ($option->id == $v->field_value) ? 'checked="checked"' : '';
								$optionList .= '
									<label for="attribute_'.$v->id.'_'.$i.'">'.$option->value.'</label>
									<input id="attribute_'.$v->id.'_'.$i++.'" type="radio" '.$selected.' name="attribute['.$v->id.']" value="'.$option->id.'">';
							}
							$input = '
								<div class="control-group">
									<div class="control-label">
										<label>
										'.$v->name.'
										</label>
									</div>
									<div class="controls">
										<fieldset id="attribute_'.$v->id.'">
											'.$optionList.'
										</fieldset>
									</div>
								</div>
							';
							break;
						}
						default: break;
					}
					
					$out .= $input;
				}
				$out .= '</ul>';
			} else {
				$out = JText::_('COM_DJCATALOG2_NO_FIELDS_IN_GROUP');
			}
		} else {
			$out = JText::_('COM_DJCATALOG2_CHOOSE_FIELDGROUP_FIRST');
		}
		echo $out;
	}*/
}
?>
