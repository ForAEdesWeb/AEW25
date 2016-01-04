<?php
/**
 * @version $Id: fielddata_legacy.php 139 2013-08-01 11:50:28Z michal $
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

switch ($this->fieldtype) {
	case 'select':
	case 'radio':
	case 'checkbox': {
		$out .= '<span class="faux-label">'
		.JText::_('COM_DJCATALOG2_FIELD_TYPE_'.strtoupper($this->fieldtype))
		.' '
		.JText::_('COM_DJCATALOG2_FIELD_TYPE_OPTIONS').'</span>
		<div class="button2-left">
			<div class="blank">
				<span onclick="Djfieldtype_'.$this->suffix.'.appendOption();">
				'.JText::_('COM_DJCATALOG2_FIELD_TYPE_ADD_OPTION').'
				</span>
			</div>
		</div>'
		;
		
		$out .= '<div class="clr"></div>
			 	<table>
			 	<thead>
			 		<tr>
			 			<th>'.JText::_('COM_DJCATALOG2_FIELD_OPTION_NAME').'</th>
			 			<th>'.JText::_('COM_DJCATALOG2_FIELD_OPTION_POSITION').'</th>
			 		</tr>
			 	</thead>
			 	<tbody id="DjfieldOptions">'
			 ;
		if ($this->fieldId > 0) {
			if (count($this->fieldoptions)) {
				foreach ($this->fieldoptions as $option) {
					$out .= '<tr>
						 <td>
						 <input type="hidden" name="fieldtype[id][]" value="'.$option->id.'"/>
						 <input type="text" size="30" name="fieldtype[option][]" value="'.$option->value.'" class="inputbox required"/>
						 </td>
						 <td>
						 <input type="text" size="4" name="fieldtype[position][]" value="'.$option->ordering.'" class="inputbox"/>
						 <div class="button2-left"><div class="blank"><span class="button-x">&nbsp;&nbsp;&minus;&nbsp;&nbsp;</span></div></div>
						 <div class="button2-left"><div class="blank"><span class="button-down">&nbsp;&nbsp;&darr;&nbsp;&nbsp;</span></div></div>
                                 <div class="button2-left"><div class="blank"><span class="button-up">&nbsp;&nbsp;&nbsp;&uarr;&nbsp;&nbsp;&nbsp;</span></div></div>
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