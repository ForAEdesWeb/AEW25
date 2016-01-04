<?php
/**
 * @version $Id: djproducer.php 143 2013-10-02 14:36:44Z michal $
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


class JFormFieldDjproducer extends JFormField {
	
	protected $type = 'Djproducer';
	
	protected function getInput()
	{
		$attr = '';

		$attr .= $this->element['class'] ? ' class="'.(string) $this->element['class'].'"' : '';
		$attr .= ((string) $this->element['disabled'] == 'true') ? ' disabled="disabled"' : '';
		$attr .= $this->element['size'] ? ' size="'.(int) $this->element['size'].'"' : '';
		
		$user = JFactory::getUser();
		$db	= JFactory::getDBO();
		
		$user_id = false;
		
		if (!empty($this->element['validate'])) {
			if (!empty($this->element['validate_user'])) {
				$user_id = (int)$this->element['validate_user'];
			} else {
				$user_id = (int)$user->id;
			}
		}

		$where = ($user_id !== false) ? 'WHERE created_by='.(int)$user_id : '';
		
		$query = "SELECT * FROM #__djc2_producers ".$where." ORDER BY name";
		
		$db->setQuery($query);
		$producers = $db->loadObjectList();
		
		$out = '';
		if (count($producers) > 1 || empty($this->element['validate'])) {
			$options = array();
			$options[] = JHTML::_('select.option', '','- '.JText::_('COM_DJCATALOG2_SELECT_PRODUCER').' -' );
			foreach($producers as $producer){
				$options[] = JHTML::_('select.option', $producer->id, $producer->name);
				
			}
			$out = JHTML::_('select.genericlist', $options, $this->name, trim($attr), 'value', 'text', $this->value);
		} else {
			$producer_id = 0;
			$producer_value = '- '.JText::_('COM_DJCATALOG2_NOT_AVAILABLE').' -';
			if (count($producers) == 1){
				$producer_id = $producers[0]->id;
				$producer_value = $producers[0]->name;
			}
			$out = '<input type="text" readonly="readonly"  value="'.$producer_value.'" class="inputbox input readonly" disabled="true"/>';
			$out .= '<input type="hidden" name="'.$this->name.'" value="'.$producer_id.'" id="'.$this->id.'" />';
		}
		return ($out);
	}
}
?>