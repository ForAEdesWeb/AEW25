<?php
/**
 * @version $Id: extrafields_legacy.php 294 2014-06-28 19:59:07Z michal $
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

$app = JFactory::getApplication();
$document = JFactory::getDocument();

if ($document instanceof JDocumentHTML) {
	if (!count(array_diff(ob_list_handlers(),array('default output handler')))) {
		ob_clean();
	}
}

$out = '<ul class="adminformlist">';
foreach ($this->fields as $k=>$v) {
	$input = null;
	$lblClass = (int)$v->required == 1 ? 'class="required"' : '';
	
	switch ($v->type) {
		case 'text': {
			$class = (int)$v->required == 1 ? 'inputbox required' : 'inputbox';
			$class = 'class="'.$class.'"';
			
			$input = '
				<li>
					<label for="attribute_'.$v->id.'" '.$lblClass.'>
					'.$v->name.'
					</label>
					<input size="40" id="attribute_'.$v->id.'" type="text" name="attribute['.$v->id.']" value="'.htmlspecialchars($v->field_value).'" '.$class.' />
				</li>
				';
			break;
		}
		case 'textarea':
		//case 'html':  
		{
			$class = (int)$v->required == 1 ? 'inputbox required' : 'inputbox';
			$class = 'class="'.$class.'"';
			$input = '
				<li>
					<label for="attribute_'.$v->id.'" '.$lblClass.'>
					'.$v->name.'
					</label>
					<textarea rows="3" cols="30" id="attribute_'.$v->id.'" name="attribute['.$v->id.']" '.$class.'>'.htmlspecialchars($v->field_value).'</textarea>
					<div class="clr"></div>
				</li>
				';
			break;
		}
		case 'html': {
			/*
			if ($document instanceof JDocumentHTML) {
				$editor = JFactory::getEditor(null);
				$editor_content = $editor->display('attribute['.$v->id.']', $v->field_value, '100%', '250', '20', '10', false, 'attribute_'.$v->id);
					
				$input = '
				<li>
					<label for="attribute_'.$v->id.'">
					'.$v->name.'
					</label>
					<div class="fltlft">
					'.$editor_content.'
					</div>
				</li>
					';
			} else {*/
				$class = (int)$v->required == 1 ? 'inputbox required' : 'inputbox';
				$class = 'class="nicEdit '.$class.'"';
				
				$input = '
				<li>
					<label for="attribute_'.$v->id.'" '.$lblClass.'>
					'.$v->name.'
					</label>
					<div class="clr"></div>
					<textarea '.$class.' style="height: 200px; width: 300px" rows="10" cols="40" id="attribute_'.$v->id.'" name="attribute['.$v->id.']">'.htmlspecialchars($v->field_value).'</textarea>
					<div class="clr"></div>
				</li>
				';
				
			//}
			
			
			break;
		}
		/*case 'html': {
			$class = (int)$v->required == 1 ? 'inputbox required' : 'inputbox';
			$class = 'class="nicEdit '.$class.'"';
			$input = '
				<li>
					<label for="attribute_'.$v->id.'" '.$lblClass.'>
					'.$v->name.'
					</label>
					<div class="clr"></div>
					<textarea '.$class.' style="width: 100%; min-width: 300px" rows="3" cols="40" id="attribute_'.$v->id.'" name="attribute['.$v->id.']">'.htmlspecialchars($v->field_value).'</textarea>
					<div class="clr"></div>
				</li>
					';
			break;
		}*/
		case 'select': {
			$class = (int)$v->required == 1 ? 'inputbox required' : 'inputbox';
			$class = 'class="'.$class.'"';
			
			if (empty($v->optionlist)) break;
			$options = $v->optionlist;
			$optionList = '<option value="">---</option>';
			foreach ($options as $option) {
				$selected = ($option->id == $v->field_value) ? 'selected="selected"' : '';
				$optionList .= '<option '.$selected.' value="'.$option->id.'">'.htmlspecialchars($option->value).'</option>';
			}
			$input = '
				<li>
					<label for="attribute_'.$v->id.'" '.$lblClass.'>'.$v->name.'</label>
					<select id="attribute_'.$v->id.'" name="attribute['.$v->id.']" '.$class.'>'.$optionList.'</select>
				</li>
				';
			break;
		}
		case 'checkbox': {
			if (empty($v->optionlist)) break;
			$options = $v->optionlist;
			$optionList = null;
			$values = explode('|', $v->field_value);
			
			$class = (int)$v->required == 1 ? 'checkboxes checkbox required' : 'checkbox checkboxes';
			$class = 'class="fltlft '.$class.'"';
			
			$i = 0;
			foreach ($options as $option) {
				$selected = (in_array($option->id, $values)) ? 'checked="checked"' : '';
				$optionList .= '
					<input id="attribute_'.$v->id.''.$i.'" type="checkbox" '.$selected.' name="attribute['.$v->id.'][]" value="'.$option->id.'" />
					<label for="attribute_'.$v->id.''.$i++.'">'.htmlspecialchars($option->value).'</label>';
			}
			$input = '
				<li>
					<span class="faux-label">'.$v->name.'</span>
					<fieldset '.$class.' id="attribute_'.$v->id.'">
						'.$optionList.'
					</fieldset>
				</li>
			';
			break;
		}
		case 'radio': {
			if (empty($v->optionlist)) break;
			$options = $v->optionlist;
			$optionList = null;
			
			$class = (int)$v->required == 1 ? 'radio required' : 'radio';
			$class = 'class="'.$class.'"';
			
			$i = 0;
			foreach ($options as $option) {
				$selected = ($option->id == $v->field_value) ? 'checked="checked"' : '';
				$optionList .= '
					<label for="attribute_'.$v->id.''.($i).'" for="attribute_'.$v->id.''.$i.'-lbl">'.htmlspecialchars($option->value).'</label>
					<input id="attribute_'.$v->id.''.($i++).'" type="radio" '.$selected.' name="attribute['.$v->id.']" value="'.$option->id.'" />';
			}
			$input = '
				<li>
					<span class="faux-label">'.$v->name.'</span>
					<fieldset id="attribute_'.$v->id.'" '.$class.'>
						'.$optionList.'
					</fieldset>
				</li>
			';
			break;
		}
		case 'calendar': {
			$class = (int)$v->required == 1 ? 'inputbox required' : 'inputbox';
			$class = 'class="djc_calendar '.$class.'"';
			$input = '
				<li>
					<label for="attribute_'.$v->id.'" '.$lblClass.'>
					'.$v->name.'
					</label>
					<input '.$class.' size="40" id="attribute_'.$v->id.'" type="text" name="attribute['.$v->id.']" value="'.htmlspecialchars($v->field_value).'" />
					'.JHTML::_('image', 'system/calendar.png', JText::_('JLIB_HTML_CALENDAR'), array('class' => 'calendar', 'id' => 'attribute_'.$v->id . '_img'), true).'
				</li>
				';
			break;
		}
		default: break;
	}
	
	$out .= $input.'<div style="clear:both; border-bottom:1px dashed #ccc; width: 100%; padding-top: 10px; margin-bottom: 10px;"></div>';
}
$out .= '</ul>';
echo $out;

if ($document instanceof JDocumentHTML) {
	$app->close();
}
