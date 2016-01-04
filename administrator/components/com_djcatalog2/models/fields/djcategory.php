<?php
/**
 * @version $Id: djcategory.php 320 2014-07-23 17:51:11Z michal $
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

if (!defined('DS')) {
	define('DS', DIRECTORY_SEPARATOR);
}

require_once(dirname(__FILE__).DS.'..'.DS.'..'.DS.'lib'.DS.'categories.php');

class JFormFieldDjcategory extends JFormField {
	
	protected $type = 'Djcategory';
	
protected function getInput()
	{
		$app = JFactory::getApplication();
		$attr = ''; 
		
		$class = $this->element['class'] ? $this->element['class'] : '';
		$class .= $this->element['required'] == 'true' ? ' required' : '';
		
		$attr .= $class ? ' class="'.(string) $class.'"' : '';
		$attr .= ((string) $this->element['disabled'] == 'true') ? ' disabled="disabled"' : '';

		$default_name = ($this->element['default_name']) ? '- '.JText::_($this->element['default_name']).' -':null;
		$default_value = ($this->element['default']) ? $this->element['default'] : '';

		if ($default_name && $default_value == $this->value && $default_value == '') {
			$this->value = -1;
		}
		
		$allowed_categories = array();
		if (!empty($this->element['allowed_categories'])) {
			if (!is_array($this->element['allowed_categories'])) {
				$allowed_categories = explode(',', $this->element['allowed_categories']);
			}
		}
		
		$ignored_values = array();
		if (!empty($this->element['ignored_values'])) {
			if (!is_array($this->element['ignored_values'])) {
				$ignored_values = explode(',', $this->element['ignored_values']);
			}
		}
		
		if (is_array($this->value)) {
			foreach($this->value as $k=>$v) {
				if (in_array($v, $ignored_values)) {
					unset($this->value[$k]);
				}
			}
		} else {
			if (in_array($this->value, $ignored_values)) {
				$this->value = null;
			}
		}
		
		$context = $app->isAdmin() ? 'admin' : 'site';
		$context .= '.'.$app->input->get('option').'.'.$app->input->get('view').'.'.$app->input->get('layout');
		$current_category = ($context == 'admin.com_djcatalog2.category.edit') ? $app->input->get('id', null, 'int') : null;
		
		$categories = Djc2Categories::getInstance();
		
		$category_limit = (isset($this->element['limit'])) ? (int)$this->element['limit'] : null;

		$html = '';
		if ($category_limit > 0) {
			$optionList = $categories->getOptionList('--', ($this->element['parent'] !='true') ? false:true, null, false, $allowed_categories, $default_value);
			$values = $this->value;
			if (!is_array($values)) {
				$values = array($values);				
			}
			$values = array_reverse($values);
			//$html .= '<fieldset>';
			for ($i = 0; $i < $category_limit; $i++) {
				$current_value = (count($values) > 0) ? array_pop($values) : null;
				$html .= '<div class="control-label">';
				$html .= '<label for="'.$this->id.'_'.$i.'">'.JText::_('COM_DJCATALOG2_ADDITIONAL_CATEGORY').' #'.($i+1).'</label>';
				$html .= '</div><div class="controls">';
				$html .= JHTML::_('select.genericlist', $optionList, $this->name, trim($attr), 'value', 'text', $current_value, $this->id.'_'.$i);
				$html .= '</div>';
			}
			//$html .= '</fieldset>';
		} else if ($category_limit === null) {
			$attr .= $this->element['size'] ? ' size="'.(int) $this->element['size'].'"' : '';
			$attr .= $this->element['multiple']=='true' ? ' multiple="multiple"' : '';
			
			$optionList = $categories->getOptionList($default_name, ($this->element['parent'] !='true') ? false:true, $current_category, ($this->element['default_disable'] == 'true') ? true:false, $allowed_categories, $default_value);
			
			$html = JHTML::_('select.genericlist', $optionList, $this->name, trim($attr), 'value', 'text', $this->value, $this->id);
		}
		return ($html);
		
	}
}
?>