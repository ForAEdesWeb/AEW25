<?php
/**
 * @version $Id: extrafields.php 191 2013-11-03 07:15:27Z michal $
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

$out = '';
foreach ($this->fields as $k=>$v) {
	$input = null;
	$lblClass = (int)$v->required == 1 ? 'class="required"' : '';
	switch ($v->type) {
		case 'text': {
			$class = (int)$v->required == 1 ? 'input inputbox required' : 'input inputbox';
			$class = 'class="'.$class.'"';
			$input = '
					<div class="control-label">
						<label for="attribute_'.$v->id.'" '.$lblClass.'>
						'.$v->name.'
						</label>
					</div>
					<div class="controls">
						<input size="40" '.$class.' id="attribute_'.$v->id.'" type="text" name="attribute['.$v->id.']" value="'.htmlspecialchars($v->field_value).'" />
					</div>
				';
			break;
		}
		case 'textarea': 
		//case 'html': 
		{
			$class = (int)$v->required == 1 ? 'input inputbox textarea required' : 'input inputbox textarea';
			$class = 'class="'.$class.'"';
			$input = '
					<div class="control-label">
						<label for="attribute_'.$v->id.'" '.$lblClass.'>
						'.$v->name.'
						</label>
					</div>
					<div class="controls">
						<textarea '.$class.' cols="30" id="attribute_'.$v->id.'" name="attribute['.$v->id.']">'.htmlspecialchars($v->field_value).'</textarea>
					</div>
				';
			break;
		}
		case 'html': {
			$class = (int)$v->required == 1 ? 'nicEdit input-xxlarge input inputbox textarea required' : 'nicEdit input-xxlarge input inputbox textarea';
			$class = 'class="'.$class.'"';
			$input = '
					<div class="control-label">	
						<label for="attribute_'.$v->id.'" '.$lblClass.'>
							'.$v->name.'
						</label>
					</div>
					<div class="controls">
						<textarea '.$class.' rows="10" cols="40" id="attribute_'.$v->id.'" name="attribute['.$v->id.']" style="width: 100%;">'.htmlspecialchars($v->field_value).'</textarea>
					</div>
					';
			break;
		}
		case 'select': {
			$options = $v->optionlist;
			if (count($options) == 0) {
				break;
			}
			$optionList = '<option value="">---</option>';
			
			$class = (int)$v->required == 1 ? 'input inputbox required' : 'input inputbox';
			$class = 'class="'.$class.'"';
			
			foreach ($options as $option) {
				$selected = ($option->id == $v->field_value) ? 'selected="selected"' : '';
				$optionList .= '<option '.$selected.' value="'.$option->id.'">'.htmlspecialchars($option->value).'</option>';
			}
			$input = '
					<div class="control-label" '.$lblClass.'>
						<label for="attribute_'.$v->id.'">'.$v->name.'</label>
					</div>
					<div class="controls">
						<select '.$class.' id="attribute_'.$v->id.'" name="attribute['.$v->id.']">'.$optionList.'</select>
					</div>
				';
			break;
		}
		case 'checkbox': {
			$options = $v->optionlist;
			if (count($options) == 0) {
				break;
			}
			$optionList = null;
			$values = explode('|', $v->field_value);
			
			$class = (int)$v->required == 1 ? 'checkbox checkboxes required' : 'checkbox checkboxes';
			$class = 'class="fltlft '.$class.'"';
			
			$i = 0;
			foreach ($options as $option) {
				$selected = (in_array($option->id, $values)) ? 'checked="checked"' : '';
				$optionList .= '
					<input id="attribute_'.$v->id.''.$i.'" type="checkbox" '.$selected.' name="attribute['.$v->id.'][]" value="'.$option->id.'" />
					<label for="attribute_'.$v->id.''.$i.'">'.htmlspecialchars($option->value).'</label>
					';
				$i++;
			}
			$input = '
					<div class="control-label">
						<label>'.$v->name.'</label>
					</div>
					<div class="controls">
						<fieldset id="attribute_'.$v->id.'" '.$class.'>
							'.$optionList.'
						</fieldset>
					</div>
			';
			break;
		}
		case 'radio': {
			$options = $v->optionlist;
			if (count($options) == 0) {
				break;
			}
			$optionList = null;
			
			$class = (int)$v->required == 1 ? 'radio required' : 'radio';
			$class = 'class="'.$class.'"';
			
			$i = 0;
			foreach ($options as $option) {
				$selected = ($option->id == $v->field_value) ? 'checked="checked"' : '';
				$optionList .= '
					<label for="attribute_'.$v->id.''.$i.'" for="attribute_'.$v->id.''.'-'.'-lbl">'.htmlspecialchars($option->value).'<input id="attribute_'.$v->id.''.($i).'" type="radio" '.$selected.' name="attribute['.$v->id.']" value="'.$option->id.'" /></label>';
				$i++;
			}
			$input = '
					<div class="control-label">
						<label>'.$v->name.'</label>
					</div>
					<div class="controls">
						<fieldset id="attribute_'.$v->id.'" '.$class.'>
							'.$optionList.'
						</fieldset>
					</div>
			';
			break;
		}
		case 'calendar': {
			$class = (int)$v->required == 1 ? 'djc_calendar input inputbox required' : 'djc_calendar input inputbox';
			$class = 'class="'.$class.'"';
			$input = '
				<div class="control-label">
					<label for="attribute_'.$v->id.'" '.$lblClass.'>
					'.$v->name.' <small>[RRRR-MM-DD]</small>
					</label>
				</div>
				<div class="controls">
					<input '.$class.' size="40" id="attribute_'.$v->id.'" type="text" name="attribute['.$v->id.']" value="'.htmlspecialchars($v->field_value).'" />
					'.JHtml::_('image', 'system/calendar.png', JText::_('JLIB_HTML_CALENDAR'), array('class' => 'calendar', 'id' => 'attribute_'.$v->id . '_img'), true).'
				</div>
				';
			break;
		}
		default: break;
	}
	$out .= '<div class="control-group formelm">'.$input.'</div>';
}

echo $out;
