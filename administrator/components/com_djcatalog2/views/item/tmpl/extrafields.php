<?php
/**
 * @version $Id: extrafields.php 294 2014-06-28 19:59:07Z michal $
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

$out = '<div class="adminformlist">';
foreach ($this->fields as $k=>$v) {
	$input = null;
	$lblClass = (int)$v->required == 1 ? 'class="required"' : '';
	switch ($v->type) {
		case 'text': {
			$class = (int)$v->required == 1 ? 'input required' : 'input';
			$class = 'class="'.$class.'"';
			$input = '
					<div class="control-label">
						<label for="attribute_'.$v->id.'" '.$lblClass.'>
						'.$v->name.'
						</label>
					</div>
					<div class="controls">
						<input size="40" id="attribute_'.$v->id.'" type="text" name="attribute['.$v->id.']" value="'.htmlspecialchars($v->field_value).'" '.$class.'/>
					</div>
				';
			break;
		}
		case 'textarea':
			$class = (int)$v->required == 1 ? 'input required' : 'input';
			$class = 'class="'.$class.'"';
		//case 'html': 
		{
			$input = '
					<div class="control-label">
						<label for="attribute_'.$v->id.'" '.$lblClass.'>
						'.$v->name.'
						</label>
					</div>
					<div class="controls">
						<textarea rows="3" cols="30" id="attribute_'.$v->id.'" name="attribute['.$v->id.']" '.$class.'>'.htmlspecialchars($v->field_value).'</textarea>
					</div>
				';
			break;
		}
		case 'html': {
			if ($document instanceof JDocumentHTML) {
				$editor = JFactory::getEditor(null);
				$input = '
					<div class="control-label">
						<label for="attribute_'.$v->id.'" '.$lblClass.'>
							'.$v->name.'
						</label>
					</div>
					<div class="controls">
						'.$editor->display( 'attribute['.$v->id.']', $v->field_value, '100%', '250', '0', '0',false, 'attribute_'.$v->id).'
					</div>
					';
			} else {
				$class = (int)$v->required == 1 ? 'nicEdit required' : 'nicEdit';
				$class = 'class="'.$class.'"';
				$input = '
					<div class="control-label">
						<label for="attribute_'.$v->id.'" '.$lblClass.'>
							'.$v->name.'
						</label>
					</div>
					<div class="controls">
						<textarea '.$class.' style="height: 300px; width: 500px" rows="10" cols="40" id="attribute_'.$v->id.'" name="attribute['.$v->id.']">'.htmlspecialchars($v->field_value).'</textarea>
					</div>
					';
			}
			
			break;
			
		}
		/*case 'html': {
			$class = (int)$v->required == 1 ? 'nicEdit input-xxlarge required' : 'nicEdit input-xxlarge';
			$class = 'class="'.$class.'"';
			$input = '
					<div class="control-label">	
						<label for="attribute_'.$v->id.'" '.$lblClass.'>
							'.$v->name.'
						</label>
					</div>
					<div class="controls">
						<textarea '.$class.' style="min-width: 400px" rows="10" cols="40" id="attribute_'.$v->id.'" name="attribute['.$v->id.']">'.htmlspecialchars($v->field_value).'</textarea>
					</div>
					';
			break;
		}*/
		case 'select': {
			if (empty($v->optionlist)) break;
			$options = $v->optionlist;
			$optionList = '<option value="">---</option>';
			
			$class = (int)$v->required == 1 ? 'input required' : 'input';
			$class = 'class="'.$class.'"';
			
			foreach ($options as $option) {
				$selected = ($option->id == $v->field_value) ? 'selected="selected"' : '';
				$optionList .= '<option '.$selected.' value="'.$option->id.'">'.htmlspecialchars($option->value).'</option>';
			}
			$input = '
					<div class="control-label">
						<label for="attribute_'.$v->id.'" '.$lblClass.'>'.$v->name.'</label>
					</div>
					<div class="controls">
						<select id="attribute_'.$v->id.'" name="attribute['.$v->id.']" '.$class.'>'.$optionList.'</select>
					</div>
				';
			break;
		}
		case 'checkbox': {
			if (empty($v->optionlist)) break;
			$options = $v->optionlist;
			$optionList = null;
			$values = explode('|', $v->field_value);
			
			$class = (int)$v->required == 1 ? 'checkboxes checkbox required' : 'checkbox checkboxes';
			$class = 'class="'.$class.'"';
			
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
			if (empty($v->optionlist)) break;
			$options = $v->optionlist;
			$optionList = null;
			
			$class = (int)$v->required == 1 ? 'radio required' : 'radio';
			$class = 'class="'.$class.'"';
			
			$i = 0;
			foreach ($options as $option) {
				$selected = ($option->id == $v->field_value) ? 'checked="checked"' : '';
				$optionList .= '
					<input id="attribute_'.$v->id.''.($i).'" type="radio" '.$selected.' name="attribute['.$v->id.']" value="'.$option->id.'" />
					<label for="attribute_'.$v->id.''.$i.'" for="attribute_'.$v->id.''.'-'.'-lbl">'.htmlspecialchars($option->value).'</label>';
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
			$class = (int)$v->required == 1 ? 'djc_calendar input required' : 'djc_calendar input';
			$class = 'class="'.$class.'"';
			
			$input = '
				<div class="control-label">
					<label for="attribute_'.$v->id.'" '.$lblClass.'>
					'.$v->name.'
					</label>
				</div>
				<div class="controls">
					<input '.$class.' size="40" id="attribute_'.$v->id.'" type="text" name="attribute['.$v->id.']" value="'.htmlspecialchars($v->field_value).'" />
					<button class="button btn" id="attribute_'.$v->id.'_img"><i class="icon-calendar"></i></button>
				</div>
				';
			break;
		}
		default: break;
	}
	$out .= '<div class="control-group">'.$input.'</div>';
}

$out .= '</div>';
echo $out;

if ($document instanceof JDocumentHTML) {
	$app->close();
}
